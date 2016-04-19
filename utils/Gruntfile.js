module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      files: [
        'Gruntfile.js',
        '../app/webroot/js/rms.js'
      ]
    },
    imagemin: {
      build: {
        options: {
          optimizationLevel: 7
        },
        files: {
          '../app/webroot/img/banner.jpg': '../app/webroot/img/banner.jpg',
          '../app/webroot/img/ifaces/basic/teleop.png': '../app/webroot/img/ifaces/basic/teleop.png'
        }
      }
    },
    clean: {
      options: {
        force: true
      },
      doc: ['../doc/js']
    },
    jsdoc: {
      doc: {
        src: [
          '../app/webroot/js/rms.js'
        ],
        options: {
          destination: '../doc/js'
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-jsdoc');

  grunt.registerTask('build', ['jshint', 'imagemin']);
  grunt.registerTask('doc', ['clean', 'jsdoc']);
};
