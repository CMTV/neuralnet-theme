<?php

get_header();

?>

<section class="content">
    <div class="inner">

        <?php

        if(have_posts()) {

            while(have_posts()) : the_post();
                $post_icon = neuralnet_get_the_post_icon();

                /* Категории поста */
                if(($categories_length = count($categories = neuralnet_get_categories())) > 0) {
                    $has_categories = true;

                    for($i = 0; $i < $categories_length; $i++) {
                        $category = $categories[$i];

                        $categories[$i] = '<a class="category" href="' . get_category_link($category) . '">' . $category->name . '</a>';
                    }

                    $categories = '<div class="categories">' . neuralnet_implode_commas($categories) . '</div>';
                } else $has_categories = false;

                /* Метки поста */
                if(($tags_length = count($tags = wp_get_post_tags(get_the_ID()))) > 0) {
                    $tags = get_the_tags();

                    $has_tags = true;

                    for($i = 0; $i < $tags_length; $i++) {
                        $tag = $tags[$i];

                        $tags[$i] = '<a class="tag" href="' . get_tag_link($tag) . '">' . $tag->name . '</a>';
                    }

                    $tags = '<div class="tags"><i title="Теги" class="price-tag"></i>' . neuralnet_implode_commas($tags) . '</div>';
                } else $has_tags = false;

                ?>

                <article class="post-item <?php echo get_post_type(); ?>" id="post-<?php the_ID(); ?>">

	                <?php echo neuralnet_get_date(get_the_date() . ' ' . get_the_time(), 'div', 'publish-date'); ?>

	                <?php
	                if(get_post_type() === 'chapter') {
		                $title = 'Глава ' . neuralnet_get_chapter_number(get_the_ID()) . '. ' . get_the_title();
	                } else {
		                $title = get_the_title();
	                }
	                ?>

	                <?php if(is_singular()): ?>
                        <h1 class="title singular">
                            <i title="<?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>" class="type-icon <?php echo $post_icon; ?>"></i>
                            <span class="text"><?php echo $title; ?></span>
                        </h1>
	                <?php else: ?>
                        <h2 class="title">
                            <i title="<?php echo get_post_type_object(get_post_type())->labels->singular_name; ?>" class="type-icon <?php echo $post_icon; ?>"></i>
                            <a class="text" href="<?php the_permalink(); ?>"><?php echo $title; ?></a>
                        </h2>
	                <?php endif; ?>

	                <?php if($has_categories) echo $categories; ?>

                    <div class="content"><?php the_content(); ?></div>

	                <?php

                    if(is_single()):
                        if($has_tags) :
                            echo $tags;
                        endif;

                        ?>

                        <!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-5996ab0a659fd9a3"></script>
                        <!-- Go to www.addthis.com/dashboard to customize your tools --> <div class="addthis_inline_share_toolbox"><div class="sep"></div><a href="<?php echo get_permalink(get_page_by_path('donate')); ?>" target="_blank" class="donation-button-footer"><i class="thumbs-o-up"></i><span>Поддержать портал!</span></a></div>

                        <?php

                        if(neuralnet_is_chapter()) :

                            $chapter_navigation = neuralnet_previous_next_chapters();

                            ?>

                            <div class="previous-next-chapters">

                                <?php if($chapter_navigation['previous']) : ?>
                                <a title="Предыдущая глава" href="<?php echo $chapter_navigation['previous']; ?>" class="previous">
                                    <i class="arrow-left"></i>
                                    <span>Предыдущая глава</span>
                                </a>
                                <?php endif; ?>

                                <div class="space"></div>

	                            <?php if($chapter_navigation['next']) : ?>
                                <a title="Следующая глава" href="<?php echo $chapter_navigation['next']; ?>" class="next">
                                    <span>Следующая глава</span>
                                    <i class="arrow-right"></i>
                                </a>
	                            <?php endif; ?>

                            </div>

                            <?php

                        endif;

                        neuralnet_author_block();

                        if(function_exists('related_posts')) {
                            related_posts();
                        }

	                    if (comments_open() || get_comments_number()) :
		                    comments_template();
	                    endif;

                        ?>

                        <section class="footer-ad">
                            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                            <!-- Нейронные сети. Перед комментариями -->
                            <ins class="adsbygoogle"
                                 style="display:block"
                                 data-ad-client="ca-pub-8094912170389944"
                                 data-ad-slot="5629072493"
                                 data-ad-format="auto"></ins>
                            <script>
                                (adsbygoogle = window.adsbygoogle || []).push({});
                            </script>
                        </section>

                        <?php

	                endif;

	                ?>

	                <?php if(!is_singular()): ?>
                        <div class="post-footer">

                            <a href="<?php echo get_the_permalink() . '#more-1'; ?>" class="more-link"><span>Читать далее</span><i class="arrow_forward"></i></a>

                            <div class="post-info">
                                <a title="Автор публикации" href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" class="author-info">

                                    <?php
                                    $user = get_userdata(get_the_author_meta('ID'));
                                    ?>

                                    <img class="avatar" width="35" height="35" alt="Аватара пользователя <?php echo $user->display_name; ?>" src="<?php echo get_avatar_url($user->ID); ?>">
                                    <div class="display-name"><?php echo $user->display_name; ?></div>
                                </a>
                                <a href="<?php echo get_comments_link(get_the_ID()); ?>" title="К комментариям" class="comments"><i class="mode_comment"></i><span><?php comments_number(0, 1, '%'); ?></span></a>
                            </div>

                        </div>
	                <?php endif; ?>

                </article>

                <?php

            endwhile;

	        the_posts_pagination([
		        'prev_text' => '<i class="arrow-left"></i>',
		        'next_text' => '<i class="arrow-right"></i>'
	        ]);

        } else {
            ?> <div class="no-content">Здесь ничего нет</div> <?php
        }

        ?>

    </div>
</section>

<?php

get_sidebar();

get_footer();