# localfonts

There is no such thing as a good font viewer. Unless this one, of course.

![localfonts](localfonts.png)

# Try it

`curl git.io/fontview | bash`

# Dependencies
python3, php

This project is loosely based on [this](https://askubuntu.com/a/1005724/) AskUbuntu answer. The UI is shamelessy taken from the excellent [Google Fonts](https://fonts.google.com).

There is a feature to install fonts from Google locally. First, select the fonts you want at [Google Fonts](https://fonts.google.com). Then, copy the `<link href="...">` tag and paste it in the install section. This will download the font in TTF and place it in `~/.local/share/fonts`.

The script `fontview` contains a tar file with all files needed to run the web server.
run with `curl git.io/fontview | bash`
install with `curl git.io/fontview_install | bash`