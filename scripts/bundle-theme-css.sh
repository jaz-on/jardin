#!/usr/bin/env bash
# Rebuild assets/css/theme-base.css from domain partials.
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CSS="$ROOT/assets/css"
cat "$CSS/domains/part-01-general-home-projects.css" \
	"$CSS/domains/part-02-shell-toolbar-nav.css" \
	"$CSS/domains/part-03-footer-events-journal.css" \
	"$CSS/domains/part-04-articles-now-singles.css" \
	> "$CSS/theme-base.css"
echo "OK: $CSS/theme-base.css ($(wc -l < "$CSS/theme-base.css") lines)"
