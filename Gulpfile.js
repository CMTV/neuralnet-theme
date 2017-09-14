const gulp =            require('gulp');
const run_seq =         require('run-sequence');
const plumber =         require('gulp-plumber');
const concat =          require('gulp-concat');
const uglify_js =       require('gulp-uglify');
const babel =           require('gulp-babel');
const scss_css =        require('gulp-sass');
const autoprefixer =    require('gulp-autoprefixer');
const clean_css =       require('gulp-clean-css');
const clean =           require('gulp-clean');
const replace =         require('gulp-replace');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Перевод из ES6 в ES5, минимизация и оптимизация JavaScript файлов (кроме библиотек) */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('handle-scripts', () => {
    return gulp.src(['src/scripts/**/*.js', '!src/scripts/libs/**/*'])
        .pipe(plumber(function (error) { console.log(error); this.emit('end'); }))
        .pipe(babel({ presets: ['es2015'] }))
        .pipe(replace('$', 'jQuery'))
        .pipe(uglify_js())
        .pipe(gulp.dest('dist/scripts'));
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Объединение, перевод из SASS в CSS, минимизация и оптимизация таблиц стилей */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('handle-styles', () => {
    return gulp.src(['src/styles/**/*.scss', '!src/styles/**/_*.scss', '!src/styles/separate/**/*'])
        .pipe(plumber(function (error) { console.log(error); this.emit('end'); }))
        .pipe(concat('style.min.css', {newLine: ''}))
        .pipe(scss_css())
        .pipe(clean_css())
        .pipe(autoprefixer())
        .pipe(gulp.dest('dist/styles'));
});

gulp.task('handle-separate-styles', () => {
    return gulp.src('src/styles/separate/**/*.scss')
        .pipe(plumber(function (error) { console.log(error); this.emit('end'); }))
        .pipe(scss_css())
        .pipe(clean_css())
        .pipe(autoprefixer())
        .pipe(gulp.dest('dist/styles'));
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Перенос JS библиотек */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('move-js-libs', () => {
    return gulp.src('src/scripts/libs/**/*')
        .pipe(plumber(function (error) { console.log(error); this.emit('end'); }))
        .pipe(gulp.dest('dist/scripts/libs'))
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Перенос шрифтов */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('move-fonts', () => {
    return gulp.src('src/styles/fonts/**/*')
        .pipe(plumber(function (error) { console.log(error); this.emit('end'); }))
        .pipe(gulp.dest('dist/styles/fonts'))
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Перемещение всех файлов (кроме скриптов и таблиц стилей) */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('move-theme-files', () => {
    return gulp.src(['src/**/*',
        '!src/scripts/**/*',
        '!src/styles/**/*',
        '!src/tinymce/**/*'])
        .pipe(plumber(function (error) { console.log(error); this.emit('end'); }))
        .pipe(gulp.dest('dist'));
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Удаление всех файлов из папки с готовыми файлами */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('clean-dist', () => {
    return gulp.src('dist', {read: false})
        .pipe(clean({force: true}));
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Удаление всех файлов из папки локального сервера */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('clean-dist-server', () => {
    return gulp.src('C:\\OSPanel\\domains\\neuralnet.info.loc\\wp-content\\themes\\neuralnet', {read: false})
        .pipe(clean({force: true}));
});

gulp.task('clean', ['clean-dist', 'clean-dist-server']);

/* ------------------------------------------------------------------------------------------------------------------ */
/* Перенос всех файлов темы в директорию локального сервера */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('move-to-destination', () => {
    return gulp.src('dist/**/*')
        .pipe(gulp.dest(
            'C:\\OSPanel\\domains\\neuralnet.info.loc\\wp-content\\themes\\neuralnet'
        ));
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Слежение за изменениями */
/* ------------------------------------------------------------------------------------------------------------------ */
gulp.task('watch', () => {
    gulp.watch('src/**/*', ['build-site']);
});

/* ================================================================================================================== */
/* Общие таски */
/* ================================================================================================================== */

gulp.task('build-site', (callback) => {
    run_seq(
        'clean-dist', 'clean-dist-server',
        ['handle-scripts', 'handle-styles', 'handle-separate-styles', 'move-theme-files'],
        ['move-js-libs', 'move-fonts'],
        'move-to-destination',
        callback
    );
});