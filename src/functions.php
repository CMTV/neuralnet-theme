<?php

define('NEURALNET_STYLES', get_template_directory_uri() . '/styles/');
define('NEURALNET_SCRIPTS', get_template_directory_uri() . '/scripts/');
define('NEURALNET_LIBS', NEURALNET_SCRIPTS . 'libs/');
define('NEURALNET_IMAGES', get_template_directory_uri() . '/images/');

require_once 'includes/utils.php';

require_once 'includes/roles-capabilities.php';
require_once 'includes/custom-post-types.php';
require_once 'includes/book.php';
require_once 'includes/tinymce.php';
require_once 'includes/toc.php';
require_once 'includes/login-form.php';

/* ------------------------------------------------------------------------------------------------------------------ */
/* Базовая настройка */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_init() {
	/* Что тема поддерживает/не поддерживает */
	add_theme_support('html5');
	add_theme_support('widgets');
	remove_theme_support('menus');

	/* Страницы "Новости" и "Оглавление учебника" */
	if(!neuralnet_page_exists('news')) {
		wp_insert_post([
			'post_type' =>      'page',
			'post_title' =>     'Новости',
			'post_name' =>      'news',
			'post_status' =>    'publish'
		]);
	}
	if(!neuralnet_page_exists('book')) {
		wp_insert_post([
			'post_type' =>      'page',
			'post_title' =>     'Учебник',
			'post_name' =>      'book',
			'post_status' =>    'publish'
		]);
	}

	/* Сокрытие админ панели */
	show_admin_bar(false);
}
add_action('after_setup_theme', 'neuralnet_init');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Регистрация сайдбаров */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_sidebars() {
	/* Сайдбар для главной страницы */
	register_sidebar([
		'name' =>           'Главная страница',
		'id' =>             'sidebar-neuralnet-home',
		'before_widget' =>  '<section id="%1$s" class="widget %2$s">',
		'after_widget' =>   '</section>',
		'before_title' =>   '<h2 class="widget-title">',
		'after_title' =>    '</h2>'
	]);

	/* Сайдбар всех остальных постов, кроме статей и глав */
	register_sidebar([
		'name' =>           'Все, кроме статей и глав',
		'id' =>             'sidebar-neuralnet-news',
		'before_widget' =>  '<section id="%1$s" class="widget %2$s">',
		'after_widget' =>   '</section>',
		'before_title' =>   '<h2 class="widget-title">',
		'after_title' =>    '</h2>'
	]);
}
add_action('widgets_init', 'neuralnet_sidebars');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Виджеты */
/* ------------------------------------------------------------------------------------------------------------------ */

require_once 'widgets/NEURALNET_Latest_PAC_Widget.php';
require_once 'widgets/NEURALNET_Latest_Categories_Widget.php';

/* ------------------------------------------------------------------------------------------------------------------ */
/* Подключение стилей и скриптов */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_enqueue_scripts() {
	/* Основная таблица стилей */
	wp_enqueue_style('main', NEURALNET_STYLES . 'style.min.css');

	/* jQuery */
	wp_enqueue_script('jquery');

	/* Snap.svg */
	wp_enqueue_script('snap-svg', NEURALNET_LIBS . 'snap.svg-min.js');

	/* Hammer */
	wp_enqueue_script('hammer', NEURALNET_LIBS . 'hammer.min.js');

	/* Magnific Popup */
	wp_enqueue_style('magnific-popup', NEURALNET_LIBS . 'magnific-popup/magnific-popup.css');
	wp_enqueue_script('magnific-popup', NEURALNET_LIBS . 'magnific-popup/jquery.magnific-popup.min.js', ['jquery']);
	wp_enqueue_script('magnific-popup-config', NEURALNET_SCRIPTS . 'magnific-popup-config.js', ['magnific-popup']);

	/* Правильная ширина при наличии полосы прокрутки */
	wp_enqueue_script('mq.genie', NEURALNET_LIBS . 'mq.genie.min.js');

	/* Контроллер шапки сайта */
	wp_enqueue_script('header-controller', NEURALNET_SCRIPTS . 'header-controller.js');

	/* Контроллер логотипа */
	wp_enqueue_script('logotype-controller', NEURALNET_SCRIPTS . 'logotype-controller.js');

	/* Контроллер открытия/закрытия сайдбара с помощью свайпа */
	wp_enqueue_script('swipe-sidebar-controller', NEURALNET_SCRIPTS . 'swipe-sidebar-controller.js');

	/* Контроллер якорей */
	wp_enqueue_script('anchors-controller', NEURALNET_SCRIPTS . 'anchors-controller.js');
}
add_action('wp_enqueue_scripts', 'neuralnet_enqueue_scripts');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Иконка админ-панели */
/* ------------------------------------------------------------------------------------------------------------------ */

add_action('admin_head', function () {
	?> <link rel="shortcut icon" href="<?php echo NEURALNET_IMAGES . 'site-icon-admin.png'; ?>" type="image/png"> <?php
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Удаление ссылки "Читать далее" */
/* ------------------------------------------------------------------------------------------------------------------ */

add_filter('the_content_more_link', function() {
	return '';
});

/* ------------------------------------------------------------------------------------------------------------------ */
/* Настройка пагинации */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_navigation_markup_template($template, $class) {
	return '
    <nav class="navigation %1$s" role="navigation"> 
		<div class="nav-links">%3$s</div>
	</nav> 
    ';
}
add_filter('navigation_markup_template', 'neuralnet_navigation_markup_template', 10, 2);

/* ------------------------------------------------------------------------------------------------------------------ */
/* Добавление статей и глав в Главный Цикл */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_add_post_types_to_loop(WP_Query $query) {
	if (($query->is_feed() || $query->is_main_query()) && $query->is_front_page()) {
		$query->set('post_type', ['post', 'article', 'chapter']);
	}

	if($query->is_author() || $query->is_tag() || $query->is_category()) {
		$query->set('post_type', ['post', 'article', 'chapter']);
    }
}
add_action('pre_get_posts', 'neuralnet_add_post_types_to_loop');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Добавление дополнительных классов тегу <body> */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_chapter_article_body_classes($classes) {
    if(is_singular()) {
        $post_type = get_post_type();

        if($post_type === 'article' || $post_type === 'chapter') {
            array_push($classes, 'single-' . $post_type);
        }
    }

    return $classes;
}
add_filter('body_class', 'neuralnet_chapter_article_body_classes');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Корректное отображение картинок с подписями */
/* ------------------------------------------------------------------------------------------------------------------ */

function fixed_img_caption_shortcode($attr, $content = null) {
	if (!isset( $attr['caption'])) {
		if (preg_match( '#((?:<a [^>]+>s*)?<img [^>]+>(?:s*</a>)?)(.*)#is', $content, $matches)) {
			$content = $matches[1];
			$attr['caption'] = trim($matches[2]);
		}
	}
	$output = apply_filters( 'img_caption_shortcode', '', $attr, $content );
	if ($output != '')
		return $output;
	extract(shortcode_atts(array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => ''
	), $attr));
	if (1 > (int) $width || empty($caption))
		return $content;
	if ($id) $id = 'id="' . esc_attr($id) . '" ';
	return '<div ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >'
	       . do_shortcode( $content ) . '<p class="wp-caption-text">' . $caption . '</p></div>';
}
add_shortcode( 'wp_caption', 'fixed_img_caption_shortcode' );
add_shortcode( 'caption', 'fixed_img_caption_shortcode' );