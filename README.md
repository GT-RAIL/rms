rms [![Build Status](https://api.travis-ci.org/WPI-RAIL/rms.png)](https://travis-ci.org/WPI-RAIL/rms)
===

#### RMS (Robot Management System)

RMS is a remote lab and user study management tool designed for use with controlling ROS enabled robots from the web.

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

### Build
Checkout [utils/README.md](utils/README.md) for details on building if you are contributing code.

### License
rms is released with a BSD license. For full terms and conditions, see the [LICENSE](LICENSE) file.

### Authors
See the [AUTHORS](AUTHORS.md) file for a full list of contributors.
