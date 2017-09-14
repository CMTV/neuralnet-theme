<?php

/**
 * Виджет, отображающий дополнительное меню в сайдбаре
 */
class NEURALNET_Additional_Menu_Widget extends WP_Widget {
	public function __construct() {
		$widget = [
			'classname' =>      'additional-menu-widget',
			'description' =>    'Виджет, отображающий дополнительное меню в сайдбаре'
		];

		parent::__construct('neuralnet-additional-menu-widget', 'Вспомогательное меню', $widget);
	}

	public function widget($args, $instance) {
		echo $args['before_widget'];
		if(!empty($instance['title'])) {
			echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
		}

		?><div class="widget-sep strong"></div><?php

        $menu_args = [];
        $menu_args['menu'] = 'neuralnet-additional-menu';
		$menu_args['container'] = '';
		$menu_args['menu_class'] = 'additional-menu';
		$menu_args['items_wrap'] = '%3$s';
		$menu_args['echo'] = false;
		$menu_args['walker'] = new class extends Walker_Nav_Menu {
			public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
				$output .= '<a href="' . $item->url . '">' . $item->title . '</a>';

				$output .= '<div class="widget-sep"></div>';
			}
		};

        $menu_html = wp_nav_menu($menu_args);

        // Убираем пробелы и перенос строки
        $menu_html = trim(preg_replace('/\s\s+/', ' ', $menu_html));

        // Убираем последнюю отделяющую черту
		if( ( $pos = strrpos( $menu_html , '<div class="widget-sep"></div>' ) ) !== false ) {
			$search_length  = strlen( '<div class="widget-sep"></div>' );
			$menu_html = substr_replace( $menu_html , '' , $pos , $search_length );
		}

        echo $menu_html;

        echo $args['after_widget'];
	}

	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : 'Вспомогательное меню';
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

/* Регистрация вспомогательного меню */
add_action( 'after_setup_theme', function() {
	register_nav_menu( 'neuralnet-additional-menu', 'Вспомогательное меню' );
});

/* Регистрация виджета */
add_action('widgets_init', function () {
	register_widget( 'NEURALNET_Additional_Menu_Widget' );
});