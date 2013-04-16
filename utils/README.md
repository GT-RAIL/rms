rms Build Setup
===============

[Phing](http://www.phing.info/) is used for building, minimizing, documenting, linting, and testing of PHP files.

[Grunt](http://gruntjs.com/) is used for building, including minimizing, documenting, linting, and testing of JavaScript files.

### Install Phing, Grunt, and their Dependencies

#### Ubuntu

 1. Install PHP Pear, Phing, and CodeSniffer
   * `sudo apt-get install php-pear`
   * `sudo pear channel-discover pear.phing.info`
   * `sudo pear install phing/phing`
   * `sudo pear install PHP_CodeSniffer`
 2. Install Node.js and its package manager, NPM
   * `sudo apt-get install python-software-properties`
   * `sudo add-apt-repository ppa:chris-lea/node.js`
   * `sudo apt-get update && sudo apt-get install nodejs phantomjs`
 3. Install Grunt and the test runner [Karma](http://karma-runner.github.io/)
   * `sudo npm install -g grunt-cli karma`
   * `sudo rm -rf ~/.npm ~/tmp`
 4. Install the Grunt tasks specific to this project
   * `cd /path/to/ros3djs/utils/`
   * `npm install .`
 5. (Optional) To generate the documentation, you'll need to setup Java and phpDocumentor 2. Documentation generation is not required for patches.
   * `echo "export JAVA_HOME=/usr/lib/jvm/default-java/jre" >> ~/.bashrc`
   * `source ~/.bashrc`
   * `sudo pear channel-discover pear.phpdoc.org`
   * `sudo pear install phpdoc/phpDocumentor-alpha`

### Build with Phing

Before proceeding, please confirm you have installed the dependencies above.

To run the build tasks:

 1. `cd /path/to/rms/utils/`
 2. `phing`

`phing build` will minimize the JavaScript files under `src` and place the new built project into `build`. It will also run the linter. This is what [Travis CI](https://travis-ci.org/WPI-RAIL/rms) runs when a Pull Request is submitted.

`phing doc` will document all PHP and JavaScript files and place them in the `doc` folder.

