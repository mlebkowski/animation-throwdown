var shell = require('gulp-shell');

module.exports = function() {
  return shell.task('app/console doctrine:migrations:migrate --no-interaction');
};
