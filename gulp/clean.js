var del = require('del');

module.exports = function(config) {
  return function(done) {
    del(config.paths.dist + '*')
    .then(function () {
      done();
    });
  };
};
