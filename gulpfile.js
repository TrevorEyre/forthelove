// gulpfile.js

// Require gulp
var gulp = require('gulp');

// Require other packages
var autoprefixer = require('autoprefixer');
var changed = require('gulp-changed');
var concat = require('gulp-concat');
var cssmin = require('gulp-minify-css');
var ftp = require('vinyl-ftp');
var gutil = require('gulp-util');
var postcss = require('gulp-postcss');
var rename = require('gulp-rename');
var sass = require('gulp-sass');
var uglify = require('gulp-uglify');
var wrap = require('gulp-wrap');

// Require config file for FTP server info
var config = require('./config.json');

// Define source/destination paths for build and deploy tasks
var srcScripts = './src/js/*.js';
var srcStyles = './src/scss/*.scss';
var srcPHP = './src/*.php';
var destScripts = './dist/js/';
var destStyles = './dist/';
var destPHP = './dist/';
var srcDeployScripts = './dist/js/**/*.min.js';
var srcDeployStyles = './dist/*.min.css';
var srcDeployPHP = './dist/*.php';
var srcDeployExtras = [
        './dist/font/*',
        './dist/images/*',
        './dist/screenshot.png'
    ];
var deployDestination = config.deployDestination;

// FTP connection
var conn = ftp.create({
    host: config.serverHost,
    user: config.serverUser,
    password: config.serverPassword,
    parallel: 5,
    log: gutil.log
});

// Deploy function. Expects source files to be grouped in a distribution folder.
// Strips this first folder, to place files in base directory of server
function deploy (inputStream) {
    return inputStream
        .pipe(rename(function (path) {
            var parts = path.dirname.split('\\');
            parts.splice(0, 1);
            path.dirname = parts.join('\\');
        }))
        .pipe(conn.newer(deployDestination))
        .pipe(conn.dest(deployDestination));
}

// Default task
gulp.task('default', ['deployAll', 'watch']);

// Various deploy tasks by file type to reduce FTP transfer to server
gulp.task('deployAll', ['deployScripts', 'deployStyles', 'deployPHP'], function () {
    return deploy(gulp.src(srcDeployExtras, {base: '.', buffer: false}));
});
gulp.task('deployScripts', ['scripts'], function () {
    return deploy(gulp.src(srcDeployScripts, {base: '.', buffer: false}));
});
gulp.task('deployStyles', ['styles'], function () {
    return deploy(gulp.src(srcDeployStyles, {base: '.', buffer: false}));
});
gulp.task('deployPHP', ['php'], function () {
    return deploy(gulp.src(srcDeployPHP, {base: '.', buffer: false}));
});

// Scripts task
gulp.task('scripts', function () {
    return gulp.src(srcScripts)
        .pipe(uglify())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destScripts));
});

// Styles task
gulp.task('styles', function () {
    return gulp.src(srcStyles)
        .pipe(sass())
        .pipe(postcss([
            autoprefixer({
                browsers: ['> 1%']
            })
        ]))
        .pipe(gulp.dest(destStyles))
        .pipe(cssmin())
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest(destStyles));
});

// PHP task. Just pushes changed PHP files to dist folder
gulp.task('php', function () {
    return gulp.src(srcPHP)
        .pipe(changed(destPHP))
        .pipe(gulp.dest(destPHP));
});

// Watch task
gulp.task('watch', function () {
    gulp.watch(srcScripts, ['deployScripts']);
    gulp.watch(srcStyles, ['deployStyles']);
    gulp.watch(srcPHP, ['deployPHP']);
});
