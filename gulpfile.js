// Sass configuration
const gulp = require('gulp');
const gulpSass = require('gulp-sass');
const gulpConcat = require('gulp-concat');
const sourcemaps = require('gulp-sourcemaps');
const del = require('del');
const postcss = require('gulp-postcss');
const postcssPresetEnv = require('postcss-preset-env');
const browserSync = require('browser-sync');

const server = browserSync.create();
const THEME_BASE = `${__dirname}/wp-content/themes/sceraTheatre`;

function reload(done) {
	server.reload();
	done();
}
function serve(done) {
	server.init({
		logSnippet: false,
		server: false,
		open: false,
		reloadDelay: 100,
		reloadDebounce: 100,
	});
	done();
}

function clean() {
	return del('main.css');
}
exports.clean = clean;

function scss() {
	return gulp
		.src(`${THEME_BASE}/scss/main.scss`)
		.pipe(sourcemaps.init())
		.pipe(gulpSass().on('error', gulpSass.logError))
		.pipe(
			postcss([
				postcssPresetEnv({
					stage: 2,
					browsers: 'last 2 versions',
				}),
			]),
		)
		.pipe(sourcemaps.write('.'))
		.pipe(
			gulp.dest(`${THEME_BASE}/css`, {
				overwrite: true,
			}),
		)
		.pipe(server ? server.reload({ stream: true }) : '');
}
exports.scss = scss;


const watch = () => {
	gulp.watch(`${THEME_BASE}/scss/*.scss`, scss);
	gulp.watch([`${THEME_BASE}/*.php`, `${THEME_BASE}/js/*.js`], reload);
};

exports.default = gulp.series(clean, scss, serve, watch);
exports.build = gulp.series(clean, scss);
