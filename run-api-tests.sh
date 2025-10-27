#!/usr/bin/env bash
set -euo pipefail
set -x

SCRIPTDIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"

APIURL=${APIURL:-http://127.0.0.1:8001/api}
USERNAME=${USERNAME:-u$(date +%s)}
EMAIL=${EMAIL:-${USERNAME}@mail.com}
PASSWORD=${PASSWORD:-password}
DELAY_REQUEST=${DELAY_REQUEST:-"50"}

npx newman run "$SCRIPTDIR/tests/Postman/Conduit.postman_collection.json" \
  --delay-request "$DELAY_REQUEST" \
  --global-var "APIURL=${APIURL}" \
  --global-var "USERNAME=${USERNAME}" \
  --global-var "EMAIL=${EMAIL}" \
  --global-var "PASSWORD=${PASSWORD}" \
  "$@"
