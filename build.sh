#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_DIR="$ROOT_DIR/plugin"
ARCHIVE="$ROOT_DIR/dist/yooinstantsearch.zip"

cd "$PLUGIN_DIR"

composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build

mkdir -p "$ROOT_DIR/dist"
rm -f "$ARCHIVE"
zip -qr "$ARCHIVE" assets builder services src vendor yooinstantsearch.xml

echo "Created $ARCHIVE"
