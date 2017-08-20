<?php

$NEURALNET_CAPABILITIES = [];

/* ------------------------------------------------------------------------------------------------------------------ */
/* Участники могут добавлять медиафайлы (с ограничением на количество загрузок) */
/* ------------------------------------------------------------------------------------------------------------------ */

/** Лимит количества загружаемых файлов */
$NEURALNET_UPLOADS_LIMIT = 20;

/* Право добавлять медиафайлы */
$NEURALNET_CAPABILITIES['upload_files'] = 'contributor';

/* Ограничение на количество загрузок */
function neuralnet_contributor_uploads_limit($file) {
	global $wpdb, $NEURALNET_UPLOADS_LIMIT;

	$user = wp_get_current_user();

	if(in_array('contributor', $user->roles)) {
		$count = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = 'attachment' AND post_author = $user->ID");
		if($count >= $NEURALNET_UPLOADS_LIMIT) {
			$file['error'] = "Участники могут загружать не более $NEURALNET_UPLOADS_LIMIT " . neuralnet_number_ending($NEURALNET_UPLOADS_LIMIT, ['медиафайла', 'медиафайлов', 'медиафайлов']) . "!";
		}
	}

	return $file;
}
add_filter('wp_handle_upload_prefilter', 'neuralnet_contributor_uploads_limit');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Администраторы и редакторы могут изменять порядок глав в учебнике */
/* ------------------------------------------------------------------------------------------------------------------ */

$NEURALNET_CAPABILITIES['edit_book_index'] = ['editor', 'administrator'];

/* ------------------------------------------------------------------------------------------------------------------ */
/* Присвоение прав соответствующим ролям */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_capabilities() {
	global $NEURALNET_CAPABILITIES;

	foreach($NEURALNET_CAPABILITIES as $capability => $role) {
		if(is_array($role)) {
			foreach($role as $role_item) {
				$role_obj = get_role($role_item);
				$role_obj->add_cap($capability);
			}
		} else {
			$role = get_role($role);
			$role->add_cap($capability);
		}
	}
}
add_action('admin_init', 'neuralnet_capabilities');