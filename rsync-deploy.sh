#!/bin/bash

# This file allows you to deploy this project to a remote server via rsync.
# Replace all "TODO" entries with your own values.

if [ -z "${SOURCE_DIRECTORY}" ]; then
  SOURCE_DIRECTORY="$(git rev-parse --show-toplevel)/"
fi
if [ -z "${TARGET_DIRECTORY}" ]; then
  TARGET_DIRECTORY="/home/nginx/{{ TODO }}"
fi

DEPLOY_TARGET="$1"
if [ -z "${DEPLOY_TARGET}" ]; then
  DEPLOY_TARGET="$(cat ${SOURCE_DIRECTORY}/.rsync-target 2>/dev/null)"
fi
if [ -z "${DEPLOY_TARGET}" ]; then
  read -p "Enter the server to deploy to: " DEPLOY_TARGET
fi
if [ -z "${DEPLOY_TARGET}" ]; then
  printf "Deploy target not provided, aborting...\n" 1>&2
  exit 1
fi
echo "${DEPLOY_TARGET}" > ${SOURCE_DIRECTORY}/.rsync-target

set -e

printf "[1/4] Getting version identifier of this deploy...\n"
DEPLOY_VERSION="$(git describe --always --tags)"

printf "[2/4] Building version information into this deploy...\n"
printf "${DEPLOY_VERSION}" > ${SOURCE_DIRECTORY}/.rsync-version

printf "[3/4] Syncing to deploy target...\n"
rsync -avzc --delete --delete-excluded --delete-after --progress \
  --exclude-from="${SOURCE_DIRECTORY}/rsync-exclude.txt" \
  --chown=nginx:www-data --rsync-path="sudo rsync" \
  "${SOURCE_DIRECTORY}" \
  ${DEPLOY_TARGET}:"${TARGET_DIRECTORY}"

printf "[4/4] Post-deploy clean up...\n"
rm ${SOURCE_DIRECTORY}/.rsync-version

printf "Operation complete!\n"
