rms [![Build Status](https://api.travis-ci.org/WPI-RAIL/rms.png)](https://travis-ci.org/WPI-RAIL/rms)
===

#### RMS (Robot Management System)

RMS is a remote lab and user study management tool designed for use with controlling ROS enabled robots from the web. RMS is built on-top of the popular [CakePHP](http://cakephp.org/) Model-View-Controller (MVC) framework.

For full documentation, see [the ROS wiki](http://ros.org/wiki/rms) or check out some [tutorials](http://www.ros.org/wiki/rms/#Tutorials).

This project is released as part of the [Robot Web Tools](http://robotwebtools.org/) effort.

### Setup
To setup RMS on an Ubuntu web server, run the automated script in the [install](install) directory:

```bash
cd install
./install.bash
```

This script will do the following tasks:
* Update and install the LAMP server
* Setup [CakePHP](http://cakephp.org/)
* Install the RMS
* Create a tmp folder
* Setup the SQL server

The RMS has been tested and developed on Ubuntu 14.04 and Ubuntu 12.04.

Note that your default username and password are the following:
* Username: admin
* Password: myremotelab

### Update
To update your version of the RMS with the latest code, run the automated script in the [install](install) directory:

```bash
git pull origin master
cd install
./update.bash
```

### Build
Checkout [utils/README.md](utils/README.md) for details on building if you are contributing code.

### License
rms is released with a BSD license. For full terms and conditions, see the [LICENSE](LICENSE) file.

### Authors
See the [AUTHORS](AUTHORS.md) file for a full list of contributors.
