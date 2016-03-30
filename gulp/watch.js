var runSequence = require('run-sequence');
var BrowserSync = require('browser-sync');

module.exports = function(config, gulp) {
  return function(done) {
    config.watch = true;

    return runSequence('default', function() {
      // sprites
      gulp.watch([config.paths.bundle + 'img/sprite/**/*.png'], ['sprites']);

      // styles
      gulp.watch(config.paths.bundle + 'scss/**/*.scss', ['styles']);

      // assets
      gulp.watch([
        config.paths.bundle + '/!(js|scss)/**/*', '!' + config.paths.bundle + '/img/sprite/*'
      ], ['assets']);

      BrowserSync.create().init({
          proxy: config.argv.proxy || "syzygy.loc",
          browser: config.argv.test ? ["google chrome", "firefox", "safari"] : 'default',
          files: [
            config.paths.base + "views/**/*.twig",
            config.paths.styles + "*.css",
            config.paths.scripts +  "*.js"
          ],
          startPath: config.argv.startWith || null
      });

      done();
    });
  };
};
