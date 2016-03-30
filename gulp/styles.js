var sourcemaps = require('gulp-sourcemaps');
var sass = require('gulp-sass');
var autoprefixer = require('gulp-autoprefixer');
var notifier = require('node-notifier');
var cleanCss = require('gulp-clean-css');
var gulpIf = require('gulp-if');

module.exports = function(config, gulp) {
  return function() {
    return gulp.src(config.paths.bundle + 'scss/**/*.scss')
      .pipe(gulpIf(!config.production, sourcemaps.init()))
      .pipe(
          sass({
            style: 'compressed',
            errLogToConsole: true
          })
      )
      .on('error', function (err) {
          console.log('Sass error:', err.message);
          notifier.notify({
            'title': 'SASS ERROR',
            'message': err.message
          });

          this.emit('end');
      })
      .pipe(autoprefixer('last 3 version'))
      .pipe(gulpIf(!config.production, sourcemaps.write('./')))
      .pipe(gulpIf(config.production, cleanCss()))
      .pipe(gulp.dest(config.paths.styles));
  };
};
