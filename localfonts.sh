#!/bin/bash

function cleanup(){
	rm -rf $dir
}

dir=$(mktemp -d)
SELF="$( cd "$(dirname "$0")" ; pwd -P )/localfonts"

cd $dir
sed '1,/^#EOF$/d' < "$SELF" | tar xz
PORT=$(./get_free_port.py)

URL="http://localhost:$PORT" 
([[ -x $BROWSER ]] && "$BROWSER" "$URL" || \
	path=$(which open 2>/dev/null || which xdg-open 2>/dev/null || which gnome-open 2>/dev/null || echo "chromium-browser")
	"$path" "$URL" &>/dev/null || echo "open a browser on $URL") &

trap cleanup SIGINT
php -S "0.0.0.0:$PORT"

