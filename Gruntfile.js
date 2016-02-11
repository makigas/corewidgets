/* 
 * This file is part of the makigas corewidgets platform.
 * Copyright (C) 2013-2016 Dani Rodríguez
 */

'use strict';

module.exports = function (grunt) {
  // Load grunt tasks in extra modules.
  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
    // Load project configuration
    pkg: grunt.file.readJSON('package.json'),
    config: {
      src: 'src',
      dist: 'dist'
    },
    clean: {
      dist: ['<%= config.dist %>']
    },
    compress: {
      dist: {
        options: {
          archive: '<%= pkg.name %>.<%= pkg.version %>.zip'
        },
        expand: true,
        cwd: '<%= config.dist %>',
        src: '**/*',
        dest: '<%= pkg.name %>/'
      }
    },
    copy: {
      dist: {
        options: {
          keepSpecialComments: 1
        },
        files: [{
          expand: true,
          dot: true,
          cwd: '<%= config.src %>',
          dest: '<%= config.dist %>',
          src: '**/*.{html,php}'
        },
        {
          expand: true,
          dest: '<%= config.dist %>',
          src: 'languages/*'
        }]
      }
    },
    rsync: {
      staging: {
        options: {
          src: './<%= config.dist %>/',
          dest: '/srv/www/makigas/htdocs/wp-content/plugins/corewidgets/',
          host: 'vagrant@makigas.dev',
          recursive: true,
          delete: true
        }
      }
    },
    pot: {
      options: {
        text_domain: 'makigas-corewidgets',
        dest: 'languages/',
        keywords: [
          '__:1',
          '_e:1',
          '_x:1,2c',
          'esc_html__:1',
          'esc_html_e:1',
          'esc_html_x:1,2c',
          'esc_attr__:1', 
          'esc_attr_e:1', 
          'esc_attr_x:1,2c', 
          '_ex:1,2c',
          '_n:1,2', 
          '_nx:1,2,4c',
          '_n_noop:1,2',
          '_nx_noop:1,2,3c'
        ]
      },
      files: {
        src: [ 'src/**/*.php' ],
        expand: true
      }
    },
    po2mo: {
      files: {
        src: 'languages/*.po',
        expand: true
      }
    }
  });

  grunt.registerTask('build', [
    'clean:dist',
    'po2mo',
    'copy:dist'
  ]);
  
  grunt.registerTask('dist', [
    'build',
    'compress'
  ]);
  
  grunt.registerTask('deploy', [
    'build',
    'rsync:staging'
  ]);
};
