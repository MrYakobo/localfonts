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
	path=$(which open || which xdg-open || which gnome-open || echo "chromium-browser")
	"$path" "$URL" &>/dev/null || echo "open a browser on $URL") &

trap cleanup SIGINT
php -S "0.0.0.0:$PORT"

