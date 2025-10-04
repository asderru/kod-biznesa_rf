import path from 'path';
import { fileURLToPath } from 'url';
import gulp from 'gulp';
import browserSync from 'browser-sync';
import cleanCSS from 'gulp-clean-css';
import concat from 'gulp-concat';
import plumber from 'gulp-plumber';
import rigger from 'gulp-rigger';
import sourcemaps from 'gulp-sourcemaps';
import uglify from 'gulp-uglify';
import * as sass from 'sass';
import gulpSass from 'gulp-sass';

const sassCompiler = gulpSass(sass);
const { series, parallel, watch } = gulp;
const server = browserSync.create();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const paths = {
    baseDir: path.join(__dirname, 'web'),
    src: path.join(__dirname, 'src'),
    web: path.join(__dirname, 'web'),
};

export function serve(done) {
    server.init({
        server: { baseDir: paths.baseDir },
        tunnel: false,
    });
    done();
}

export function html() {
    return gulp
        .src(path.join(paths.src, 'index.html'))
        .pipe(rigger())
        .pipe(gulp.dest(paths.web))
        .pipe(server.stream());
}

async function images2webp() {
    const imagemin = (await import('imagemin')).default;
    const imageminWebp = (await import('imagemin-webp')).default;

    await imagemin([path.join(paths.src, 'img/*.{jpg,jpeg,png}')], {
        destination: path.join(paths.web, 'img'),
        plugins: [imageminWebp({ quality: 90 })],
    });

    console.log('Images optimized');
}

export async function styles() {
    const autoprefixer = (await import('gulp-autoprefixer')).default;
    const cleanCSS = (await import('gulp-clean-css')).default;
    const gulpSassInstance = gulpSass(sass);

    return gulp
        .src(path.join(paths.src, 'scss/main.scss'))
        .pipe(plumber())
        .pipe(gulpSassInstance({ outputStyle: 'expanded' }))
        .pipe(concat('main.css'))
        .pipe(sourcemaps.init())
        .pipe(
            autoprefixer({
                overrideBrowserslist: ['last 2 versions'],
                cascade: false,
            })
        )
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(path.join(paths.web, 'css')))
        .pipe(cleanCSS())
        .pipe(concat('main.min.css'))
        .pipe(gulp.dest(path.join(paths.web, 'css')))
        .pipe(server.stream());
}

export function scripts() {
    return gulp
        .src(path.join(paths.src, 'js/main.js'))
        .pipe(plumber())
        .pipe(rigger())
        .pipe(sourcemaps.init())
        .pipe(uglify())
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(path.join(paths.web, 'js')))
        .pipe(server.stream());
}

export function watchFiles() {
    server.init({
        server: { baseDir: paths.baseDir },
        tunnel: false,
    });
    gulp.watch(path.join(paths.src, 'views/**/*'), html);
    gulp.watch(path.join(paths.src, 'views/img/**/*'), images2webp);
    gulp.watch(path.join(paths.src, 'scss/**/*'), styles);
    gulp.watch(path.join(paths.src, 'js/down/*'), scripts);
    gulp.watch(path.join(paths.web, 'index.html'), server.reload);
    gulp.watch(path.join(paths.web, 'main.css'), server.reload);
}

export async function clean() {
    const del = (await import('del')).deleteAsync;
    return del([
        path.join(paths.web, 'css/main.css'),
        path.join(paths.web, 'css/main.min.css'),
        path.join(paths.web, 'css/main.css.map'),
        path.join(paths.web, 'js*'),
    ]);
}

export const build = series(clean, parallel(styles, html, images2webp, scripts));
export default series(build, watchFiles);
