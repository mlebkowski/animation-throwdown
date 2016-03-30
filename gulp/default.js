var runSequence = require('run-sequence');

module.exports = function() {
  return function(done) {
    return runSequence(
      ['clean'],
      ['scripts', 'sprites'],
      ['styles', 'assets'],
      ['styles:hash'],
      done
    );
  };
};
