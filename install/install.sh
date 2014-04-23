#!/bin/sh

DB="rms"
USER="rms"

## Update and install the LAMP server
echo
echo "Setting up LAMP, PhpMyAdmin, and PEAR..."
echo

sudo apt-get update
sudo apt-get install lamp-server^ -yy
sudo apt-get install phpmyadmin php-pear
sudo a2enmod rewrite
sudo service apache2 restart

## Setup CakePHP
echo
echo "Setting up CakePHP..."
echo

sudo pear channel-discover pear.cakephp.org
sudo pear install cakephp/CakePHP-2.4.7 cakephp/CakePHP_CodeSniffer

## Install the app
echo
echo "Linking app to Apache..."
echo

sudo cp rms.conf /etc/apache2/sites-available/
sudo a2dissite 000-default.conf
sudo a2ensite rms.conf
sudo rm -f /var/www/rms
sudo ln -s `pwd`/../app/ /var/www/rms
sudo /etc/init.d/apache2 restart

## Create a tmp folder
echo
echo "Setting up site tmp folder..."
echo

mkdir -p ../app/tmp
sudo chown -R www-data ../app/tmp

## Setup the SQL server
echo
echo "Setting MySQL server..."
echo

PASS=`date +%s | sha256sum | base64 | head -c 16 ;`

mysql -u root -v -e "DROP DATABASE IF EXISTS $DB; \
GRANT USAGE ON *.* TO '$USER'@'localhost'; \
DROP USER '$USER'@'localhost'; \
CREATE DATABASE IF NOT EXISTS $DB; \
GRANT ALL PRIVILEGES ON $DB.* TO '$USER'@'localhost' IDENTIFIED BY '$PASS';\G " -p
mysql -D $DB -u $USER -p$PASS < rms.sql


echo
echo "Setup complete!"
echo
