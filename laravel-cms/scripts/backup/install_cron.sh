#!/usr/bin/env bash
set -Eeuo pipefail

PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
BACKUP_SCRIPT="$PROJECT_ROOT/scripts/backup/backup_and_push.sh"
CRON_LOG="${BACKUP_CRON_LOG:-$PROJECT_ROOT/storage/logs/backup-cron.log}"
CRON_EXPR="${BACKUP_CRON_EXPR:-0 */6 * * *}"
MARKER="# hovi-cms-auto-backup"

mkdir -p "$(dirname "$CRON_LOG")"

if [[ ! -x "$BACKUP_SCRIPT" ]]; then
  echo "[cron] ERROR: backup script is missing or not executable: $BACKUP_SCRIPT"
  exit 1
fi

cron_line="$CRON_EXPR cd '$PROJECT_ROOT' && /usr/bin/env bash '$BACKUP_SCRIPT' >> '$CRON_LOG' 2>&1 $MARKER"
current_cron="$(crontab -l 2>/dev/null || true)"

updated_cron="$(printf "%s\n" "$current_cron" | sed '/# hovi-cms-auto-backup$/d')"
updated_cron="$(printf "%s\n%s\n" "$updated_cron" "$cron_line" | awk 'NF')"

printf "%s\n" "$updated_cron" | crontab -

echo "[cron] Installed cron job: $cron_line"
