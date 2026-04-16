#!/usr/bin/env bash
set -Eeuo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
cd "$PROJECT_ROOT"

declare -A preserved_env
preserve_keys=(
  DB_DATABASE
  BACKUP_LOCAL_DIR
  BACKUP_TMP_DIR
  BACKUP_ASSET_PATHS
  BACKUP_KEEP_COUNT
  BACKUP_GIT_REMOTE
  BACKUP_GIT_BRANCH
  BACKUP_GIT_DIR
  BACKUP_GIT_SUBDIR
  BACKUP_GIT_USER_NAME
  BACKUP_GIT_USER_EMAIL
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

BACKUP_LOCAL_DIR="${BACKUP_LOCAL_DIR:-$PROJECT_ROOT/.local-backups}"
BACKUP_TMP_DIR="${BACKUP_TMP_DIR:-$PROJECT_ROOT/.backup-tmp}"
BACKUP_ASSET_PATHS="${BACKUP_ASSET_PATHS:-public/uploads storage/app/public}"
BACKUP_KEEP_COUNT="${BACKUP_KEEP_COUNT:-56}"
BACKUP_GIT_REMOTE="${BACKUP_GIT_REMOTE:-}"
BACKUP_GIT_BRANCH="${BACKUP_GIT_BRANCH:-main}"
BACKUP_GIT_DIR="${BACKUP_GIT_DIR:-$PROJECT_ROOT/.backup-repo}"
BACKUP_GIT_SUBDIR="${BACKUP_GIT_SUBDIR:-backups/hovi-cms}"
BACKUP_GIT_USER_NAME="${BACKUP_GIT_USER_NAME:-}"
BACKUP_GIT_USER_EMAIL="${BACKUP_GIT_USER_EMAIL:-}"

resolve_path() {
  local value="$1"
  if [[ "$value" = /* ]]; then
    echo "$value"
  else
    echo "$PROJECT_ROOT/$value"
  fi
}

BACKUP_LOCAL_DIR="$(resolve_path "$BACKUP_LOCAL_DIR")"
BACKUP_TMP_DIR="$(resolve_path "$BACKUP_TMP_DIR")"
BACKUP_GIT_DIR="$(resolve_path "$BACKUP_GIT_DIR")"

if [[ -z "$BACKUP_GIT_REMOTE" ]]; then
  BACKUP_TARGET_MODE="repo"
  echo "[backup] INFO: BACKUP_GIT_REMOTE is empty. Using current repository as backup target."
else
  BACKUP_TARGET_MODE="remote"
fi

for cmd in docker tar gzip sha256sum; do
  if ! command -v "$cmd" >/dev/null 2>&1; then
    echo "[backup] ERROR: Missing required command: $cmd"
    exit 1
  fi
done

if ! command -v git >/dev/null 2>&1; then
  echo "[backup] ERROR: Missing required command: git"
  exit 1
fi

timestamp="$(date -u +%Y%m%dT%H%M%SZ)"
run_tmp_dir="$BACKUP_TMP_DIR/$timestamp"
run_output_dir="$BACKUP_LOCAL_DIR/$timestamp"

mkdir -p "$run_tmp_dir" "$run_output_dir"

db_name="${DB_DATABASE:-hovi_cms}"
db_dump_file="$run_tmp_dir/db-${db_name}-${timestamp}.sql.gz"
asset_archive_file="$run_tmp_dir/assets-${timestamp}.tar.gz"
manifest_file="$run_tmp_dir/manifest-${timestamp}.txt"

echo "[backup] Dumping database from mysql container..."
docker compose exec -T mysql sh -lc 'exec mysqldump --single-transaction --quick --lock-tables=false -uroot -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' \
  | gzip -c > "$db_dump_file"

echo "[backup] Archiving asset paths..."
declare -a existing_asset_paths=()
for relative_path in $BACKUP_ASSET_PATHS; do
  if [[ -e "$PROJECT_ROOT/$relative_path" ]]; then
    existing_asset_paths+=("$relative_path")
  else
    echo "[backup] WARN: Skip missing path: $relative_path"
  fi
done

if (( ${#existing_asset_paths[@]} > 0 )); then
  tar -czf "$asset_archive_file" -C "$PROJECT_ROOT" "${existing_asset_paths[@]}"
else
  echo "[backup] WARN: No asset paths found. Creating empty archive placeholder."
  tar -czf "$asset_archive_file" --files-from /dev/null
fi

echo "[backup] Writing manifest..."
{
  echo "timestamp=$timestamp"
  echo "project_root=$PROJECT_ROOT"
  echo "db_dump=$(basename "$db_dump_file")"
  echo "assets_archive=$(basename "$asset_archive_file")"
  sha256sum "$db_dump_file"
  sha256sum "$asset_archive_file"
} > "$manifest_file"

cp -a "$run_tmp_dir/." "$run_output_dir/"
rm -rf "$run_tmp_dir"

prune_old_dirs() {
  local target_dir="$1"
  local keep_count="$2"

  if [[ ! -d "$target_dir" ]]; then
    return 0
  fi

  mapfile -t old_dirs < <(find "$target_dir" -mindepth 1 -maxdepth 1 -type d -printf '%f\n' | sort -r | tail -n +$((keep_count + 1)))
  for dir_name in "${old_dirs[@]:-}"; do
    [[ -z "$dir_name" ]] && continue
    rm -rf "$target_dir/$dir_name"
  done
}

prune_old_dirs "$BACKUP_LOCAL_DIR" "$BACKUP_KEEP_COUNT"

if [[ "$BACKUP_TARGET_MODE" = "repo" ]]; then
  if ! git -C "$PROJECT_ROOT" rev-parse --is-inside-work-tree >/dev/null 2>&1; then
    echo "[backup] ERROR: Project directory is not a git repository: $PROJECT_ROOT"
    exit 1
  fi

  backup_git_worktree="$PROJECT_ROOT"
  backup_push_branch="$(git -C "$PROJECT_ROOT" branch --show-current || true)"
  if [[ -z "$backup_push_branch" ]]; then
    backup_push_branch="$BACKUP_GIT_BRANCH"
  fi
else
  backup_git_worktree="$BACKUP_GIT_DIR"
  backup_push_branch="$BACKUP_GIT_BRANCH"

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

if [[ -n "$BACKUP_GIT_USER_NAME" ]]; then
  git -C "$backup_git_worktree" config user.name "$BACKUP_GIT_USER_NAME"
fi

if [[ -n "$BACKUP_GIT_USER_EMAIL" ]]; then
  git -C "$backup_git_worktree" config user.email "$BACKUP_GIT_USER_EMAIL"
fi

backup_repo_target="$backup_git_worktree/$BACKUP_GIT_SUBDIR"
mkdir -p "$backup_repo_target/$timestamp"
cp -a "$run_output_dir/." "$backup_repo_target/$timestamp/"

prune_old_dirs "$backup_repo_target" "$BACKUP_KEEP_COUNT"

git -C "$backup_git_worktree" add "$BACKUP_GIT_SUBDIR"
if git -C "$backup_git_worktree" diff --cached --quiet; then
  echo "[backup] No changes to commit."
else
  git -C "$backup_git_worktree" commit -m "backup: $timestamp"

  if git -C "$backup_git_worktree" remote get-url origin >/dev/null 2>&1; then
    git -C "$backup_git_worktree" push origin "$backup_push_branch"
  else
    echo "[backup] WARN: No origin remote configured; skipped push."
  fi
fi

echo "[backup] Local backup saved at: $run_output_dir"
echo "[backup] Repository snapshot path: $BACKUP_GIT_SUBDIR/$timestamp"
echo "[backup] Done: $timestamp"
