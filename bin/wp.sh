#!/usr/bin/env bash

npx wp-env run cli \
    "${@:-help core}" 2>/dev/null