<?php

query_posts(['post_type' => 'post']);

get_header();

?>

	<section class="content">
		<div class="inner">

			<?php

			if(have_posts()) {

				while(have_posts()) : the_post();

					/* Категории поста */
					if(($categories_length = count($categories = neuralnet_get_categories())) > 0) {
						$has_categories = true;

						for($i = 0; $i < $categories_length; $i++) {
							$category = $categories[$i];

							$categories[$i] = '<a class="category" href="' . get_category_link($category) . '">' . $category->name . '</a>';
						}

						$categories = '<div class="categories">' . neuralnet_implode_commas($categories) . '</div>';
					} else $has_categories = false;

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

                        <h2 class="title">
                            <a class="text" href="<?php the_permalink(); ?>"><?php echo $title; ?></a>
                        </h2>

						<?php if($has_categories) echo $categories; ?>

						<div class="content"><?php the_content(); ?></div>

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