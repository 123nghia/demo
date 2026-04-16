#!/usr/bin/env bash
set -Eeuo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$PROJECT_ROOT"

usage() {
  cat <<'EOF'
Usage:
  bash scripts/backup/restore_backup.sh [latest|TIMESTAMP] [--source local|repo|git] [--yes] [--dry-run]

Examples:
  bash scripts/backup/restore_backup.sh latest --source repo --yes
  bash scripts/backup/restore_backup.sh latest --source git --yes
  bash scripts/backup/restore_backup.sh 20260416T013256Z --source local --yes
  bash scripts/backup/restore_backup.sh latest --source repo --dry-run
EOF
}

declare -A preserved_env
preserve_keys=(
  DB_DATABASE
  BACKUP_LOCAL_DIR
  BACKUP_GIT_REMOTE
  BACKUP_GIT_BRANCH
  BACKUP_GIT_DIR
  BACKUP_GIT_SUBDIR
  BACKUP_ASSET_PATHS
  BACKUP_RESTORE_SOURCE
  BACKUP_RESTORE_TIMESTAMP
)

for key in "${preserve_keys[@]}"; do
  if [[ -n "${!key+x}" ]]; then
    preserved_env["$key"]="${!key}"
  fi
done

if [[ -f "$PROJECT_ROOT/.env" ]]; then
  set -a
  # shellcheck disable=SC1091
  source "$PROJECT_ROOT/.env"
  set +a
fi

for key in "${!preserved_env[@]}"; do
  export "$key=${preserved_env[$key]}"
done

