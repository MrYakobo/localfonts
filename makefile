localfonts: localfonts.sh index.php install.php
	cat localfonts.sh > $@

	echo 'exit 0' >> $@
	echo '#EOF' >> $@

	tar cz index.php install.php >> $@

	chmod +x $@
