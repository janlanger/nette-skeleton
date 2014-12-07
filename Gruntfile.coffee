module.exports = (grunt) ->
  grunt.initConfig
    useminPrepare:
      html: [
        'app/templates/**/@*.latte'
      ]
      options:
        dest: '.'


    netteBasePath:
      basePath: 'www'
      options:
        removeFromPath: [
          'app\\templates\\',
          'app/templates/'
        ]
    concat:
      options:
        separator: '\n',
        process: (src, filepath) ->
          cssPatt = new RegExp("^www(/.*/).*.css$")
          #filter out everithing except css files
          file = cssPatt.exec(filepath)

          if file
            urlPatt = /url\([\'\"]?([^\'\"\:\)]*)[\'\"]?\)/g
            console.log "In file: " + filepath

            #replace every url(...) with its absolute path
            return src.replace urlPatt, (match, p1) ->
              if p1.charAt(0) != '/'
                console.log " * " + match + " -> " + "url('" + file[1] + p1 + "')"
                "url('" + file[1] + p1 + "')"
              else
                console.log " * " + match + " - absolute, not replaced"
                "url('" + p1 + "')"

          src
    coffee:
      compile:
        expand: true
        flatten: true
        src: "www/assets/coffee/*.coffee"
        dest: "www/assets/coffee/compiled"
        ext: ".js"
    less:
      dev:
        options:
          rootpath: "/assets/less/"
          relativeUrls: false
        flatten: true
        expand: true
        cwd: "www/assets/less"
        src: ["screen.less"]
        dest: "www/webtemp/css/"
        ext: ".css"
    autoprefixer:
      options:
        browsers: ['> 1%', 'last 2 versions', 'Firefox ESR', 'Opera 12.1', 'ie >=7']
      dev:
        src: "www/webtemp/css/*.css"
    watch:
      options:
        livereload: true
      coffee:
        files: 'www/assets/coffee/*.coffee'
        tasks: 'coffee:compile'
      less:
        files: 'www/assets/less/*.less'
        tasks: ['less', 'autoprefixer:dev']
      misc:
        files: ['www/assets/css/*.css', 'www/assets/js/*.js', "app/**/*.latte"]


  # These plugins provide necessary tasks.
  grunt.loadNpmTasks 'grunt-contrib-less'
  grunt.loadNpmTasks 'grunt-contrib-coffee'
  grunt.loadNpmTasks 'grunt-contrib-watch'
  grunt.loadNpmTasks 'grunt-contrib-concat'
  grunt.loadNpmTasks 'grunt-contrib-uglify'
  grunt.loadNpmTasks 'grunt-contrib-cssmin'
  grunt.loadNpmTasks 'grunt-usemin'
  grunt.loadNpmTasks 'grunt-nette-basepath'
  grunt.loadNpmTasks 'grunt-autoprefixer'

  # Default task.
  grunt.registerTask 'build', [
    'default'
    'useminPrepare'
    'netteBasePath'
    'concat'

    'cssmin'
    'uglify'
  ]


  grunt.registerTask 'default', [
     'less', 'coffee:compile', 'autoprefixer:dev'
  ]
