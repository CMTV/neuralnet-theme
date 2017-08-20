<?php

/* ------------------------------------------------------------------------------------------------------------------ */
/* Регистрация новых типов постов */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_custom_post_types() {

	/* Статьи */
	$labels = [
		"name" => __( "Статьи", "neuralnet" ),
		"singular_name" => __( "Статья", "neuralnet" ),
		"menu_name" => __( "Статьи", "neuralnet" ),
		"all_items" => __( "Все статьи", "neuralnet" ),
		"add_new" => __( "Добавить новую", "neuralnet" ),
		"add_new_item" => __( "Добавить статью", "neuralnet" ),
		"edit_item" => __( "Редактировать статью", "neuralnet" ),
		"new_item" => __( "Новая статья", "neuralnet" ),
		"view_item" => __( "Посмотреть статью", "neuralnet" ),
		"view_items" => __( "Посмотреть статьи", "neuralnet" ),
		"search_items" => __( "Найти статью", "neuralnet" ),
		"not_found" => __( "Статей не найдено", "neuralnet" ),
		"not_found_in_trash" => __( "Статей в корзине не найдено", "neuralnet" ),
	];

	$args = [
		"label" => __( "Статьи", "neuralnet" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => "articles",
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "article", "with_front" => true ),
		"query_var" => true,
		"menu_position" => 5,
		"menu_icon" => "dashicons-media-document",
		"supports" => array( "title", "editor", "custom-fields", "comments", "revisions", "author" ),
		"taxonomies" => array( "category", "post_tag" ),
	];

	register_post_type( "article", $args );

	/* Главы */
	$labels = [
		"name" => __( "Главы", "neuralnet" ),
		"singular_name" => __( "Глава", "neuralnet" ),
		"menu_name" => __( "Учебник", "neuralnet" ),
		"all_items" => __( "Все главы", "neuralnet" ),
		"add_new" => __( "Добавить новую", "neuralnet" ),
		"add_new_item" => __( "Добавить главу", "neuralnet" ),
		"edit_item" => __( "Редактировать главу", "neuralnet" ),
		"new_item" => __( "Новая глава", "neuralnet" ),
		"view_item" => __( "Посмотреть главу", "neuralnet" ),
		"view_items" => __( "Посмотреть глав", "neuralnet" ),
		"search_items" => __( "Найти главу", "neuralnet" ),
		"not_found" => __( "Глав не найдено", "neuralnet" ),
		"not_found_in_trash" => __( "Глав в корзине не найдено", "neuralnet" ),
	];

	$args = [
		"label" => __( "Главы", "neuralnet" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => false,
		"show_in_menu" => true,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array( "slug" => "chapter", "with_front" => true ),
		"query_var" => true,
		"menu_position" => 6,
		"menu_icon" => "dashicons-book",
		"supports" => array( "title", "editor", "custom-fields", "comments", "revisions", "author" ),
		"taxonomies" => array( "post_tag" ),
	];

	register_post_type( "chapter", $args );
}
add_action('init', 'neuralnet_custom_post_types');