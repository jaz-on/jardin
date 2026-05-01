#!/usr/bin/env bash
# Verify files required for jardin-theme/header-utilities and header toolbar PHP are present (run from CI or locally).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"
missing=0
for f in \
	inc/header-toolbar.php \
	blocks/header-utilities/block.json \
	blocks/header-utilities/render.php \
	blocks/header-utilities/index.js \
	inc/blocks.php \
	functions.php
do
	if [[ ! -f "$f" ]]; then
		echo "MISSING: $f" >&2
		missing=1
	fi
done
if ! grep -q 'header-toolbar.php' functions.php; then
	echo "ERROR: functions.php must require inc/header-toolbar.php" >&2
	missing=1
fi
if ! grep -q "header-utilities" inc/blocks.php; then
	echo "ERROR: inc/blocks.php must register header-utilities block" >&2
	missing=1
fi
if [[ "$missing" -ne 0 ]]; then
	exit 1
fi
echo "OK: header deploy files present under ${ROOT}"
