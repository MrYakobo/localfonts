#! /usr/bin/env bash

cat << __HEADER
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Sample of local fonts</title>
</head>
<body>
<div style="display: flex; flex-wrap: wrap">
__HEADER

#fc-list --format='%{family}\n' $1 | sort -u | while IFS='' read -r fontfamily
fc-list | grep -F .local/share/fonts | cut -d':' -f2 | awk '{$1=$1; print}' | cut -d',' -f1 | sort -u | while IFS='' read -r fontfamily
do
    cat << __BODY
    <div style="font-family: '${fontfamily}', 'serif'; width: 49%">
        <p style="font-size: 2em;">${fontfamily}</p>
        <p>Amanda Jakob</p>
        <p style="text-transform: uppercase">Amanda Jakob</p>
    </div>
__BODY

done

cat << __FOOTER
    </div>
    <hr>
</body>
</html>
__FOOTER
