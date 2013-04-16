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
    csslint: {
      build: {
        options: {
          csslintrc: '.csslintrc'
        },
        src: [
          '../src/css/common.css',
          '../src/api/robot_environments/interfaces/**/*.css'
        ]
      }
    },
    cssmin: {
      options: {
        report: 'min'
      },
      build: {
        files: {
          '../build/css/common.css': ['../build/css/common.css'],
          '../build/css/jquery-ui-1.8.22.custom.css': ['../build/css/jquery-ui-1.8.22.custom.css'],
          '../build/api/robot_environments/interfaces/basic/style.css': ['../build/api/robot_environments/interfaces/basic/style.css'],
          '../build/api/robot_environments/interfaces/markers/style.css': ['../build/api/robot_environments/interfaces/markers/style.css'],
          '../build/api/robot_environments/interfaces/simple_nav2d/style.css': ['../build/api/robot_environments/interfaces/simple_nav2d/style.css']
        }
      }
    },
    imagemin: {
      build: {
        options: {
          optimizationLevel: 7
        },
        files: {
          '../build/css/images/bg.jpg': '../build/css/images/bg.jpg',
          '../build/css/images/pagination.png': '../build/css/images/pagination.png',
          '../build/css/images/ui-bg_diagonals-small_20_0b3e6f_40x40.png': '../build/css/images/ui-bg_diagonals-small_20_0b3e6f_40x40.png',
          '../build/css/images/ui-bg_diagonals-small_20_333333_40x40.png': '../build/css/images/ui-bg_diagonals-small_20_333333_40x40.png',
          '../build/css/images/ui-bg_diagonals-thick_15_022056_40x40.png': '../build/css/images/ui-bg_diagonals-thick_15_022056_40x40.png',
          '../build/css/images/ui-bg_dots-medium_30_096ac8_4x4.png': '../build/css/images/ui-bg_dots-medium_30_096ac8_4x4.png',
          '../build/css/images/ui-bg_dots-small_30_a32d00_2x2.png': '../build/css/images/ui-bg_dots-small_30_a32d00_2x2.png',
          '../build/css/images/ui-bg_flat_0_0b3e6f_40x100.png': '../build/css/images/ui-bg_flat_0_0b3e6f_40x100.png',
          '../build/css/images/ui-bg_flat_0_aaaaaa_40x100.png': '../build/css/images/ui-bg_flat_0_aaaaaa_40x100.png',
          '../build/css/images/ui-bg_flat_100_f6f6f6_40x100.png': '../build/css/images/ui-bg_flat_100_f6f6f6_40x100.png',
          '../build/css/images/ui-bg_flat_40_292929_40x100.png': '../build/css/images/ui-bg_flat_40_292929_40x100.png',
          '../build/css/images/ui-icons_0b3e6f_256x240.png': '../build/css/images/ui-icons_0b3e6f_256x240.png',
          '../build/css/images/ui-icons_9ccdfc_256x240.png': '../build/css/images/ui-icons_9ccdfc_256x240.png',
          '../build/css/images/ui-icons_ffffff_256x240.png': '../build/css/images/ui-icons_ffffff_256x240.png',
          '../build/img/logo.png': '../build/img/logo.png',
          '../build/img/real.png': '../build/img/real.png',
          '../build/img/sim.png': '../build/img/sim.png',
          '../build/img/slides/pr2.jpg': '../build/img/slides/pr2.jpg',
          '../build/img/slides/youbot_sim.jpg': '../build/img/slides/youbot_sim.jpg',
          '../build/img/slides/youbot.jpg': '../build/img/slides/youbot.jpg'
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
  grunt.loadNpmTasks('grunt-contrib-imagemin');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-csslint');
  grunt.loadNpmTasks('grunt-jsdoc');
  grunt.loadNpmTasks('grunt-karma');

  grunt.registerTask('build', ['jshint', 'uglify', 'csslint', 'cssmin', 'imagemin']);
  grunt.registerTask('doc', ['clean', 'jsdoc']);
};

