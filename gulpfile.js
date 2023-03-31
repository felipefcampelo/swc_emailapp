const gulp = require('gulp');
const copy = require('gulp-copy');

function copyAssets() {
  return gulp.src([
    'node_modules/bootstrap/dist/**/*',
    'node_modules/jquery/dist/**/*',
    'node_modules/popper.js/dist/**/*',
    'node_modules/quill/dist/**/*',
  ])
    .pipe(copy('public/assets/'));
}

exports.default = copyAssets;
