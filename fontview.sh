#!/bin/sh

# this script contains a tar file with all files needed to run the web server
# run with `curl git.io/fontview | bash`
# install with `curl git.io/fontview_install | bash`

# requirements: python3, php

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
