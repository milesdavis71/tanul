module.exports = function(grunt) {

  // Project configuration.
  // grunt.initConfig({
  //   pkg: grunt.file.readJSON('package.json'),
  //   uglify: {
  //     build: {
  //       src: 'assets/js/*.js',
  //       dest: 'assets/js/bellows.min.js'
  //     }
  //   }
  // });

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    cssmin: {
      options: {
        banner:
          "/*\n"+
           " * Bellows \n" +
           " * http://getbellows.com \n" +
           " * Copyright 2021 Chris Mavricos, SevenSpark \n" +
           " */"
      },
      minify: {
        files: {
          'assets/css/bellows.min.css' : ['assets/css/bellows.css'],
          'pro/assets/css/bellows.min.css' : ['pro/assets/css/bellows.css']
        }
      }
      /*
      minify: {
          expand: true,
          cwd: 'assets/css/',
          src: ['bellows.css'],
          dest: 'assets/css/',
          ext: '.min.css'
        }
      */
    },

    //Note: because of how grunt-closure-compiler searchs for file, we need to go into the Brew Cellar
    // and copy the libexec/closurecompiler-v20180101.jar into libexec/build/compiler.jar
    'closure-compiler': {
      frontend: {
        //closurePath: '/usr/local/Cellar/closure-compiler/20161201/libexec', // '/usr/local/opt/closure-compiler/libexec', // '/usr/local/lib/closure-compiler',
        //closurePath: '/usr/local/Cellar/closure-compiler/20180101/libexec', ///closure-compiler-v20180101.jar';
        closurePath: '/usr/local/Cellar/closure-compiler/20210601/libexec', ///closure-compiler-v20180101.jar';
        js: 'assets/js/*.js',
        jsOutputFile: 'assets/js/bellows.min.js',
        maxBuffer: 500,
        options: {
          compilation_level: 'SIMPLE_OPTIMIZATIONS',
          language_in: 'ECMASCRIPT5_STRICT'
        }
      }
    },

    less: {
      development: {
        options: {
          compress: false,
        },
        files: [
          {
            "assets/css/bellows.css": "assets/css/bellows.less"
          },
          {
             "pro/assets/css/bellows.css": "pro/assets/css/bellows.less"
          },
          // {
          //   "custom/custom-sample-skin.css": "pro/assets/css/skins/custom-skin.less"
          // },
          {
            expand: true,
            cwd: 'pro/assets/css/skins/',
            src: ['*.less'],
            dest: 'pro/assets/css/skins/',
            ext: '.css'
            // target.css file: source.less file
            //"pro/assets/css/skins/blackwhite2.css": "pro/assets/css/skins/blackwhite2.less"
          },
          {
            expand: true,
            cwd: 'pro/assets/css/skins/',
            src: ['blue-material.less','grey-material.less','vanilla.less'],
            dest: 'assets/css/skins',
            ext: '.css'
            // target.css file: source.less file
            //"pro/assets/css/skins/blackwhite2.css": "pro/assets/css/skins/blackwhite2.less"
          }
        ]
      }
    },

    makepot: {
      target: {
        options: {
          mainFile: 'bellows-pro.php',
          domainPath: '/languages',
          potFilename: 'bellows.pot',
          // include: [
          //   'path/to/some/file.php'
          // ],
          type: 'wp-plugin', // or `wp-theme`
          potHeaders: {
            poedit: true
          }
        }
      }
    },




    //BUILD
    copy: {
      build_lite: {
        //cwd: 'source',
        src: ['**', '!**/node_modules/**' , '!**/pro/**' , '!**/wp-assets/**', '!bellows-pro.php' , '!.gitignore' , '!package.json' , '!Gruntfile.js' , '!**/*.report.txt' , '!**/_build_pro/**' ],
        dest: '../bellows_build_lite/bellows-accordion-menu/',
        expand: true,
      },
      //copy plugin to plugins/ dir
      install_lite: {
        cwd: '../bellows_build_lite/bellows-accordion-menu/',
        src: '**/*',
        dest: '../../plugins/bellows-accordion-menu',
        expand: true,
      },
      build_pro: {
        //cwd: 'source',
        src: ['**', '!**/node_modules/**' , '!bellows-accordion-menu.php' , '!**/wp-assets/**', '!.gitignore' , '!**/*.report.txt' , '!**/_build_lite/**' ],
        dest: '../bellows_build_pro/bellows-pro/',
        expand: true,
      },
      //copy plugin to plugins/ dir
      install_pro: {
        cwd: '../bellows_build_pro/bellows-pro/',
        src: '**/*',
        dest: '../../plugins/bellows-pro',
        expand: true,
      }
    },

    clean: {
      build_lite: {
        options: {
          force: true
        },
        src: [ '../bellows_build_lite/' ],
      },
      install_lite: {
        options: {
          force: true
        },
        src: [ '../../plugins/bellows-accordion-menu' ]
      },
      build_pro: {
        options: {
          force: true
        },
        src: [ '../bellows_build_pro' ]
      },
      install_pro: {
        options: {
          force: true
        },
        src: [ '../../plugins/bellows-pro' ]
      },
    },

    compress: {
      pro: {
        options: {
          //archive: 'bellows-pro.zip'
          archive: function(){
            var v = grunt.option('ver');
            if( typeof v === 'string' || v instanceof String ){
              if( v.charAt(0) == 'v' ) v = v.substring(1);
            }
            if( v ) v = '-' + v;
            else v = '';
            return '../bellows_build_pro/bellows-pro'+v+'.zip';
            //return 'bellows-pro'+v+'.zip';
          }
        },
        expand: true,
        cwd: '../bellows_build_pro/',
        src: ['**/*'],
        //dest: '/'
      },
      lite: {
        options: {
          //cwd: '../bellows_build'
          archive: function(){
            var v = grunt.option('ver');
            if( typeof v === 'string' || v instanceof String ){
              if( v.charAt(0) == 'v' ) v = v.substring(1);
            }
            if( v ) v = '-' + v;
            else v = '';
            return '../bellows_build_lite/bellows'+v+'.zip';
          }
        },
        expand: true,
        cwd: '../bellows_build_lite/',
        src: ['**/*'],
      }
    },

    wp_deploy: {
        deploy: {
            options: {
                plugin_slug: 'bellows-accordion-menu',
                svn_user: 'sevenspark',
                build_dir: '../bellows_build_lite/bellows-accordion-menu', //relative path to your build directory
                assets_dir: 'wp-assets', //relative path to your assets directory (optional).
                tmp_dir: '../bellows_tmp',
            },
        }
    },

  });


  //LOAD PLUGINS
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-closure-compiler');
  grunt.loadNpmTasks('grunt-wp-i18n');
  grunt.loadNpmTasks('grunt-contrib-watch');


  //MANAGE
  grunt.registerTask('css',     ['less','cssmin'] );
  grunt.registerTask('go',      ['less','cssmin','closure-compiler'] );
  grunt.registerTask('compile', ['closure-compiler'] );
  grunt.registerTask('pot',     ['makepot']);
  grunt.registerTask('default', ['less','cssmin','closure-compiler','makepot'] );




  //BUILD
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-compress');

  //Compile, clean, copy, zip
  grunt.registerTask('build-pro', ['compile' , 'clean:build_pro' , 'copy:build_pro' , 'compress:pro']);
  grunt.registerTask('build-lite', ['compile' , 'clean:build_lite' , 'copy:build_lite' , 'compress:lite']);
  grunt.registerTask('build', ['build-lite' , 'build-pro']);

  //Clean, build, copy over to plugins directory for testing
  grunt.registerTask('test-pro', ['css', 'clean:build_pro' , 'copy:build_pro' , 'clean:install_pro', 'copy:install_pro']);
  grunt.registerTask('test-lite', ['css', 'clean:build_lite' , 'copy:build_lite' , 'clean:install_lite', 'copy:install_lite']);
  grunt.registerTask('test' , ['test-pro' , 'test-lite']);

  //Compile, clean, copy, zip, copy over to plugins directory
  grunt.registerTask('install-pro', ['css', 'compile' , 'clean:build_pro' , 'copy:build_pro' , 'compress:pro' , 'clean:install_pro', 'copy:install_pro']);
  grunt.registerTask('install-lite', ['css', 'compile' , 'clean:build_lite' , 'copy:build_lite' , 'compress:lite' , 'clean:install_lite', 'copy:install_lite']);
  grunt.registerTask('cleanup' , ['clean:build_lite' , 'clean:build_pro']);

  // Get everything ready for release
  grunt.registerTask( 'release', [ 'pot', 'install-pro', 'install-lite' ]);


  //DEPLOY
  //grunt.loadNpmTasks('grunt-wp-deploy');  //TODO - this deploy seems to have put the trunk in a tag....
  grunt.registerTask('deploy' , ['wp_deploy:deploy']);

};



//https://github.com/stephenharris/grunt-wp-deploy
