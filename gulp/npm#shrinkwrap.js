var shell = require('gulp-shell');

module.exports = function() {
  return shell.task('rm -rf ./npm-shrinkwrap.json && npm shrinkwrap');
};
