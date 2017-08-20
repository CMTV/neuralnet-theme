<?php
/**
 * Виджет, отображающий категории, для которых недавно были опубликованы новости или статьи
 */
class NEURALNET_Latest_Categories_Widget extends WP_Widget {
	public function __construct() {
		$widget = [
			'classname' =>      'categories-widget',
			'description' =>    'Виджет, отображающий категории, в которых недавно были опубликованы новости или статьи'
		];

		parent::__construct('neuralnet-categories-widget', 'Последние категории', $widget);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];
		if(!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}

		?><div class="widget-sep strong"></div><?php

		/* Получение 5 последних категорий */
		$latest_categories = [];
		$i = 0;
		foreach(($categories = neuralnet_latest_categories()) as $category_id) {
			if($category_id === 1) continue;

			$category = get_category($category_id);

			?>
			<a href="<?php echo get_category_link($category); ?>" class="category">
				<span class="category-title"><?php echo $category->name; ?></span>
			</a>
            <?php if($i+1 !== count($categories)) : ?>
			<div class="widget-sep"></div>
			<?php endif;
            $i++;
		}

		echo $args['after_widget'];
	}

	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : 'Последние категории';
		$categories_number = !empty($instance['categories_number']) ? $instance['categories_number'] : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">Заголовок</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'categories_number' ) ); ?>">Количество категорий</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'categories_number' ) ); ?>" min="1" max="10" name="<?php echo esc_attr( $this->get_field_name( 'categories_number' ) ); ?>" type="number" value="<?php echo esc_attr( $categories_number ); ?>">
		</p>
		<?php
	}

	public function update($new_instance, $old_instance) {
		$instance = [];
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		$instance['categories_number'] = (!empty($new_instance['categories_number'])) ? $new_instance['categories_number'] : 5;

		return $instance;
	}
}

/* Регистрация виджета */
add_action('widgets_init', function () {
	register_widget('NEURALNET_Latest_Categories_Widget');
});