rms [![Build Status](https://api.travis-ci.org/WPI-RAIL/rms.png)](https://travis-ci.org/WPI-RAIL/rms)
===

#### RMS (Robot Management System)

RMS is a remote lab management tool designed for use with controlling ROS enabled robots from the web.

For full documentation, see [the ROS wiki](http://ros.org/wiki/rms) or check out some [tutorials](http://www.ros.org/wiki/rms/#Tutorials).

[API Documentation](https://robotsfor.me/developers/) can be found on the RobotsFor.Me website.

This project is released as part of the [Robot Web Tools](http://robotwebtools.org/) effort.

RMS follows the [Zend Coding Standard](http://framework.zend.com/manual/1.12/en/coding-standard.html).

### Usage
Pre-built files can be found in either the [build](build/) directory. The difference between the [build](build/) directory and the [src](src/) directory is that [build](build/) contains minified versions of any JavaScript files. This should be used on any deployed system. For development, the [src](src/) directory may be used.

To setup the RMS, please follow the [installation instructions](http://www.ros.org/wiki/rms/Tutorials/InstallTheRMS).

### Server Dependencies
rms depends on:

[Apache HTTP Server](http://projects.apache.org/projects/http_server.html). The current supported version is 0.2.2. For example setup instructions, please follow the [RMS installation instructions](http://www.ros.org/wiki/rms/Tutorials/InstallTheRMS).

[MySQL](http://www.mysql.com/). The current supported version is 5.5. For example setup instructions, please follow the [RMS installation instructions](http://www.ros.org/wiki/rms/Tutorials/InstallTheRMS).

[PHP](http://us.php.net/). The current supported version is 5.3. For example setup instructions, please follow the [RMS installation instructions](http://www.ros.org/wiki/rms/Tutorials/InstallTheRMS).

[PHP cURL](http://curl.haxx.se/libcurl/php/). The current supported version is 5.3. For example setup instructions, please follow the [RMS installation instructions](http://www.ros.org/wiki/rms/Tutorials/InstallTheRMS).

### JavaScript Dependencies
Various ROS JavaScript libraries from the [Robot Web Tools](http://robotwebtools.org/) effort are also used throughout this project. Typically, these projects are sourced from the Robot Web Tools CDN server.

[EventEmitter2](https://github.com/hij1nx/EventEmitter2). The current supported version is 0.4.11. The current supported version can be found the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js)) | ([min](http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.min.js))

[three.js](https://github.com/mrdoob/three.js/). The current supported version is r56. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/threejs/r56/three.js)) | ([min](http://cdn.robotwebtools.org/threejs/r56/three.min.js)).

[ColladaLoader2](https://github.com/crobi/ColladaAnimationCompress). The current supported version is 0.0.1. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/ColladaAnimationCompress/0.0.1/ColladaLoader2.js)) | ([min](http://cdn.robotwebtools.org/ColladaAnimationCompress/0.0.1/ColladaLoader2.min.js))

[EaselJS](https://github.com/CreateJS/EaselJS/). The current supported version is 0.6.0. The current supported version can be found [in this project](include/EaselJS/easeljs.js) or on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/EaselJS/0.6.0/easeljs.js)) | ([min](http://cdn.robotwebtools.org/EaselJS/0.6.0/easeljs.min.js))

[roslibjs](https://github.com/RobotWebTools/roslibjs). The current supported version is r5. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/roslibjs/r5/roslib.js)) | ([min](http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js))

[ros2djs](https://github.com/RobotWebTools/ros2djs). The current supported version is r1. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/ros2djs/r1/ros2d.js)) | ([min](http://cdn.robotwebtools.org/ros2djs/r1/ros2d.min.js))

[ros3djs](https://github.com/RobotWebTools/ros3djs). The current supported version is r4. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/ros3djs/r4/ros3d.js)) | ([min](http://cdn.robotwebtools.org/ros3djs/r4/ros3d.min.js))

[keyboardtelopjs](https://github.com/WPI-RAIL/keyboardtelopjs). The current supported version is r1. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/keyboardtelopjs/r1/keyboardtelop.js)) | ([min](http://cdn.robotwebtools.org/keyboardtelopjs/r1/keyboardtelop.min.js))

[mjpegcanvasjs](https://github.com/WPI-RAIL/mjpegcanvasjs). The current supported version is r1. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/mjpegcanvasjs/r1/mjpegcanvas.js)) | ([min](http://cdn.robotwebtools.org/mjpegcanvasjs/r1/mjpegcanvas.min.js))

[nav2djs](https://github.com/WPI-RAIL/nav2djs). The current supported version is r1. The current supported version can be found on the Robot Web Tools CDN: ([full](http://cdn.robotwebtools.org/nav2djs/r1/nav2d.js)) | ([min](http://cdn.robotwebtools.org/nav2djs/r1/nav2d.min.js))

[SlideJS](https://github.com/nathansearles/Slides). This script is auto-linked in RMS.

[tablesorter](http://tablesorter.com). The current supported version is 2.0.5b. This script is auto-linked in RMS.

[jQuery](http://jquery.com/). The current supported version is 1.7.2. This script is auto-linked in RMS.

[jQuery UI](http://jqueryui.com/). The current supported version is 1.8.22. This script is auto-linked in RMS.

### Build
Checkout [utils/README.md](utils/README.md) for details on building.

### License
rms is released with a BSD license. For full terms and conditions, see the [LICENSE](LICENSE) file.

### Authors
See the [AUTHORS.md](AUTHORS) file for a full list of contributors.

