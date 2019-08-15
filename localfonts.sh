#!/bin/sh

LOWERPORT=8000
UPPERPORT=9000
while :; do PORT="`shuf -i $LOWERPORT-$UPPERPORT -n 1`"; ss -lpn | grep -q ":$PORT " || break; done

dir=$(mktemp -d)
SELF=${PWD}/$0

cd $dir
sed '1,/^#EOF$/d' < "$SELF" | tar xz
$BROWSER "http://localhost:$PORT" &
php -S 0.0.0.0:$PORT
rm -rf $dir