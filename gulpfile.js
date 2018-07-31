// All the things will live in here
var $ = {

    // Config
    config: {
        source: '_templates/assets',
        dest: '_templates/assets',
        templates: '_templates',
        url: 'boilerplate.dev',
        boilerplate: null // Leave this as null, it's used by YAML plugin
    },

    // Modules
    gulp:           require('gulp'),
    sass:           require('gulp-sass'),
    minifycss:      require('gulp-cssnano'),
    uglify:         require('gulp-uglify'),
    rename:         require('gulp-rename'),
    concat:         require('gulp-concat'),
    notify:         require('gulp-notify'),
    plumber:        require('gulp-plumber'),
    del:            require('del'),
    autoprefixer:   require('gulp-autoprefixer'),
    if:             require('gulp-if'),
    fs:             require('fs'),
    yaml:           require('js-yaml')

};

// Load Config
$.gulp.task('load-config', function() {

    // Try to load config file
    try {

        // Get file using fs module
        let file = $.fs.readFileSync('./.config.yml', 'utf8');

        // Parse YAML
        $.config.boilerplate = $.yaml.safeLoad(file);

        console.log('Loaded config');

    } catch (e) {
        console.log(e);
    }

});

// Default task
$.gulp.task('default', function() {
    $.gulp.start('compile-sass', 'compile-javascript');
});

// Style Tasks
$.gulp.task('compile-sass', function() {

    // Load SASS Files
    return $.gulp.src( $.config.source + '/sass/*.scss' )

    // Compile SASS
    .pipe( $.sass().on('error', $.sass.logError) )
    .pipe( $.plumber() )

    // Autoprefix and Minify
    .pipe( $.gulp.dest($.config.dest + '/css') )
    .pipe( $.autoprefixer({ cascade: false }) )
    .pipe( $.minifycss() )

    // Save it and update the browser
    .pipe( $.gulp.dest($.config.dest + '/css') )
    .pipe( $.notify({ message: 'Styles task complete' }) );

});

// Javascript Tasks
$.gulp.task('compile-javascript', function() {

    // Find Javascript files
    return $.gulp.src([
        $.config.source + '/js/source/*.js',
        $.config.source + '/js/source/vendor/*.js'],
        { base: $.config.source + '/js/source' }
    )

    // Catch Errors
    .pipe( $.plumber() )

    // Combine files
    .pipe( $.concat('bundle.js') )
    .pipe( $.gulp.dest( $.config.dest + '/js' ) )

    // Minify
    .pipe( $.uglify())
    .pipe( $.gulp.dest( $.config.dest + '/js' ) )

    // Update the browser
    .pipe( $.notify({ message: 'Scripts task complete' }) );

});

// BrowserSync Task
$.gulp.task('browser-sync', function() {

    // Setup BrowserSync
    $.browsersync.init({
        proxy: {
            target: $.config.url,
            ws: true
        }
    });

});

// Watch
$.gulp.task('watch', function() {

    // Run BrowserSync
    //$.gulp.start('browser-sync');

    // Watch .scss files
    $.gulp.watch([
        $.config.source + '/sass/*.scss',
        $.config.source + '/sass/**/*.scss',
        $.config.source + '/sass/**/**/*.scss'], ['compile-sass']);

    // Watch .js files
    $.gulp.watch([
        $.config.source + '/js/source/*.js',
        $.config.source + '/js/source/**/*.js'], ['compile-javascript']);

});

// Minify JS
$.gulp.task('js-minify', function() {

    // Get JS Files
    return $.gulp.src([$.config.source + '/js/*.js', '!'+ $.config.source + '/js/*.min.js', '!'+ $.config.source + '/js/main.js'])

    // Check for errors
    .pipe( $.plumber() )

    // Minify
    .pipe( $.uglify() )

    // Add .min to the filename
    .pipe( $.rename(function(path) {
        path.basename += '.min';
    }) )

    // Save!
    .pipe( $.gulp.dest($.config.dest + '/js') )

});
