var webpack = require("webpack");
var gutil = require('gulp-util');
var notifier = require('node-notifier');

module.exports = function(config) {
  return function(cb) {
    var plugins = [
      new webpack.ResolverPlugin(
        new webpack.ResolverPlugin.DirectoryDescriptionFilePlugin('package.json', ['browser', 'main'])
      ),
      new webpack.ResolverPlugin(
        new webpack.ResolverPlugin.DirectoryDescriptionFilePlugin('bower.json', ['main'])
      ),
      new webpack.ProvidePlugin({
        $: "jquery",
        jQuery: "jquery",
        "window.jQuery": "jquery"
      })
    ];

    if (config.production) {
      plugins.push(new webpack.optimize.UglifyJsPlugin({compress: { warnings: false }}));
    }

    var compiler = webpack({
      entry: "./" + config.paths.bundle + "js/index.js",
      output: {
        path: config.paths.scripts,
        filename: "app.js"
      },
      module: {
        preLoaders: [
          {
            test: /\.js$/,
            exclude: /(node_modules|bower_components)/,
            loader: "jshint-loader"
          }
        ],
        loaders: [
          {
            test: /\.js$/,
            exclude: /(node_modules|bower_components)/,
            loader: 'babel?presets[]=es2015'
          },
          { test: /\.html$/, loader: 'html' }
        ]
      },
      plugins: plugins,
      devtool: !config.argv.production ? '#source-map' : undefined
    });

    var handler = function(err, stats) {
      if (err) {
        throw new gutil.PluginError("webpack", err);
      }
      var jsonStats = stats.toJson();

      if (jsonStats.errors.length > 0) {
        notifier.notify({
          'title': '[webpack]',
          'message': jsonStats.errors[0]
        });
      }

      if (!config.production || jsonStats.errors.length > 0) {
        gutil.log("[webpack]", stats.toString({
          colors: true,
          chunks: false
        }));

        if (config.production && jsonStats.errors.length > 0) {
          throw new gutil.PluginError("webpack", err);
        }
      }

      if (!config.watch) {
        cb();
      }
    };

    if (config.watch) {
      compiler.watch({ aggregateTimeout: 300 }, handler);
      cb();
    } else {
      compiler.run(handler);
    }
  };
};
