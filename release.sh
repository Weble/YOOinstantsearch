#!/usr/bin/env bash

set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PLUGIN_DIR="plugin"
DEVELOP_BRANCH="develop"
RELEASE_BRANCH="main"

cd "$ROOT_DIR"

if [[ "$(git branch --show-current)" != "$DEVELOP_BRANCH" ]]; then
    echo "Run this script from the $DEVELOP_BRANCH branch." >&2
    exit 1
fi

if [[ -n "$(git status --porcelain)" ]]; then
    echo "Release requires a clean worktree." >&2
    exit 1
fi

if ! git remote get-url origin >/dev/null 2>&1; then
    echo "Release requires an origin remote." >&2
    exit 1
fi

if ! git show-ref --verify --quiet "refs/heads/$RELEASE_BRANCH"; then
    echo "Release branch does not exist locally: $RELEASE_BRANCH" >&2
    exit 1
fi

VERSION="$(tr -d '[:space:]' < VERSION)"

if [[ ! "$VERSION" =~ ^([0-9]+)\.([0-9]+)\.([0-9]+)$ ]]; then
    echo "Invalid VERSION: $VERSION" >&2
    exit 1
fi

MAJOR="${BASH_REMATCH[1]}"
MINOR="${BASH_REMATCH[2]}"
PATCH="${BASH_REMATCH[3]}"

PS3="Release type: "
select TYPE in patch minor major; do
    case "$TYPE" in
        patch|minor|major)
            break
            ;;
        *)
            echo "Invalid choice." >&2
            ;;
    esac
done

case "$TYPE" in
    patch)
        PATCH=$((PATCH + 1))
        ;;
    minor)
        MINOR=$((MINOR + 1))
        PATCH=0
        ;;
    major)
        MAJOR=$((MAJOR + 1))
        MINOR=0
        PATCH=0
        ;;
esac

NEW_VERSION="$MAJOR.$MINOR.$PATCH"
TAG="v$NEW_VERSION"

if git rev-parse --verify "refs/tags/$TAG" >/dev/null 2>&1; then
    echo "Tag already exists: $TAG" >&2
    exit 1
fi

printf '%s\n' "$NEW_VERSION" > VERSION
(cd "$PLUGIN_DIR" && npm version "$NEW_VERSION" --no-git-tag-version --ignore-scripts)
perl -0pi -e "s{<version>[^<]+</version>}{<version>$NEW_VERSION</version>}" "$PLUGIN_DIR/yooinstantsearch.xml"

./build.sh

git add VERSION "$PLUGIN_DIR/package.json" "$PLUGIN_DIR/package-lock.json" "$PLUGIN_DIR/yooinstantsearch.xml" \
    "$PLUGIN_DIR/assets/yooinstantsearch.min.js" "$PLUGIN_DIR/assets/yooinstantsearch.min.js.LICENSE.txt" "$PLUGIN_DIR/mix-manifest.json"
git commit -m "Bump version to $NEW_VERSION"

git switch "$RELEASE_BRANCH"
git merge "$DEVELOP_BRANCH"
git tag "$TAG"
git push origin "$DEVELOP_BRANCH" "$RELEASE_BRANCH" "$TAG"
git switch "$DEVELOP_BRANCH"

echo "Released $TAG"
