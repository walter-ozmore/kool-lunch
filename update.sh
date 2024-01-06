# This script update dependencies

wod="/var/www/walter-ozmore.dev"
kld="/var/www/kool-lunch"

sudo cp -r $wod/account $kld
sudo cp -r $wod/res/wo-lib.css $kld/res
sudo cp -r $wod/res/wo-lib.js $kld/res