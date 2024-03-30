const gulp = require('gulp');
const htmlMin = require('gulp-htmlmin');
const prettify = require('gulp-prettify');

const replace = require('gulp-replace');

const plumber = require('gulp-plumber');
const rename = require('gulp-rename');
const ejs = require("gulp-ejs");
const sass = require('gulp-sass')(require('sass'));
// const sass = require("gulp-sass");
const sourcemaps = require('gulp-sourcemaps');
const autoprefixer = require('gulp-autoprefixer');

const cssNano = require('gulp-cssnano');

const imageMin = require("gulp-imagemin");
const pngQuant = require('imagemin-pngquant');
const mozJpeg = require('imagemin-mozjpeg');
const svgo = require('gulp-svgo');
const webp = require('gulp-webp'); //webpに変換

const babel = require('gulp-babel');
const terser = require('gulp-terser');

const fs = require('fs');

//パス設定
const paths = {
    ejs: {
        dist: './',
    },
    styles: {
        dist: './css/',
    },
    scripts: {
        src: ['./src/js/**/*.js', '!./src/js/**/vendors/*.js'], //外部のライブラリファイルはコンパイルしない
        copy:'./src/js/**/vendors/**/*',
        dist: './js/',
    },
    images: {
        src: './src/img/**/*.{jpg,jpeg,png,gif,svg}',
        srcWebp: './src/img/**/*.{jpg,jpeg,png}',
        dist: './img/',
        distWebp: './img/webp/',
    },
    fonts: {
        src: './fonts/**/*.{off,ttf,woff,woff2}',
        dist: './fonts/',
    },
};



gulp.task("sass", function(done) {

    gulp.src("scss/**/*scss")
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'expanded'
        }))
        .pipe(autoprefixer({browsers: ['last 3 versions', 'ie >= 10']}))
        
        .pipe(gulp.dest(paths.styles.dist, {
            // ソースマップを出力する場合のパス
            sourcemaps: './map',
        }));
    done();

});


gulp.task('watch', function (done) {
  
    gulp.watch('scss/**/*.scss', gulp.series('sass'));
  
    done();
});