#!/usr/bin/env bash
# Fetch a public URL and check that header utility markup is present (desktop structure in DOM).
# Usage: ./scripts/smoke-header-remote.sh https://dev.example.com/
set -euo pipefail
URL="${1:-}"
if [[ -z "$URL" ]]; then
	echo "usage: $0 https://site.example/" >&2
	exit 2
fi
tmp="$(mktemp)"
trap 'rm -f "$tmp"' EXIT
if ! curl -fsSL "$URL" -o "$tmp"; then
	echo "ERROR: curl failed for $URL" >&2
	exit 1
fi
fail=0
if ! grep -qE 'class="toolbar"|wp-block-jardin-theme-header-utilities' "$tmp"; then
	echo "FAIL: no .toolbar or wp-block-jardin-theme-header-utilities in HTML (toolbar row missing? reset Header template part)" >&2
	fail=1
fi
if ! grep -q 'site-nav-drawer-tools' "$tmp"; then
	echo "FAIL: no site-nav-drawer-tools (drawer tools missing from header pattern?)" >&2
	fail=1
fi
if [[ "$fail" -ne 0 ]]; then
	exit 1
fi
echo "OK: header markers found for $URL"
