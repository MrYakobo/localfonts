localfonts: localfonts.sh index.php install.php get_free_port.py
	cat localfonts.sh > $@

	echo 'exit 0' >> $@
	echo '#EOF' >> $@

	tar cz index.php install.php get_free_port.py >> $@

	chmod +x $@
