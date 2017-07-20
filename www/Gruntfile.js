// http://www.cnblogs.com/wymbk/p/5766064.html
module.exports = function(grunt) {

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    sass: {
      output: {
        options: {
          style: 'compressed'
        },
        files: {
          './css/styles.css': './scss/styles.scss'
        }
      }
    },
    watch: {
      sass: {
        files: [
          './scss/**/*.scss',
          './scss/**/**/*.scss',
          './scss/**/**/**/*.scss'
        ],
        tasks: ['sass']
      }
    },
  });

  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['sass', 'watch']);
};