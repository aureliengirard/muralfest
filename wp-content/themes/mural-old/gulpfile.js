'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var postcss      = require('gulp-postcss');
var sourcemaps   = require('gulp-sourcemaps');
var autoprefixer = require('autoprefixer');
var notify = require("gulp-notify");
var pump = require('pump');

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
gulp.task('sass', function (err) {
	pump([
		gulp.src([
			'./scss/*.scss'
		]),
		sourcemaps.init(),
		sass.sync().on('error', reportError),
		postcss([ autoprefixer() ]),
		sourcemaps.write('.'),
		gulp.dest('.'),
	], err);
});

// watch for sass file change and compile on save.
gulp.task('sass:watch', gulp.series(['sass'], function () {
	gulp.watch('./scss/*.scss', gulp.series(['sass']));
}));


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
	
	pump([
		gulp.src(['./scss/*.+(sass|scss)', '../codemstheme/sass/*.+(sass|scss)']),
		converter({
			from: 'sass',
			to: 'scss',
		}),
		sassdoc(options)
	]);
});

// Generate all the documentations.
gulp.task('generatedoc', gulp.series(['sassdoc']));