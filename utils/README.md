rms Build Setup
===============

[Phing](http://www.phing.info/) is used for documenting linting of PHP files.

[Grunt](http://gruntjs.com/) is used for documenting and linting of JavaScript files.

### Install Phing, Grunt, and their Dependencies

#### Ubuntu 14.04

 1. Install Phing and PHP CodeSniffer
   * `sudo pear channel-discover pear.phing.info`
   * `sudo pear install phing/phing PHP_CodeSniffer-1.5.6 cakephp/CakePHP_CodeSniffer-0.1.30`
 2. Install Node.js and its package manager, NPM
   * `sudo apt-get install nodejs npm`
   * `sudo ln -s /usr/bin/nodejs /usr/bin/node`
 3. Install Grunt
   * `sudo npm install -g grunt-cli`
   * `sudo rm -rf ~/.npm ~/tmp`
 4. (Optional) To generate the documentation, you'll need to setup Java and phpDocumentor 2. Documentation generation is not required for patches.
   * `echo "export JAVA_HOME=/usr/lib/jvm/default-java/jre" >> ~/.bashrc`
   * `source ~/.bashrc`
   * `sudo apt-get install php5-xsl`
   * `sudo pear channel-discover pear.phpdoc.org`
   * `sudo pear install phpdoc/phpDocumentor`

#### Ubuntu 12.04

 1. Install Phing and PHP CodeSniffer
   * `sudo pear channel-discover pear.phing.info`
   * `sudo pear install phing/phing PHP_CodeSniffer-1.5.6 cakephp/CakePHP_CodeSniffer`
 2. Install Node.js and its package manager, NPM
   * `sudo apt-get install python-software-properties`
   * `sudo add-apt-repository ppa:chris-lea/node.js`
   * `sudo apt-get update && sudo apt-get install nodejs`
 3. Install Grunt
   * `sudo npm install -g grunt-cli`
   * `sudo rm -rf ~/.npm ~/tmp`
 4. (Optional) To generate the documentation, you'll need to setup Java and phpDocumentor 2. Documentation generation is not required for patches.
   * `echo "export JAVA_HOME=/usr/lib/jvm/default-java/jre" >> ~/.bashrc`
   * `source ~/.bashrc`
   * `sudo apt-get install php5-xsl`
   * `sudo pear channel-discover pear.phpdoc.org`
   * `sudo pear install phpdoc/phpDocumentor`

### Build with Phing

Before proceeding, please confirm you have installed the dependencies above.

To run the build tasks:

 1. `cd /path/to/rms/utils/`
 2. `phing`

`phing build` will run the linters. This is what [Travis CI](https://travis-ci.org/WPI-RAIL/rms) runs when a Pull Request is submitted.

`phing doc` will document all PHP and JavaScript files and place them in the `doc` folder.
