#!/bin/sh

PORT=8000
dir=$(mktemp -d)
SELF=${PWD}/$0

extract() {
	sed '1,/^#EOF$/d' < "$SELF" | tar xz
}

echo $PORT
echo $dir

cd $dir
extract
$BROWSER "http://localhost:$PORT" &
php -S 0.0.0.0:$PORT
rm -rf $dir