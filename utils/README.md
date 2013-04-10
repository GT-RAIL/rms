rms Build Setup
===============

[Phing](http://www.phing.info/) is used for building, minimizing, documenting, linting, and testing.

### Install Phing and its Dependencies

#### Ubuntu

 1. Install PHP Pear and Phing
   * `sudo apt-get install php-pear`
   * `sudo pear channel-discover pear.phing.info`
   * `sudo pear install phing/phing`

### Build with Phing

Before proceeding, please confirm you have installed the dependencies above.

To run the build tasks:

 1. `cd /path/to/rms/utils/`
 2. `phing`

`phing build` will minimize the JavaScript files under `src` and place the new built project into `build`. It will also run the linter. This is what [Travis CI](https://travis-ci.org/WPI-RAIL/rms) runs when a Pull Request is submitted.

`phing doc` will document all PHP and JavaScript files and place them in the `doc` folder.
