<?php
/*
YARPP Template: Neural Net
Author: CMTV
*/
?>
<?php if (have_posts()):?>
<div class="related-posts">

    <h2 class="related-title">Похожее</h2>
    <div class="sep strong"></div>

	<?php while (have_posts()) : the_post(); ?>

        <div class="related-post">
            <i class="<?php echo neuralnet_get_the_post_icon(); ?>"></i>
            <a href="<?php the_permalink(); ?>" class="inner">
                <div><?php the_title(); ?></div>
				<?php echo neuralnet_get_date(get_the_date() . ' ' . get_the_time(), 'div', 'publish-date'); ?>
            </a>
        </div>

        <?php if($wp_query->current_post +1 !== $wp_query->post_count) : ?>
            <div class="sep"></div>
		<?php endif; ?>

	<?php endwhile; ?>

</div>
<?php endif; ?>