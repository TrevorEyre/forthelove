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
var srcScripts = './src/js/**/*.js';
var srcStyles = './src/scss/*.scss';
var srcPHP = './src/php/*.php';
var destScripts = './dist/js/';
var destStyles = './dist/';
var destPHP = './dist/';
var srcDeployScripts = './dist/js/**/*.min.js';
var srcDeployStyles = './dist/*.css';
var srcDeployPHP = './dist/*.php';
var srcDeployExtras = [
        './res/**/*'
    ];
var deployDestination = config.deployDestination;
var srcDeployProd = ['./dist/**/*'].concat(srcDeployExtras);
var deployDestinationProd = config.deployDestinationProd;

// FTP connection
var conn = ftp.create({
    host: config.serverHost,
    user: config.serverUser,
    password: config.serverPassword,
    parallel: 5,
    log: gutil.log
});

// Deploy function. If source files are located in 'dist' or 'res' folders, function strips first
// folder, to put files in base directory. If source files are in a different base folder, the
// original file structure is preserved.
function deploy (destination, inputStream) {
    return inputStream
        .pipe(rename(function (path) {
            var parts = path.dirname.split('\\');
            if (parts[0] == 'dist' || parts[0] == 'res') {
                parts.splice(0, 1);
            }
            path.dirname = parts.join('\\');
        }))
        .pipe(conn.newer(destination))
        .pipe(conn.dest(destination));
}

// Default task
gulp.task('default', ['deployAll', 'watch']);

// Deploy all files to production server
gulp.task('deployProduction', ['scripts', 'styles', 'php'], function () {
    return deploy(deployDestinationProd, gulp.src(srcDeployProd, {base: '.', buffer: false}));
});

// Deploy all files to development server
gulp.task('deployAll', ['scripts', 'styles', 'php'], function () {
    return deploy(deployDestination, gulp.src(srcDeployProd, {base: '.', buffer: false}));
});

// Various deploy tasks by file type to reduce FTP transfer to server
gulp.task('deployScripts', ['scripts'], function () {
    return deploy(deployDestination, gulp.src(srcDeployScripts, {base: '.', buffer: false}));
});
gulp.task('deployStyles', ['styles'], function () {
    return deploy(deployDestination, gulp.src(srcDeployStyles, {base: '.', buffer: false}));
});
gulp.task('deployPHP', ['php'], function () {
    return deploy(deployDestination, gulp.src(srcDeployPHP, {base: '.', buffer: false}));
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
        .pipe(cssmin())
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
