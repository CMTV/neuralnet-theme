<?php

/**
 * Получение правильной формы слова в зависимости от числа
 *
 * @param int $number Число, для которого нужно вернуть правильную форму слова
 * @param array $endings Массив форм слов (1 яблоко, 4 яблока, 5 яблок)
 *
 * @return string
 */
function neuralnet_number_ending(int $number, array $endings) : string {
	$number = $number % 100;
	if ($number >= 11 && $number <= 19) {
		$ending = $endings[2];
	}
	else {
		$i = $number % 10;
		switch($i) {
			case (1): $ending = $endings[0]; break;
			case (2): case (3): case (4): $ending = $endings[1]; break;
			default: $ending = $endings[2];
		}
	}
	return $ending;
}

/**
 * Объединение всех элментов массива в строку через запятую. Запятая после последнего элемента не ставится
 *
 * @param array $terms
 *
 * @return string
 */
function neuralnet_implode_commas(array $terms) : string {
	$out = '';

	for($i = 0; $i < count($terms); $i++) {
		$out .= $terms[$i];

		if($i+1 !== count($terms)) {
			$out .= ', ';
		}
	}

	return $out;
}

/**
 * Путь к логотипу сайта
 *
 * @return string
 */
function neuralnet_logotype_url() : string {
	return NEURALNET_IMAGES . 'neuralnet.png';
}

/**
 * Получение пути к первому изображению в теле поста
 *
 * @param int $post_id
 *
 * @return string
 */
function neuralnet_first_post_img(int $post_id) : string {
	$post = get_post($post_id);
	ob_start();
	ob_end_clean();
	$regular = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
	$first_img_url = $matches[1][0];

	if(!$first_img_url) $first_img_url = '';

	return $first_img_url;
}

/**
 * Содержит ли данный пост таблицу содержания
 *
 * @return bool
 */
function neuralnet_has_toc() : bool {
	if(is_singular()) {
		$post_type = get_post_type();

		if($post_type === 'article' || $post_type === 'chapter') return true;
		else return false;
	} else return false;
}

/**
 * Проверка, существует ли страница по ее slug
 *
 * @param string $slug Slug страницы
 *
 * @return bool
 */
function neuralnet_page_exists(string $slug) : bool {
	$page = get_page_by_path($slug, OBJECT);

	return isset($page);
}

/**
 * Получение класса иконки данного типа поста.
 * Необходимо использовать в Цикле
 *
 * @return string Класс для <i> тега
 */
function neuralnet_get_the_post_icon() : string {
	switch(get_post_type()) {
		case 'post':
			return 'bullhorn';
		case 'article':
			return 'newspaper';
		case 'chapter':
			return 'book';
		default:
			return '';
	}
}

/**
 * Получение всех категорий поста исключая категорию "Без категории"
 *
 * @return array
 */
function neuralnet_get_categories() : array {
	$categories = get_the_category();

	if(count($categories) > 0) {
		if($categories[0]->term_id === 1) {
			array_shift($categories);
		}
	}

	return $categories;
}

/**
 * Получение красиво отформатированной строки с датой.
 * Если пост вышел сегодня, то выводит строку в формате "x часов/минут/секунд назад".
 * Если посты вышел вчера, то выводит строку в формате "вчера в XX:XX".
 * Если пост вышел позже, то выводит просто дата.месяц.год
 *
 * @param string $datetime Строка с датой публикации поста
 * @param string $wrapper Тег, в котором будет выводиться дата
 * @param string $classes Классы тега
 * @param string $attributes Атрибуты тега
 *
 * @return string
 */
function neuralnet_get_date(string $datetime, string $wrapper = 'span', $classes = '', string $attributes = '') : string {
	$now = new DateTime();
	$now->setTimezone(new DateTimeZone('Europe/Moscow'));

	$old = new DateTime($datetime, new DateTimeZone('Europe/Moscow'));

	if($now->format('Y-m-d') === $old->format('Y-m-d')) {
		/* Сегодня */
		$tag_content = human_time_diff($old->getTimestamp(), $now->getTimestamp()) . ' назад';
	}
	else if((new DateTime())->modify('-1 day')->format('Y-m-d') === $old->format('Y-m-d')) {
		/* Вчера */
		$tag_content = 'вчера в ' . $old->format('G:i');
	}
	else {
		/* Раньше */
		$tag_content = $old->format('d.m.Y');
	}

	if(empty($wrapper)) {
		return $tag_content;
	} else {
		return "<{$wrapper} title=\"{$old->format('d.m.Y в G:i')}\" class=\"{$classes}\" {$attributes}>{$tag_content}</{$wrapper}>";
	}
}

/**
 * Получение категорий, в которых опубликованы самые новые посты
 *
 * @param int $categories_number
 *
 * @return array Массив категорий
 */
function neuralnet_latest_categories(int $categories_number = 5) : array {
	$categories = get_categories();
	$recent_posts = [];
	foreach ($categories as $key=>$category) {
		$args = [
			'numberposts' => 1,
			'category' => $category->term_id,
		];
		$post = get_posts($args)[0];
		$recent_posts[ $category->term_id ] = strtotime($post->post_date);
	}

	arsort($recent_posts);

	$recent_categories = array_slice(array_keys($recent_posts), 0, $categories_number);

	return $recent_categories;
}

/**
 * Является ли данный пост главой учебника.
 * Использовать только в Цикле.
 *
 * @return bool
 */
function neuralnet_is_chapter() : bool {
	return get_post_type() === 'chapter';
}

function neuralnet_author_block() {
    $author_id = get_the_author_meta('ID');
    $author = get_userdata($author_id);

	?>

	<div class="author">

        <a href="<?php echo get_author_posts_url($author_id); ?>"><?php echo get_avatar($author_id, 120, 'mystery', 'Аватара автора ' . $author->display_name); ?></a>

        <div class="author-info">

            <a href="<?php echo get_author_posts_url($author_id); ?>"><h3 class="name"><?php echo $author->display_name; ?></h3></a>

            <div class="bio">
                <?php echo $author->description; ?>
            </div>

        </div>

	</div>

	<?php
}