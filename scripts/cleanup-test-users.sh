#!/bin/bash

# Cleanup E2E Test Users
# Deletes all subscriber users (test users) except admin

echo "🧹 Cleaning up test users..."

# Use a temp file to avoid ANSI code issues with command substitution
TEMP_FILE=$(mktemp)
trap "rm -f $TEMP_FILE" EXIT

# Get user IDs and write to temp file
wp-env run cli wp user list --field=ID --role=subscriber --format=csv 2>/dev/null > "$TEMP_FILE"

# Read and filter IDs from file (disable grep color output to avoid ANSI codes)
USER_IDS=$(grep --color=never -E '^[0-9]+$' "$TEMP_FILE" | tr '\n' ' ' | xargs)

if [ -z "$USER_IDS" ]; then
    echo "✅ No test users to clean up"
    exit 0
fi

# Delete the users
# shellcheck disable=SC2086
wp-env run cli wp user delete $USER_IDS --yes 2>&1 | grep --color=never -E '(Success|Removed)'

echo "✅ Deleted test users: $USER_IDS"

exit 0
