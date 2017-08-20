<?php

/* ------------------------------------------------------------------------------------------------------------------ */
/* Редизайн формы входа */
/* ------------------------------------------------------------------------------------------------------------------ */

function neuralnet_login_form_styles() { ?>
	<link rel="stylesheet" href="<?php echo NEURALNET_STYLES . 'login-form.css'; ?>">
	<link rel="shortcut icon" href="<?php echo NEURALNET_IMAGES . 'site-icon.png'; ?>" type="image/png">
<?php
}
add_action('login_head', 'neuralnet_login_form_styles');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Ссылка формы входа и ее описание */
/* ------------------------------------------------------------------------------------------------------------------ */

add_filter('login_headerurl', function () {
    return home_url('/');
});

add_filter('login_headertitle', function () {
    return 'Нейронные сети';
});