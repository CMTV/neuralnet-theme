<?php

/* -------------------------------------------------------------------------------------------------------------- */
/* Оглавление учебника */
/* -------------------------------------------------------------------------------------------------------------- */

if(function_exists('acf_add_options_sub_page')) {
	acf_add_options_sub_page([
		'page_title' =>     'Оглавление учебника',
		'menu_title' =>     'Оглавление',
		'menu_slug' =>      'book_index',
		'capability' =>     'edit_book_index',
		'parent_slug' =>    'edit.php?post_type=chapter'
	]);
}

if( function_exists('acf_add_local_field_group') ):

	acf_add_local_field_group(array (
		'key' => 'group_59841d78e1588',
		'title' => 'Оглавление учебника',
		'fields' => array (
			array (
				'key' => 'field_59841f6dd3a79',
				'label' => 'Оглавление учебника',
				'name' => 'book_index',
				'type' => 'relationship',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array (
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'post_type' => array (
					0 => 'chapter',
				),
				'taxonomy' => array (
				),
				'filters' => array (
					0 => 'search'
				),
				'elements' => [],
				'min' => '',
				'max' => '',
				'return_format' => 'object',
			),
		),
		'location' => array (
			array (
				array (
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'book_index',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));

endif;

/* -------------------------------------------------------------------------------------------------------------- */
/* Функции */
/* -------------------------------------------------------------------------------------------------------------- */

/**
 * Получение порядкового номера главы учебника
 *
 * @param int $post_id ID главы
 *
 * @return int Порядковый номер главы или -1 в случае ошибки
 */
function neuralnet_get_chapter_number(int $post_id) : int {
	$chapter = get_post($post_id);

	if($chapter->post_type !== 'chapter') return -1;

	$book_index = neuralnet_book_index();

	if($book_index) {
		$i = 0;
		foreach($book_index as $index_item) {
			if($index_item->ID === $post_id) {
				break;
			}

			$i++;
		}

		return $i+1;
	} else {
		return -1;
	}
}

function neuralnet_previous_next_chapters() : array {
	$book_index = neuralnet_book_index();

	$current_chapter_id = get_the_ID();

	$previous_link = '';
	$next_link = '';

	for($i = 0; $i < count($book_index); $i++) {
		$chapter = $book_index[$i];

		if($chapter->ID === $current_chapter_id) {

			if($i-1 >= 0) {
				$previous_link = get_permalink($book_index[$i-1]);
			}

			if($i+1 !== count($book_index)) {
				$next_link = get_permalink($book_index[$i+1]);
			}

		}
	}

	return [
		'previous' => $previous_link,
		'next' => $next_link
	];
}

function neuralnet_book_index() {
	return get_field('book_index', 'option');
}