#!/bin/bash

# Cleanup E2E Test Users
# Deletes all subscriber users (test users) except admin

echo "🧹 Cleaning up test users..."

# Get all subscriber user IDs (excluding admin)
USER_IDS=$(wp-env run cli wp user list --field=ID --role=subscriber --format=csv 2>/dev/null | tail -n +2)

if [ -z "$USER_IDS" ]; then
    echo "✅ No test users to clean up"
    exit 0
fi

# Convert newlines to spaces for the delete command
USER_IDS_SPACE=$(echo "$USER_IDS" | tr '\n' ' ')

# Delete the users
wp-env run cli wp user delete $USER_IDS_SPACE --yes 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Deleted test users: $USER_IDS_SPACE"
else
    echo "⚠️  Some users may not have been deleted"
fi

exit 0
