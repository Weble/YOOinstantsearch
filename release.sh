#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_DIR="$ROOT_DIR/plugin"

cd "$ROOT_DIR"

[[ "$(git branch --show-current)" == "develop" ]] || { echo "Run from develop." >&2; exit 1; }
[[ -z "$(git status --porcelain)" ]] || { echo "Release requires a clean worktree." >&2; exit 1; }

PS3="Release type: "
select TYPE in patch minor major; do
    [[ -n "$TYPE" ]] && break
done

(
    cd "$PLUGIN_DIR"
    npm version "$TYPE" --no-git-tag-version --ignore-scripts
)

VERSION="$(node -p "require('./plugin/package.json').version")"
printf '%s\n' "$VERSION" > VERSION
perl -0pi -e "s{<version>[^<]+</version>}{<version>$VERSION</version>}" "$PLUGIN_DIR/yooinstantsearch.xml"

./build.sh

git add VERSION "$PLUGIN_DIR/package.json" "$PLUGIN_DIR/package-lock.json" "$PLUGIN_DIR/yooinstantsearch.xml" \
    "$PLUGIN_DIR/assets/yooinstantsearch.min.js" "$PLUGIN_DIR/assets/yooinstantsearch.min.js.LICENSE.txt" "$PLUGIN_DIR/mix-manifest.json"
git commit -m "Bump version to $VERSION"

git switch main
git merge develop
git tag "v$VERSION"
git push origin develop main "v$VERSION"
git switch develop

echo "Released v$VERSION"
