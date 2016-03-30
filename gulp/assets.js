module.exports = function(config, gulp) {
  return function() {
    return gulp.src([
      config.paths.bundle + '/!(js|scss|svg)/**/*',
      '!' + config.paths.bundle + '/img/sprite/*'
    ], {
      "dot": true
    })
    .pipe(gulp.dest(config.paths.dist));
  };
};
