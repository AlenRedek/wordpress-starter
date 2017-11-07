// Load all the modules from package.json
var gulp 		= require( 'gulp' ),
	plumber 	= require( 'gulp-plumber' ),
	watch 		= require( 'gulp-watch' ),
	livereload 	= require( 'gulp-livereload' ),
	minifycss 	= require( 'gulp-minify-css' ),
	jshint 		= require( 'gulp-jshint' ),
	stylish 	= require( 'jshint-stylish' ),
	uglify 		= require( 'gulp-uglify' ),
	rename 		= require( 'gulp-rename' ),
	notify 		= require( 'gulp-notify' ),
	include 	= require( 'gulp-include' ),
	sass 		= require( 'gulp-sass' );

// Default error handler
var onError = function( err ) {
	console.log( 'An error occured:', err.message );
	this.emit('end');
}

var jsPath = './assets/js';
var cssPath = './assets/css';
var scssPath = './assets/scss';

// Jshint outputs any kind of javascript problems you might have
// Only checks javascript files inside /src directory
gulp.task( 'jshint', function() {
	return gulp.src( `${jsPath}/src/*.js` )
		.pipe( jshint( '.jshintrc' ) )
		.pipe( jshint.reporter( stylish ) )
		.pipe( jshint.reporter( 'fail' ) );
});

// Concatenates all files that it finds in the manifest
// and creates two versions: normal and minified.
// It's dependent on the jshint task to succeed.
gulp.task( 'scripts', ['jshint'], function() {
  return gulp.src( `${jsPath}/manifest.js` )
    .pipe( include() )
    .pipe( rename( { basename: 'scripts' } ) )
    .pipe( gulp.dest( `${jsPath}/vendor/` ) )
    // Normal done, time to create the minified javascript (scripts.min.js)
    .pipe( uglify() )
    .pipe( rename( { suffix: '.min' } ) )
    .pipe( gulp.dest( `${jsPath}/vendor/` ) )
    .pipe( livereload() );
});

// As with javascripts this task creates two files, the regular and
// the minified one. It automatically reloads browser as well.
gulp.task('scss', function() {
	return gulp.src(`${scssPath}/style.scss`)
		.pipe( plumber( { errorHandler: onError } ) )
		.pipe( sass() )
		.pipe( gulp.dest( `${cssPath}/` ) )
		// Normal done, time to do minified (style.min.css)
		// remove the following 3 lines if you don't want it
		.pipe( minifycss() )
		.pipe( rename( { suffix: '.min' } ) )
		.pipe( gulp.dest( `${cssPath}/` ) )
		.pipe( livereload() );
});


// Start the livereload server and watch files for change
gulp.task( 'watch', function() {
	livereload.listen();
	
	// don't listen to whole js folder, it'll create an infinite loop
	gulp.watch( [ `${jsPath}/**/*.js`, `!${jsPath}/vendor/*.js` ], ['scripts'] );
	
	gulp.watch( `${scssPath}/**/*.scss`, ['scss'] );
	
	gulp.watch( './**/*.php' ).on( 'change', function( file ) {
		// reload browser whenever any PHP file changes
		livereload.changed( file );
	});
});


gulp.task( 'default', ['watch'], function() {
	// Does nothing in this task, just triggers the dependent 'watch'
});