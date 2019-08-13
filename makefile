fontview: fontview.sh index.php install.php gfonts
	cat fontview.sh > $@

	echo 'exit 0' >> $@
	echo '#EOF' >> $@

	tar cz index.php install.php gfonts >> $@

	chmod +x $@