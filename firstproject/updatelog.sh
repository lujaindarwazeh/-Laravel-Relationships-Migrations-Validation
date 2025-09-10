#!/bin/bash

# Folder containing your Laravel logs
LOG_FOLDER="./storage/logs"

# Temporary folder for filtered logs
TMP_FOLDER="./storage/tmp_logs"
mkdir -p "$TMP_FOLDER"

# Clear previous temp logs
rm -f "$TMP_FOLDER"/*.log

# Find logs modified in the last N days (change -2 or -1) and copy them to TMP_FOLDER
find "$LOG_FOLDER" -type f -name "*.log" -mtime -2 -exec cp {} "$TMP_FOLDER" \;

echo "Filtered logs from last day(s) copied to $TMP_FOLDER"

# Restart promtail to pick up the filtered logs
docker-compose restart promtail