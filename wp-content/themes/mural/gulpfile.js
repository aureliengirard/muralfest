'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var postcss      = require('gulp-postcss');
var sourcemaps   = require('gulp-sourcemaps');
var autoprefixer = require('autoprefixer');
var notify = require("gulp-notify");

var converter = require('sass-convert');
var sassdoc = require('sassdoc');

var reportError = function (error) {
	notify({
		title: 'Gulp Task Error',
		message: 'Line '+error.line+"\n"+error.formatted
	}).write(error);
 
	this.emit('end');
}

// Compile sass files in scss folder.
gulp.task('sass', function () {
	return gulp.src('./scss/*.scss')
	.pipe(sourcemaps.init())
	.pipe(sass.sync().on('error', reportError))
	.pipe(postcss([ autoprefixer() ]))
	.pipe(sourcemaps.write('.'))
	.pipe(gulp.dest('.'));
});

// watch for sass file change and compile on save.
gulp.task('sass:watch', ['sass'], function () {
	gulp.watch('./scss/*.scss', ['sass']);
});


// Generate all the documentations.
gulp.task('generatedoc', ['sassdoc']);

// generate SASS documentation
gulp.task('sassdoc', function () {
	var options = {
		dest: 'docs/sass',
		verbose: true,
		display: {
			access: ['public', 'private'],
			alias: true,
			watermark: false,
		},
		theme: "doc-templates/sassdoc/index.js"
	};
  
	return gulp.src(['./scss/*.+(sass|scss)', '../codemstheme/sass/*.+(sass|scss)'])
	.pipe(converter({
		from: 'sass',
		to: 'scss',
	}))
	.pipe(sassdoc(options));
});