var gulp = require('gulp');
var requireAll = require('require-all');
var argv = require('yargs').argv;

// Default Configuration
var baseAssetsPath = 'web/assets/';
var basePath = 'src/Resources/';

var config = {
  paths: {
    base: basePath,
    bundle: basePath + 'ui/',
    dist: baseAssetsPath,
    scripts: baseAssetsPath + 'js/',
    styles: baseAssetsPath + 'css/'
  },

  production: argv.production,

  argv: argv,
};

// Load tasks
var tasks = requireAll(__dirname + '/gulp');
for (var taskName in tasks) {
  if (tasks.hasOwnProperty(taskName)) {
    var id = taskName.replace('#', ':');
    gulp.task(id, tasks[taskName](config, gulp));
  }
}
