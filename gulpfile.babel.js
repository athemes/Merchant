/**
 * Gulpfile.
 * Gulp with WordPress.
 */

/**
 * Load WPGulp Configuration.
 */
const config = require('./wpgulp.config.js');

/**
 * Load Plugins.
 */
const gulp  = require('gulp');
const newer = require('gulp-newer');

// CSS related plugins.
const nodesass     = require('node-sass')
const sass         = require('gulp-sass')(nodesass);
const minifycss    = require('gulp-uglifycss');
const autoprefixer = require('gulp-autoprefixer');
const mmq          = require('gulp-merge-media-queries');
const rtlcss       = require('gulp-rtlcss');

// JS related plugins.
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const babel  = require('gulp-babel');

// Utility related plugins.
const rename      = require('gulp-rename');
const lineec      = require('gulp-line-ending-corrector');
const filter      = require('gulp-filter');
const notify      = require('gulp-notify');
const browserSync = require('browser-sync').create();
const wpPot       = require('gulp-wp-pot');
const sort        = require('gulp-sort');
const cache       = require('gulp-cache');
const remember    = require('gulp-remember');
const plumber     = require('gulp-plumber');
const beep        = require('beepbeep');
const zip         = require('gulp-zip');

/**
 * Custom Error Handler.
 */
const errorHandler = r => {
	notify.onError('\n\n❌  ===> ERROR: <%= error.message %>\n')(r);
	beep();
};
 
/**
 * Helper function to allow browser reload with Gulp 4.
 */
const reload = done => {
	browserSync.reload();
	done();
};

/**
* Task: `browser-sync`.
*/
const browsersync = done => {
	browserSync.init({
		proxy: config.projectURL,
		open: config.browserAutoOpen,
		injectChanges: config.injectChanges,
		watchEvents: ['change', 'add', 'unlink', 'addDir', 'unlinkDir']
	});
	done();
};

/**
 * Task: `metaboxStyles`.
 */
