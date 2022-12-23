#!/usr/bin/env bash

###################################
# wp-cli helper
#
# Usage: ./bin/wp.sh 'command'
#
# Example: Get a list of users.
# Command: ./bin/wp.sh 'user list'
#
# Example: Update admin user password.
# Command: ./bin/wp.sh 'user update admin --user_pass=admin'
#
# Example: Create post with 'page' post type.
# Command: ./bin/wp.sh 'post create --post_type=page --post_title="New Page" --post_content="Hello World"'
##################################

npx wp-env run cli \
    "wp ${@:-help}" 2>/dev/null