<?php

/**
 * Виджет, отображающий последнюю новость, статью и главу
 */
class NEURALNET_Latest_PAC_Widget extends WP_Widget {
	public function __construct() {
		$widget = [
			'classname' =>      'latest-widget',
			'description' =>    'Виджет, отображающий последнюю новость, статью и главу'
		];

		parent::__construct('neuralnet-latest-widget', 'Что нового?', $widget);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];
		if(!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}

		?><div class="widget-sep strong"></div><?php

		/* Получение последней новости */
		if($latest_post = get_posts(['numberposts' => 1, 'post_type' => 'post'])) {
			$latest_post = $latest_post[0];

			?>
			<a href="<?php the_permalink($latest_post->ID); ?>" title="К последней новости..." class="latest-news">
				<i class="bullhorn icon"></i>
				<div class="info">
					<h3><?php echo $latest_post->post_title; ?></h3>
					<div class="publish-date">
						<i class="clock"></i><?php echo neuralnet_get_date($latest_post->post_date, 'span'); ?>
					</div>
				</div>
			</a>
			<?php
		} else {
			?>
			<div class="latest-news empty">Новостей нет</div>
			<?php
		}

		?><div class="widget-sep"></div><?php

		/* Получение последней статьи */
		if($latest_article = get_posts(['numberposts' => 1, 'post_type' => 'article'])) {
			$latest_article = $latest_article[0];

			?>
			<a href="<?php the_permalink($latest_article->ID); ?>" title="К последней статье..." class="latest-article">
				<i class="newspaper icon"></i>
				<div class="info">
					<h3><?php echo $latest_article->post_title; ?></h3>
					<div class="publish-date">
						<i class="clock"></i><?php echo neuralnet_get_date($latest_article->post_date, 'span'); ?>
					</div>
				</div>
			</a>
			<?php
		} else {
			?>
			<div class="latest-article empty">Статей нет</div>
			<?php
		}

		?><div class="widget-sep"></div><?php

		/* Получение последней главы */
		if($latest_chapter = get_posts(['numberposts' => 1, 'post_type' => 'chapter'])) {
			$latest_chapter = $latest_chapter[0];

			?>
			<a href="<?php the_permalink($latest_chapter->ID); ?>" title="К последней главе..." class="latest-chapter">
				<i class="book icon"></i>
				<div class="info">
					<h3><?php echo neuralnet_get_chapter_number($latest_chapter->ID) . '. ' . $latest_chapter->post_title; ?></h3>
					<div class="publish-date">
						<i class="clock"></i><?php echo neuralnet_get_date($latest_chapter->post_date, 'span'); ?>
					</div>
				</div>
			</a>
			<?php
		} else {
			?>
			<div class="latest-chapter empty">Глав учебника нет</div>
			<?php
		}

		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : 'Что нового?';
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Заголовок</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = [];
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

		return $instance;
	}
}

/* Регистрация виджета */
add_action('widgets_init', function () {
	register_widget( 'NEURALNET_Latest_PAC_Widget' );
});