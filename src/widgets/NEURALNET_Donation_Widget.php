<?php
/**
 * Виджет, позволяющий пожертвовать деньги на поддержку проекта
 */
class NEURALNET_Donation_Widget extends WP_Widget {
    public function __construct() {
        $widget = [
            'classname' =>      'donation-widget',
            'description' =>    'Виджет, позволяющий пожертвовать деньги на поддержку проекта'
        ];

        parent::__construct('neuralnet-donation-widget', 'Кнопка "Поддержать портал"', $widget);
    }

    public function widget($args, $instance) {
        ?>

    <a href="<?php echo get_permalink(get_page_by_path('donate')); ?>" target="_blank" class="donation-button-widget"><i class="thumbs-o-up"></i><span>Поддержать портал!</span></a>

        <?
    }
}

/* Регистрация виджета */
add_action('widgets_init', function () {
    register_widget( 'NEURALNET_Donation_Widget' );
});