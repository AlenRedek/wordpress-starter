// Defining requirements
var gulp 			= require( 'gulp' ),
	plumber 		= require( 'gulp-plumber' ),
	sass 			= require( 'gulp-sass' ),
	sourcemaps 		= require( 'gulp-sourcemaps' ),
	cleanCSS 		= require( 'gulp-clean-css' ),
	gulpSequence 	= require( 'gulp-sequence' ),
	autoprefixer 	= require( 'gulp-autoprefixer' ),
	rename 			= require( 'gulp-rename' ),
	concat 			= require( 'gulp-concat' ),
	uglify 			= require( 'gulp-uglify' ),
	watch 			= require( 'gulp-watch' ),
	browserSync 	= require( 'browser-sync' ).create();

// Configuration file to keep your code DRY
var config = require( './gulpconfig.json' );
var paths = config.paths;

// Default error handler
var onError = function( err ) {
	console.log( 'An error occured:', err.message );
	this.emit('end');
}

// Run:
// gulp watch
// Starts watcher. Watcher runs gulp sass task on changes
gulp.task( 'watch', function() {
    gulp.watch( paths.scss + '/**/*.scss', ['styles'] );
    gulp.watch( paths.src + '/js/**/*.js', ['scripts'] );
});

// Run:
// gulp watch-bs
// Starts watcher with browser-sync. Browser-sync reloads page automatically on your browser
gulp.task( 'watch-bs', ['browser-sync', 'watch'], function() { 
} );

// Run:
// gulp browser-sync
// Starts browser-sync task for starting the server.
gulp.task( 'browser-sync', function() {
    browserSync.init( config.browserSyncOptions );
} );

// Run:
// gulp sass
// Compiles SCSS files in CSS
gulp.task( 'sass', function() {
    var stream = gulp.src( paths.scss + '/*.scss' )
        .pipe( plumber( { errorHandler: onError } ) )
        .pipe( sass( { errLogToConsole: true } ) )
        .pipe( autoprefixer( 'last 2 versions' ) )
        .pipe( gulp.dest( paths.css ) );
    return stream;
});

gulp.task( 'minifycss', function() {
  return gulp.src( paths.css + '/theme.css' )
    .pipe( sourcemaps.init( { loadMaps: true } ) )
    .pipe( cleanCSS( { compatibility: '*' } ) )
    .pipe( plumber( { errorHandler: onError } ) )
    .pipe( rename( { suffix: '.min' } ) )
	.pipe( sourcemaps.write( './' ) )
    .pipe( gulp.dest( paths.css ) );
});

gulp.task( 'styles', function( callback ) {
    gulpSequence( 'sass', 'minifycss' )( callback );
} );

// Run: 
// gulp scripts. 
// Uglifies and concat all JS files into one
gulp.task( 'scripts', function() {
	var scripts = paths.src + '/js/**/*.js';
	
	gulp.src( scripts )
	  .pipe( concat( 'scripts.min.js' ) )
	  .pipe( uglify() )
	  .pipe( gulp.dest( paths.js ) );
    
	gulp.src( scripts )
	  .pipe( concat( 'scripts.js' ) )
	  .pipe( gulp.dest( paths.js ) );
});

// Run:
// gulp copy-src.
// Copy all needed dependency assets files from node_modules assets to themes src folder. Run this task after node install or node update
gulp.task( 'copy-src', function() {

	for (var prop in config.vendors) {
		gulp.src( config.vendors[prop].src )
		.pipe( gulp.dest( config.vendors[prop].dest ) );
	}

});
