var gulpIf = require('gulp-if');
var hashSrc = require("gulp-hash-src");

module.exports = function(config, gulp) {
  return function() {
    return gulp.src(config.paths.styles + '*.css')
      .pipe(gulpIf(config.production, hashSrc({
        build_dir: config.paths.styles,
        src_path: config.paths.styles,
        query_name: '',
        hash_len: 7
      })))
      .pipe(gulp.dest(config.paths.styles));
  };
};
