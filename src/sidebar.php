<aside <?php if(neuralnet_has_toc()) echo 'class="toc"'; ?>>
	<div class="inner">

		<?php
		/* ------------------------------------------------------------------------------------------------------------------ */
		/* Переменные для шапки сайдбара */
		/* ------------------------------------------------------------------------------------------------------------------ */

		$home_url =         home_url('/');
		$news_url =         get_permalink(get_page_by_path('news'));
		$book_url =         get_permalink(get_page_by_path('book'));
		$articles_url =     get_post_type_archive_link('article');
		$forum_url =        'http://forum.neuralnet.info';
		$registration_url = wp_registration_url();
		$login_url =        wp_login_url(get_permalink());

		?>

        <!-- Дублер главного меню -->
        <div class="main-menu-sidebar">

            <a href="<?php echo $news_url; ?>" title="Новости" class="menu-item <?php if($post->post_name === 'news') echo 'current'; ?>">
                <i class="bullhorn"></i>
            </a>

            <a href="<?php echo $book_url; ?>" title="Учебник" class="menu-item <?php if($post->post_name === 'book') echo 'current'; ?>">
                <i class="book"></i>
            </a>

            <a href="<?php echo $articles_url; ?>" title="Статьи" class="menu-item <?php if(is_post_type_archive('article')) echo 'current'; ?>">
                <i class="newspaper"></i>
            </a>

            <a href="<?php echo $forum_url; ?>" title="Форум" class="menu-item">
                <i class="bubbles"></i>
            </a>

        </div>

        <!-- Дублер поля поиска -->
        <form class="search-form-sidebar" role="search" action="<?php echo $home_url; ?>">
            <input size="10" autocomplete="off" class="search-field" name="s" placeholder="Найти...">
            <button class="search-submit" title="Найти"><i class="search"></i></button>
        </form>

		<?php

		if(is_front_page()) {
			dynamic_sidebar('sidebar-neuralnet-home');
			?>
            <script type="text/javascript" src="//vk.com/js/api/openapi.js?146"></script>

            <!-- VK Widget -->
            <div id="vk_groups"></div>
            <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 3, color2: '333', width: 'auto'}, 152566074);
            </script>
            <section class="ads">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- NeuralNet - Низ сайдбара 1 -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-8094912170389944"
                     data-ad-slot="2154154540"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </section>
			<?php
		} else if(is_singular() && (get_post_type() === 'chapter' || get_post_type() === 'article')) {
			neuralnet_get_toc();
		} else {
			dynamic_sidebar('sidebar-neuralnet-news');
			?>
            <script type="text/javascript" src="//vk.com/js/api/openapi.js?146"></script>

            <!-- VK Widget -->
            <div id="vk_groups"></div>
            <script type="text/javascript">
                VK.Widgets.Group("vk_groups", {mode: 3, color2: '333', width: 'auto'}, 152566074);
            </script>
            <section class="ads">
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- NeuralNet - Низ сайдбара 1 -->
                <ins class="adsbygoogle"
                     style="display:block"
                     data-ad-client="ca-pub-8094912170389944"
                     data-ad-slot="2154154540"
                     data-ad-format="auto"></ins>
                <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            </section>
            <?php
		}

		?>

	</div>
</aside>