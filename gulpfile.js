'use strict';

/*

---------------------------------------
User settings
---------------------------------------

List all your JavaScript file in `userScripts` to define
their order of concatenation.

List all the JavaScript plugin file you are using in `pluginScripts`
to define their order of concatenation.

Managing more than one LESS/CSS is made with @imports in LESS.

To enable automatic reloading on .js and .less files compilation,
as well as other niceties from [browser sync](https://www.browsersync.io/)
write your local dev url in the localDevUrl variable.

*/

var pluginsScripts = [
  'bower_components/jquery/dist/jquery.js',
  //'bower_components/bootstrap/dist/js/bootstrap.js'
];
var userScripts = [
  'assets/js/main.js'
];

var localDevUrl = 'http://localhost:8888/';


/*

---------------------------------------
Gulp definition
---------------------------------------

By default you don’t have to configure anything to
make Gulp work.

gulp `default` task are “lint”, “css”, “script-plugins”, “scripts”.
gulp `dev-watch` task execute “lint” and “script-plugins” from `userScripts`
and “less” from `assets/less/*`.

*/

// Include gulp
var gulp = require('gulp');



// Include Our Plugins
var autoprefixer = require('gulp-autoprefixer');
var postcss = require('gulp-postcss');
var $ = require('gulp-load-plugins')();
var lost = require('lost');
//var connect = require('gulp-connect-php');
var browserSync  = require('browser-sync').create();
var concat       = require('gulp-concat');
var jshint       = require('gulp-jshint');
var sass         = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var nano         = require('gulp-cssnano');
var plumber      = require('gulp-plumber');
var rename       = require('gulp-rename');
var uglify       = require('gulp-uglify');

var paths = {
  cssSource: 'assets/css/',
  cssDestination: 'assets/production/'
};

// Compile our SASS
gulp.task('sass', function() {
  return gulp.src('assets/scss/**/*.{scss,sass}')
    .pipe(plumber({
        errorHandler: function (err) {
          console.log(err);
          this.emit('end');
        }
    }))
    // Initializes sourcemaps
    .pipe(sourcemaps.init())
    .pipe(sass({
      //includePaths: ['app/bower_components', 'app/bower_components/foundation/scss'],
      errLogToConsole: true
      }))
    .pipe(postcss([
         lost()
    ]))
    // Writes sourcemaps into the CSS file
    .pipe(autoprefixer({
      browsers: ['last 2 versions'],
      cascade: false
    }))
    .pipe(sourcemaps.write())
    .pipe($.size({
      title: 'styles'
    }))
    .pipe(gulp.dest('assets/css'))
    .pipe(browserSync.stream());
});



// Prefix & Minify CSS
gulp.task('css', ['sass'], function (done) {
  return gulp.src([
      'assets/css/*.css',
    ])
    .pipe(nano({discardComments: {removeAll: true}}))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest('assets/production/css'));
});

// Copy Web Fonts To Dist
gulp.task('fonts', function() {
  return gulp.src('assets/fonts/*')
    .pipe($.size({
      title: 'fonts'
    }))
    .pipe(gulp.dest('assets/production/css/fonts'));
});


// Lint Task
gulp.task('lint', function() {
  return gulp.src( userScripts)
    .pipe(jshint())
    .pipe(jshint.reporter('jshint-stylish'));
});



// Concatenate JS plugin
gulp.task('script-plugins', function() {
  return gulp.src(pluginsScripts)
    .pipe(concat('plugins.js'))
    .pipe(gulp.dest('assets/js'))
    .pipe(browserSync.stream());
});



// Concatenate JS plugin with user scripts and minify them.
gulp.task('scripts', ['script-plugins'], function (done) {
  return gulp.src(['assets/js/plugins.js'].concat(userScripts))
    .pipe(concat('all.js'))
    .pipe(gulp.dest('assets/production'))
    .pipe(rename('all.min.js'))
    .pipe(uglify())
    .pipe(gulp.dest('assets/production'));
});



// Live reload sync on every screen connect to localhost
gulp.task('init-live-reload', function() {
  browserSync.init({
    proxy: localDevUrl,
    files: ['!site/accounts/', 'site/**/*.php', 'content/**/*.txt'],
  });
});



// Watch Files For Changes
gulp.task('dev-watch', function() {
  gulp.watch( userScripts, ['lint', 'script-plugins']);
  gulp.watch('assets/scss/**/*.{scss,sass}', ['sass'])
});



// Watch Files For Changes with live reload sync on every screen connect to localhost.
gulp.task('dev-watch-sync', ['init-live-reload', 'dev-watch']);



// Production Task
gulp.task('prod', ['lint', 'sass', 'css', 'script-plugins', 'scripts', 'fonts']);



// Default Task
gulp.task('default', ['prod']);