resolve_path() {
  local value="$1"
  if [[ "$value" = /* ]]; then
    echo "$value"
  else
    echo "$PROJECT_ROOT/$value"
  fi
}

BACKUP_LOCAL_DIR="$(resolve_path "${BACKUP_LOCAL_DIR:-.local-backups}")"
BACKUP_GIT_REMOTE="${BACKUP_GIT_REMOTE:-}"
BACKUP_GIT_BRANCH="${BACKUP_GIT_BRANCH:-main}"
BACKUP_GIT_DIR="$(resolve_path "${BACKUP_GIT_DIR:-.backup-repo}")"
BACKUP_GIT_SUBDIR="${BACKUP_GIT_SUBDIR:-backups/hovi-cms}"
BACKUP_ASSET_PATHS="${BACKUP_ASSET_PATHS:-public/uploads storage/app/public}"
RESTORE_SOURCE="${BACKUP_RESTORE_SOURCE:-repo}"
RESTORE_TIMESTAMP="${BACKUP_RESTORE_TIMESTAMP:-latest}"

FORCE_NO_CONFIRM="false"
DRY_RUN="false"
TIMESTAMP_ARG=""

while [[ $# -gt 0 ]]; do
  case "$1" in
    latest)
      TIMESTAMP_ARG="latest"
      shift
      ;;
    --yes|-y)
      FORCE_NO_CONFIRM="true"
      shift
      ;;
    --dry-run)
      DRY_RUN="true"
      shift
      ;;
    --source)
      [[ $# -lt 2 ]] && { echo "[restore] ERROR: --source requires value local|repo|git"; exit 1; }
      RESTORE_SOURCE="$2"
      shift 2
      ;;
    --help|-h)
      usage
      exit 0
      ;;
    *)
      if [[ -z "$TIMESTAMP_ARG" ]]; then
        TIMESTAMP_ARG="$1"
        shift
      else
        echo "[restore] ERROR: Unknown argument: $1"
        usage
        exit 1
      fi
      ;;
  esac
done

if [[ -n "$TIMESTAMP_ARG" ]]; then
  RESTORE_TIMESTAMP="$TIMESTAMP_ARG"
fi

if [[ "$RESTORE_SOURCE" != "local" && "$RESTORE_SOURCE" != "repo" && "$RESTORE_SOURCE" != "git" ]]; then
  echo "[restore] ERROR: Invalid source '$RESTORE_SOURCE'. Use local, repo or git."
  exit 1
fi

for cmd in docker tar gzip; do
  if ! command -v "$cmd" >/dev/null 2>&1; then
    echo "[restore] ERROR: Missing required command: $cmd"
    exit 1
  fi
done

if [[ "$RESTORE_SOURCE" = "git" ]]; then
  if ! command -v git >/dev/null 2>&1; then
    echo "[restore] ERROR: Missing required command: git"
    exit 1
  fi

  if [[ -z "$BACKUP_GIT_REMOTE" ]]; then
    echo "[restore] WARN: BACKUP_GIT_REMOTE is empty. Fallback to repo source."
    RESTORE_SOURCE="repo"
  fi

fi

if [[ "$RESTORE_SOURCE" = "git" ]]; then

  if [[ ! -d "$BACKUP_GIT_DIR/.git" ]]; then
    rm -rf "$BACKUP_GIT_DIR"
    if git clone --branch "$BACKUP_GIT_BRANCH" --single-branch "$BACKUP_GIT_REMOTE" "$BACKUP_GIT_DIR" >/dev/null 2>&1; then
      :
    else
      git clone "$BACKUP_GIT_REMOTE" "$BACKUP_GIT_DIR"
      git -C "$BACKUP_GIT_DIR" checkout -B "$BACKUP_GIT_BRANCH"
    fi
  fi

  git -C "$BACKUP_GIT_DIR" fetch origin "$BACKUP_GIT_BRANCH" >/dev/null 2>&1 || true
  git -C "$BACKUP_GIT_DIR" checkout "$BACKUP_GIT_BRANCH" >/dev/null 2>&1 || git -C "$BACKUP_GIT_DIR" checkout -B "$BACKUP_GIT_BRANCH"
  git -C "$BACKUP_GIT_DIR" pull --rebase origin "$BACKUP_GIT_BRANCH" >/dev/null 2>&1 || true
fi

if [[ "$RESTORE_SOURCE" = "git" ]]; then
  base_dir="$BACKUP_GIT_DIR/$BACKUP_GIT_SUBDIR"
elif [[ "$RESTORE_SOURCE" = "repo" ]]; then
  base_dir="$PROJECT_ROOT/$BACKUP_GIT_SUBDIR"
else
  base_dir="$BACKUP_LOCAL_DIR"
fi

if [[ ! -d "$base_dir" ]]; then
  echo "[restore] ERROR: Backup directory does not exist: $base_dir"
  exit 1
fi

if [[ "$RESTORE_TIMESTAMP" = "latest" ]]; then
  RESTORE_TIMESTAMP="$(find "$base_dir" -mindepth 1 -maxdepth 1 -type d -printf '%f\n' | grep -E '^[0-9]{8}T[0-9]{6}Z$' | sort -r | head -n 1 || true)"
fi

if [[ -z "$RESTORE_TIMESTAMP" ]]; then
  echo "[restore] ERROR: No backup snapshots found in $base_dir"
  exit 1
fi

snapshot_dir="$base_dir/$RESTORE_TIMESTAMP"
if [[ ! -d "$snapshot_dir" ]]; then
  echo "[restore] ERROR: Snapshot not found: $snapshot_dir"
  exit 1
fi

db_dump_file="$(find "$snapshot_dir" -maxdepth 1 -type f -name 'db-*.sql.gz' | sort | head -n 1 || true)"
assets_archive_file="$(find "$snapshot_dir" -maxdepth 1 -type f -name 'assets-*.tar.gz' | sort | head -n 1 || true)"

if [[ -z "$db_dump_file" || ! -f "$db_dump_file" ]]; then
  echo "[restore] ERROR: Database dump file not found in $snapshot_dir"
  exit 1
fi

if [[ -z "$assets_archive_file" || ! -f "$assets_archive_file" ]]; then
  echo "[restore] ERROR: Assets archive file not found in $snapshot_dir"
  exit 1
fi

echo "[restore] source=$RESTORE_SOURCE"
echo "[restore] snapshot=$RESTORE_TIMESTAMP"
echo "[restore] db_dump=$db_dump_file"
echo "[restore] assets=$assets_archive_file"

if [[ "$DRY_RUN" = "true" ]]; then
  echo "[restore] DRY RUN mode. No changes were applied."
  exit 0
fi

if [[ "$FORCE_NO_CONFIRM" != "true" ]]; then
  echo "[restore] WARNING: This will overwrite database and asset files."
  read -r -p "Continue restore? (yes/no): " confirm
  if [[ "$confirm" != "yes" ]]; then
    echo "[restore] Cancelled"
    exit 0
  fi
fi

echo "[restore] Restoring database..."
gzip -dc "$db_dump_file" | docker compose exec -T mysql sh -lc 'exec mysql -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"'

echo "[restore] Restoring assets..."
container_project_root="/var/www/html"
container_assets_archive="${assets_archive_file/#$PROJECT_ROOT/$container_project_root}"

container_cleanup_cmd=""
container_fix_cmd=""
for relative_path in $BACKUP_ASSET_PATHS; do
  if [[ -n "$container_cleanup_cmd" ]]; then
    container_cleanup_cmd+=" "
  fi
  container_cleanup_cmd+="'$container_project_root/$relative_path'"

  container_fix_cmd+="mkdir -p '$container_project_root/$relative_path' && "
  container_fix_cmd+="chown -R www-data:www-data '$container_project_root/$relative_path' && "
  container_fix_cmd+="chmod -R ug+rwX '$container_project_root/$relative_path' && "
done

# remove trailing " && "
container_fix_cmd="${container_fix_cmd% && }"

docker compose exec -T app sh -lc "rm -rf $container_cleanup_cmd && tar -xzf '$container_assets_archive' -C '$container_project_root'"
docker compose exec -T app sh -lc "$container_fix_cmd"

echo "[restore] Done: $RESTORE_TIMESTAMP"
