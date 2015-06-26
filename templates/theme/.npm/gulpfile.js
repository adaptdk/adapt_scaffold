var gulp       = require('gulp'),
    sass       = require('gulp-sass'),
    stylestats = require('gulp-stylestats'),
    svg2png    = require('gulp-svg2png'),
    jshint     = require('gulp-jshint'),
    plumber    = require('gulp-plumber');

/*
* Task: sass
* sass compile
*/
gulp.task('sass', function() {
    gulp.src('sass/**/*.scss')
        .pipe(plumber())
        .pipe(sass({ sync: true }))
        .pipe(plumber.stop())
        .pipe(gulp.dest('css'))
});

/*
* Task: stats
* Usefull sass statistics
*/
gulp.task('cssstats', function () {
  gulp.src('css/*.css')
    .pipe(stylestats());
});

/*
* Task: jshint
* JS Hint Error checking JS
*/
gulp.task('lint', function() {
    return gulp.src('js/*.js')
        .pipe(jshint())
        .pipe(jshint.reporter('jshint-stylish'));
});

/*
* Task: png
* Converts all SVGs to PNGs
*/
gulp.task('png', function() {
    var pngSrc = 'images/*.svg';
    pngDst = 'images';

    gulp.src(pngSrc)
        .pipe(svg2png())
        .pipe(gulp.dest(pngDst));
});

/*
* Task: watch
* Monitors file changes
*/
gulp.task('watch', function() {
    gulp.watch('sass/**/*.scss', ['sass']);
    gulp.watch('js/*.js', ['jslint']);
    gulp.watch('css/*.css', ['stats']);
});

/*
* Registered tasks
*/

gulp.task('jshint', ['jshint']);
gulp.task('png', ['png']);
gulp.task('stats', ['cssstats']);

/*
* Default task
*/

gulp.task('default', ['lint', 'png', 'sass', 'watch']);