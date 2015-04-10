#!/bin/bash

# RMS Update Script
#
# Author: Russell Toris - rctoris@wpi.edu

DB="rms"
USER="rms"

echo " ____  __  __ ____  "
echo "|  _ \|  \/  / ___| "
echo "| |_) | |\/| \___ \ "
echo "|  _ <| |  | |___) |"
echo "|_| \_\_|  |_|____/ "
echo

echo
echo "Robot Management System Updater"
echo "Author: Russell Toris - rctoris@wpi.edu"
echo

# check the directory we are working in
DIR=`pwd`
if [[ $DIR != *install ]]
then
	echo "ERROR: Please run this script in the 'install' directory."
	exit;
fi

## Setup CakePHP
echo
echo "Updating CakePHP..."
sudo pear install cakephp/CakePHP-2.5.8 >> /dev/null

# Run the RMS updater
sudo ../app/Console/cake update

echo
echo "Update complete!"
echo
