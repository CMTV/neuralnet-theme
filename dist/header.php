<!doctype html>
<html>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" href="<?php echo NEURALNET_IMAGES . 'site-icon.png'; ?>" type="image/png">

	<?php

	/* -------------------------------------------------------------------------------------------------------------- */
	/* Данные страницы */
	/* -------------------------------------------------------------------------------------------------------------- */
	if(is_singular()) :
		$post_type = get_post_type();

		switch ($post_type) {
			case 'article':
				$og_type = 'article';
				break;
			case 'chapter':
				$og_type = 'book';
				break;
			default:
				$og_type = 'website';
				break;
		}

		while(have_posts()) : the_post(); ?>
            <?php
                if(get_post_type() === 'chapter') {
                    $title = neuralnet_get_chapter_number(get_the_ID()) . '. ' . get_the_title() . ' &#8211; ' . get_bloginfo('name');
                } else {
                    $title = get_the_title() . ' &#8211; ' . get_bloginfo('name');
                }
            ?>
            <title><?php echo $title; ?></title>
            <meta name="description" content="<?php echo str_replace('[&hellip;]', '...', wp_strip_all_tags(get_the_excerpt())); ?>">

            <meta property="og:title" content="<?php the_title(); ?>">
            <meta property="og:description" content="<?php echo str_replace('[&hellip;]', '...', wp_strip_all_tags(get_the_excerpt())); ?>">
            <meta property="og:image" content="<?php echo(neuralnet_first_post_img(get_the_ID()) ?: neuralnet_logotype_url()); ?>">
            <meta property="og:type" content="<?php echo $og_type; ?>">
            <meta property="og:url" content="<?php echo urldecode(get_the_permalink()); ?>">

			<?php
    endwhile; rewind_posts();

    else :
        ?>

        <title><?php bloginfo('name'); ?></title>
        <meta name="description" content="<?php bloginfo('description'); ?>">

        <meta property="og:title" content="<?php bloginfo('name'); ?>">
        <meta property="og:description" content="<?php bloginfo('description'); ?>">
        <meta property="og:image" content="<?php echo neuralnet_logotype_url(); ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php bloginfo('url'); ?>">

        <?php
    endif;

	/* -------------------------------------------------------------------------------------------------------------- */
	/* Ключевые слова страницы */
	/* -------------------------------------------------------------------------------------------------------------- */

	$tags = [];

    if(is_singular() && get_the_tags()) {
        $tags = array_merge(['нейросети', 'нейронные сети'], array_map(function(WP_Term $tag) {
            return $tag->name;
        }, get_the_tags()));
    } else {
        $tags = ['нейросети', 'нейронные сети', 'нейронные сети учебник', 'нейронные сети уроки', 'искусственный интеллект', 'нейросети портал', 'нейронные сети портал'];
    }

    ?>

    <meta name="keywords" content="<?php echo neuralnet_implode_commas($tags); ?>">

    <?php

    wp_head();
	?>
</head>
<body <?php body_class(); ?>>
<?php
/* ------------------------------------------------------------------------------------------------------------------ */
/* Переменные для шапки */
/* ------------------------------------------------------------------------------------------------------------------ */

$home_url =         home_url('/');
$news_url =         get_permalink(get_page_by_path('news'));
$book_url =         get_permalink(get_page_by_path('book'));
$articles_url =     get_post_type_archive_link('article');
$forum_url =        'http://forum.neuralnet.info';
$registration_url = wp_registration_url();
$login_url =        wp_login_url(get_permalink());



?>

<header>
	<!-- Декоративная полоска в самом верху сайта -->
	<div class="top-bar"></div>

	<!-- Шапка сайта -->
	<div class="content-area header">

        <!-- Кнопка вызова сайдбара -->
        <div title="Открыть сайдбар" class="menu-item sidebar-button"><i class="menu"></i></div>

        <!-- Логотип -->
        <a class="menu-item logotype-item" href="<?php echo $home_url; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="logotype"></svg>
        </a>

        <!-- Кнопка вызова сайдбара -->
        <div title="Открыть сайдбар" class="menu-item sidebar-button with-search"><i class="menu"></i></div>

        <!-- Главное меню сайта -->
        <nav class="main-menu">

            <a href="<?php echo $news_url; ?>" title="Новости" class="menu-item <?php if($post->post_name === 'news') echo 'current'; ?>">
                <i class="bullhorn"></i>
                <span>Новости</span>
            </a>

            <a href="<?php echo $book_url; ?>" title="Учебник" class="menu-item <?php if($post->post_name === 'book') echo 'current'; ?>">
                <i class="book"></i>
                <span>Учебник</span>
            </a>

            <a href="<?php echo $articles_url; ?>" title="Статьи" class="menu-item <?php if(is_post_type_archive('article')) echo 'current'; ?>">
                <i class="newspaper"></i>
                <span>Статьи</span>
            </a>

            <a href="<?php echo $forum_url; ?>" title="Форум" class="menu-item">
                <i class="bubbles"></i>
                <span>Форум</span>
            </a>

        </nav>

        <!-- Поле поиска -->
        <form class="search-form" role="search" action="<?php echo $home_url; ?>">
            <input size="10" autocomplete="off" class="search-field" name="s" placeholder="Найти...">
            <button class="search-submit" title="Найти"><i class="search"></i></button>
        </form>

        <?php if(!is_user_logged_in()) : ?>

            <!-- Кнопки Регистрации/Входа -->
            <div class="login-registration">
                <a href="<?php echo $registration_url; ?>" class="registration">Регистрация</a>
                <a href="<?php echo $login_url; ?>" class="login">Вход</a>
            </div>

            <!-- Иконка Регистрации/Входа -->
            <a title="Регистрация/вход" href="<?php echo $login_url; ?>" class="menu-item login-registration-item"><i class="key2"></i></a>

        <?php else :
            $current_user = wp_get_current_user();

            $display_name = $current_user->display_name;
            $avatar_url = get_avatar_url($current_user->ID);

	        $profile_url =  get_edit_user_link($current_user->ID);
	        $console_url =  admin_url();
	        $settings_url = $profile_url;
	        $logout_url =   wp_logout_url();
        ?>

            <!-- Данные о пользователе -->
            <div class="profile has-submenu">

                <div class="menu-item profile-item">
                    <span class="username"><?php echo $display_name; ?></span>
                    <?php echo get_avatar($current_user->ID, 40, '', 'Аватара пользователя ' . $current_user->display_name); ?>
                </div>

                <div class="submenu">
                    <div class="inner">
                        <a class="submenu-item" href="<?php echo $profile_url; ?>"><i class="user"></i><span>Мой профиль</span></a>
                        <?php if(in_array('administrator', $current_user->roles) || in_array('editor', $current_user->roles) || in_array('author', $current_user->roles)) : ?>
                            <a class="submenu-item" href="<?php echo $console_url; ?>"><i class="terminal"></i><span>Админ панель</span></a>
                        <?php endif; ?>
                        <a class="submenu-item" href="<?php echo $settings_url; ?>"><i class="cog"></i><span>Настройки</span></a>
                        <a class="submenu-item" href="<?php echo $logout_url; ?>"><i class="switch"></i><span>Выйти</span></a>
                    </div>
                </div>

            </div>

        <?php endif; ?>

    </div>
</header>