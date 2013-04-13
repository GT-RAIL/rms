module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      files: [
        'Gruntfile.js',
        '../src/*.js',
        '../src/**/*.js'
      ]
    },
    karma: {
      build: {
        configFile: '../test/karma.conf.js',
        singleRun: true,
        browsers: ['PhantomJS']
      }
    },
    uglify: {
      options: {
        report: 'min'
      },
      build: {
        files: {
          '../build/js/rms/common.js': ['../build/js/rms/common.js'],
          '../build/js/rms/study.js': ['../build/js/rms/study.js']
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
          '../src/*.js',
          '../src/**/*.js'
        ],
        options: {
          destination: '../doc/js'
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-jsdoc');
  grunt.loadNpmTasks('grunt-karma');

  grunt.registerTask('build', ['jshint', 'uglify']);
  grunt.registerTask('doc', ['clean', 'jsdoc']);
};

