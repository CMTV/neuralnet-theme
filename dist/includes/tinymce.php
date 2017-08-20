<?php

/* ------------------------------------------------------------------------------------------------------------------ */
/* Удаление заголовков 1, 4, 5 и 6 уровней */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_tinymce_remove_header($init) {
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3';
	return $init;
}
add_filter('tiny_mce_before_init', 'neuralnet_tinymce_remove_header');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Стили для визуального редактора */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_tinymce_editor_style() {
	add_editor_style(NEURALNET_STYLES . 'tinymce.css?' . rand());
}
add_action('wp_head', 'neuralnet_tinymce_editor_style');
add_action('admin_head', 'neuralnet_tinymce_editor_style');