var spritesmith = require('gulp.spritesmith');
var fs = require('fs');
var path = require('path');
var merge = require('merge-stream');
var concat = require('gulp-concat');
var gutil = require('gulp-util');

module.exports = function(config, gulp) {
  return function() {
    var folders;

    try {
      folders = fs.readdirSync(config.paths.bundle + 'img/sprite').filter(function(file) {
        return fs.statSync(path.join(config.paths.bundle + 'img/sprite', file)).isDirectory();
      });
    } catch(e) {
      gutil.log("[sprites]", e.toString());
      return;
    }

    var globalSprite = gulp.src(config.paths.bundle + 'img/sprite/*.png')
      .pipe(spritesmith({
          padding: 10,
          retinaSrcFilter: [config.paths.bundle + 'img/sprite/*-2x.png'],
          imgName: 'sprite.png',
          retinaImgName: 'sprite-2x.png',
          imgPath: '../img/sprite.png',
          retinaImgPath:  '../img/sprite-2x.png',
          cssName: '_sprite.scss'
        })
      );

    var cssStream = globalSprite.css;

    var sprites = folders.map(function(folder) {
      var sprite = gulp.src(config.paths.bundle + 'img/sprite/' + folder + '/*.png')
        .pipe(spritesmith({
            padding: 10,
            retinaSrcFilter: [config.paths.bundle + 'img/sprite/' + folder + '/*-2x.png'],
            imgName: 'sprite-' + folder + '.png',
            retinaImgName: 'sprite-' + folder + '-2x.png',
            imgPath: '../img/sprite-' + folder + '.png',
            retinaImgPath:  '../img/sprite-' + folder + '-2x.png',
            cssName: '_sprite-' + folder + '.scss',
            cssVarMap: function (sprite) {
              sprite.name = folder + '-' + sprite.name;
            },
            cssSpritesheetName: folder + '-spritesheet',
            cssRetinaSpritesheetName: folder + '-retina-spritesheet',
            cssRetinaGroupsName: folder + '-retina-groups'
          })
        );

      cssStream = merge(cssStream, sprite.css);

      return sprite.img.pipe(gulp.dest(config.paths.dist + 'img/'));
    });

    sprites.push(cssStream
      .pipe(concat('_sprite.scss'))
      .pipe(gulp.dest(config.paths.bundle + 'scss/_partials/')));

    sprites.push(globalSprite.img.pipe(gulp.dest(config.paths.dist + 'img/')));

    return merge(sprites);
  };
};