gulp.task('metaboxStyles', () => {
	return gulp
		.src(config.metaboxCssSRC, {allowEmpty: true})
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'expanded',
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoprefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(gulp.dest(config.metaboxCssDestination))
		.pipe(filter('**/*.css'))
		.pipe(mmq({log: true}))
		.pipe(browserSync.stream())
		.pipe(
			notify({
				message: '\n\n✅  ===> Metabox CSS Expanded — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `metaboxStylesMin`.
 */
gulp.task('metaboxStylesMin', () => {
	return gulp
	  .src(config.metaboxCssSRC, {allowEmpty: true})
	  .pipe(plumber(errorHandler))
	  .pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'compressed',
				precision: config.precision
			})
		)
	  .on('error', sass.logError)
	  .pipe(autoprefixer(config.BROWSERS_LIST))
	  .pipe(rename({suffix: '.min'}))
	  .pipe(lineec())
	  .pipe(gulp.dest(config.metaboxCssDestination))
	  .pipe(filter('**/*.css'))
	  .pipe(mmq({log: true}))
	  .pipe(browserSync.stream())
	  .pipe(
			notify({
				message: '\n\n✅  ===> Metabox CSS Minified — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `styles`.
 */
gulp.task('styles', () => {
	return gulp
		.src(config.styleSRC, {allowEmpty: true})
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'expanded',
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoprefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(gulp.dest(config.styleDestination))
		.pipe(filter('**/*.css'))
		.pipe(mmq({log: true}))
		.pipe(browserSync.stream())
		.pipe(
			notify({
				message: '\n\n✅  ===> Styles Expanded — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `stylesMin`.
 */
gulp.task('stylesMin', () => {
	return gulp
	  .src(config.styleSRC, {allowEmpty: true})
	  .pipe(plumber(errorHandler))
	  .pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'compressed',
				precision: config.precision
			})
		)
	  .on('error', sass.logError)
	  .pipe(autoprefixer(config.BROWSERS_LIST))
	  .pipe(rename({suffix: '.min'}))
	  .pipe(lineec())
	  .pipe(gulp.dest(config.styleDestination))
	  .pipe(filter('**/*.css'))
	  .pipe(mmq({log: true}))
	  .pipe(browserSync.stream())
	  .pipe(
			notify({
				message: '\n\n✅  ===> Styles Minified — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `adminStyles`.
 */
gulp.task('adminStyles', () => {
	return gulp
		.src(config.adminStyleSRC, {allowEmpty: true})
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'expanded',
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoprefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(gulp.dest(config.adminStyleDestination))
		.pipe(filter('**/*.css'))
		.pipe(mmq({log: true}))
		.pipe(browserSync.stream())
		.pipe(
			notify({
				message: '\n\n✅  ===> Admin Styles Expanded — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `adminStylesMin`.
 */
gulp.task('adminStylesMin', () => {
	return gulp
	  .src(config.adminStyleSRC, {allowEmpty: true})
	  .pipe(plumber(errorHandler))
	  .pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'compressed',
				precision: config.precision
			})
		)
	  .on('error', sass.logError)
	  .pipe(autoprefixer(config.BROWSERS_LIST))
	  .pipe(rename({suffix: '.min'}))
	  .pipe(lineec())
	  .pipe(gulp.dest(config.adminStyleDestination))
	  .pipe(filter('**/*.css'))
	  .pipe(mmq({log: true}))
	  .pipe(browserSync.stream())
	  .pipe(
			notify({
				message: '\n\n✅  ===> Admin Styles Minified — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `gridStyles`.
 */
gulp.task('gridStyles', () => {
	return gulp
		.src(config.gridStyleSRC, {allowEmpty: true})
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'expanded',
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoprefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(gulp.dest(config.gridStyleDestination))
		.pipe(filter('**/*.css'))
		.pipe(mmq({log: true}))
		.pipe(browserSync.stream())
		.pipe(
			notify({
				message: '\n\n✅  ===> Grid Styles Expanded — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `gridStylesMin`.
 */
gulp.task('gridStylesMin', () => {
	return gulp
	  .src(config.gridStyleSRC, {allowEmpty: true})
	  .pipe(plumber(errorHandler))
	  .pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'compressed',
				precision: config.precision
			})
		)
	  .on('error', sass.logError)
	  .pipe(autoprefixer(config.BROWSERS_LIST))
	  .pipe(rename({suffix: '.min'}))
	  .pipe(lineec())
	  .pipe(gulp.dest(config.gridStyleDestination))
	  .pipe(filter('**/*.css'))
	  .pipe(mmq({log: true}))
	  .pipe(browserSync.stream())
	  .pipe(
			notify({
				message: '\n\n✅  ===> Grid Styles Minified — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `carouselStyles`.
 */
gulp.task('carouselStyles', () => {
	return gulp
		.src(config.carouselStyleSRC, {allowEmpty: true})
		.pipe(plumber(errorHandler))
		.pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'expanded',
				precision: config.precision
			})
		)
		.on('error', sass.logError)
		.pipe(autoprefixer(config.BROWSERS_LIST))
		.pipe(lineec())
		.pipe(gulp.dest(config.carouselStyleDestination))
		.pipe(filter('**/*.css'))
		.pipe(mmq({log: true}))
		.pipe(browserSync.stream())
		.pipe(
			notify({
				message: '\n\n✅  ===> Carousel Styles Expanded — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `carouselStylesMin`.
 */
gulp.task('carouselStylesMin', () => {
	return gulp
	  .src(config.carouselStyleSRC, {allowEmpty: true})
	  .pipe(plumber(errorHandler))
	  .pipe(
			sass({
				errLogToConsole: config.errLogToConsole,
				outputStyle: 'compressed',
				precision: config.precision
			})
		)
	  .on('error', sass.logError)
	  .pipe(autoprefixer(config.BROWSERS_LIST))
	  .pipe(rename({suffix: '.min'}))
	  .pipe(lineec())
	  .pipe(gulp.dest(config.carouselStyleDestination))
	  .pipe(filter('**/*.css'))
	  .pipe(mmq({log: true}))
	  .pipe(browserSync.stream())
	  .pipe(
			notify({
				message: '\n\n✅  ===> Carousel Styles Minified — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `scripts`.
 */
gulp.task('scripts', () => {
	return gulp
		.src(config.scriptSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.scriptDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
				}
					]
				]
			})
		)
		.pipe(remember(config.scriptSRC))
		.pipe(concat(config.scriptFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.scriptDestination))
		.pipe(
			rename({
				basename: config.scriptFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.scriptDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Scripts — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `adminScripts`.
 */
gulp.task('adminScripts', () => {
	return gulp
		.src(config.adminScriptSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.adminScriptDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
						}
					]
				]
			})
		)
		.pipe(remember(config.adminScriptSRC))
		.pipe(concat(config.adminScriptFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.adminScriptDestination))
		.pipe(
			rename({
				basename: config.adminScriptFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.adminScriptDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Admin Scripts — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `scrollDirectionScript`.
 */
gulp.task('scrollDirectionScript', () => {
	return gulp
		.src(config.scrollDirectionScriptSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.scrollDirectionScriptDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
				}
					]
				]
			})
		)
		.pipe(remember(config.scrollDirectionScriptSRC))
		.pipe(concat(config.scrollDirectionScriptFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.scrollDirectionScriptDestination))
		.pipe(
			rename({
				basename: config.scrollDirectionScriptFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.scrollDirectionScriptDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Scroll Direction Script — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `toggleClassScript`.
 */
gulp.task('toggleClassScript', () => {
	return gulp
		.src(config.toggleClassScriptSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.toggleClassScriptDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
				}
					]
				]
			})
		)
		.pipe(remember(config.toggleClassScriptSRC))
		.pipe(concat(config.toggleClassScriptFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.toggleClassScriptDestination))
		.pipe(
			rename({
				basename: config.toggleClassScriptFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.toggleClassScriptDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Toggle Class Script — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `customAddToCartButtonScript`.
 */
gulp.task('customAddToCartButtonScript', () => {
	return gulp
		.src(config.customAddToCartButtonScriptSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.customAddToCartButtonScriptDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
				}
					]
				]
			})
		)
		.pipe(remember(config.customAddToCartButtonScriptSRC))
		.pipe(concat(config.customAddToCartButtonScriptFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.customAddToCartButtonScriptDestination))
		.pipe(
			rename({
				basename: config.customAddToCartButtonScriptFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.customAddToCartButtonScriptDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Custom Add To Cart Button Script — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `carouselScript`.
 */
gulp.task('carouselScript', () => {
	return gulp
		.src(config.carouselScriptSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.carouselScriptDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
				}
					]
				]
			})
		)
		.pipe(remember(config.carouselScriptSRC))
		.pipe(concat(config.carouselScriptFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.carouselScriptDestination))
		.pipe(
			rename({
				basename: config.carouselScriptFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.carouselScriptDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Carousel Script — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `metaboxScripts`.
 */
gulp.task('metaboxScripts', () => {
	return gulp
		.src(config.metaboxJsSRC, {since: gulp.lastRun('scripts')})
		// .pipe(newer(config.metaboxJsDestination))
		.pipe(plumber(errorHandler))
		.pipe(
			babel({
				presets: [
					[
						'@babel/preset-env',
						{
							targets: {browsers: config.BROWSERS_LIST}
				}
					]
				]
			})
		)
		.pipe(remember(config.metaboxJsSRC))
		.pipe(concat(config.metaboxJsFile + '.js'))
		.pipe(lineec())
		.pipe(gulp.dest(config.metaboxJsDestination))
		.pipe(
			rename({
				basename: config.metaboxJsFile,
				suffix: '.min'
			})
		)
		.pipe(uglify())
		.pipe(lineec())
		.pipe(gulp.dest(config.metaboxJsDestination))
		.pipe(
			notify({
				message: '\n\n✅  ===> Metabox JS — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `clearCache`.
 */
gulp.task('clearCache', function (done) {
	return cache.clearAll(done);
});

/**
 * Task: `translate`.
 */
gulp.task('translate', () => {
	return gulp
		.src(config.watchPhp)
		.pipe(sort())
		.pipe(
			wpPot({
				domain: config.textDomain,
				package: config.packageName,
				bugReport: config.bugReport,
				lastTranslator: config.lastTranslator,
				team: config.team
			})
		)
		.pipe(gulp.dest(config.translationDestination + '/' + config.translationFile))
		.pipe(
			notify({
				message: '\n\n✅  ===> Translate — completed!\n',
				onLast: true
			})
		);
});

/**
 * Task: `zip`.
 */
gulp.task('zip', () => {
	const src = [...config.zipIncludeGlob, ...config.zipIgnoreGlob];
	return gulp.src(src).pipe(zip(config.zipName)).pipe(gulp.dest(config.zipDestination));
});

/**
 * Watch Tasks.
 */
gulp.task(
	'default',
	gulp.parallel(
		'styles',
		'stylesMin',
		'metaboxStyles',
		'metaboxStylesMin',
		'adminStyles',
		'adminStylesMin',
		'gridStyles',
		'gridStylesMin',
		'carouselStyles',
		'carouselStylesMin',
		'scripts',
		'adminScripts',
		'scrollDirectionScript',
		'toggleClassScript',
		'customAddToCartButtonScript',
		'carouselScript',
		'metaboxScripts',
		browsersync, () => {

		// Global
		gulp.watch(config.watchPhp, reload);

		// Frontend CSS
		gulp.watch(config.watchStyles, gulp.parallel('styles'));
		gulp.watch(config.watchStyles, gulp.parallel('stylesMin'));
		gulp.watch(config.watchStyles, gulp.parallel('metaboxStyles'));
		gulp.watch(config.watchStyles, gulp.parallel('metaboxStylesMin'));
		gulp.watch(config.watchStyles, gulp.parallel('gridStyles'));
		gulp.watch(config.watchStyles, gulp.parallel('gridStylesMin'));
		gulp.watch(config.watchStyles, gulp.parallel('carouselStyles'));
		gulp.watch(config.watchStyles, gulp.parallel('carouselStylesMin'));

		// Backend CSS
		gulp.watch(config.watchStyles, gulp.parallel('adminStyles'));
		gulp.watch(config.watchStyles, gulp.parallel('adminStylesMin'));

		// Admin JS
		gulp.watch(config.watchScripts, gulp.series('metaboxScripts', reload));

		// Frontend JS
		gulp.watch(config.watchScripts, gulp.series('scripts', reload));
		gulp.watch(config.watchScripts, gulp.series('scrollDirectionScript', reload));
		gulp.watch(config.watchScripts, gulp.series('toggleClassScript', reload));
		gulp.watch(config.watchScripts, gulp.series('customAddToCartButtonScript', reload));
		gulp.watch(config.watchScripts, gulp.series('carouselScript', reload));

		// Backend JS
		gulp.watch(config.watchScripts, gulp.series('adminScripts', reload));

	})
);
