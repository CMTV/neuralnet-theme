<?php
/**
 * Формирование и отображение таблицы содержимого для статей и глав учебника
 */

$NEURALNET_TOC = [];

/* Установка якорей для всех заголовков + формирование оглавления */
function neuralnet_header_anchors($content) {
	global $post, $NEURALNET_TOC;

	$NEURALNET_TOC = [
		'post_id' => $post->ID,
		'toc' => []
	];

	$content = preg_replace_callback('/<(h\d)>(.+)<\/h\d>/', function ($matches) use (&$NEURALNET_TOC, $post) {
		$header_id = preg_replace('/[^-\p{L}0-9]+/u', '', preg_replace('/\s/', '-', strip_tags($matches[2])));
		$link_to_anchor = get_permalink($post) . '#' . $header_id;

		/* Проверка на дубликаты */
		foreach(array_map(function($header) {
			return $header['id'];
		}, $NEURALNET_TOC['toc']) as $header_from_array) {
            if($header_from_array === $header_id) {
                $header_id .= '_';
            }
		}

		array_push($NEURALNET_TOC['toc'], [
			'header_level' => $matches[1],
			'title' => $matches[2],
			'id' => $header_id
		]);

		return "<{$matches[1]} id=\"{$header_id}\">{$matches[2]}<a title=\"Ссылка на этот раздел\" class=\"header-anchor-link\" href=\"{$link_to_anchor}\"><i class=\"link\"></i></a></{$matches[1]}>";
	}, $content);

	return $content;
}
add_filter('the_content', 'neuralnet_header_anchors');

/* Вывод содержания справа от статьи/главы */
function neuralnet_get_toc() {
	global $NEURALNET_TOC;

	?>
	<div class="toc-widget widget">
		<h2 class="widget-title">Содержание</h2>
		<div class="widget-sep strong"></div>

		<?php

		for($i = 0; $i < count($NEURALNET_TOC['toc']); $i++) {
			$toc = $NEURALNET_TOC['toc'][$i];
			?>
			<a href="#<?php echo $toc['id']; ?>" class="toc-item <?php echo $toc['header_level']; ?>"><?php echo $toc['title']; ?></a>
		<?php }

		?>

	</div>
	<?php
}