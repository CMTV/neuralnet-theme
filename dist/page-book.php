<?php

get_header();

?>

	<section class="content book">
		<div class="inner">

            <h1 class="book-title">Учебник</h1>

            <div class="book-description">

                <p>Нейросети сегодня – одна из самых современных творческих и интересных областей знаний. Нейронные сети способны решать задачи, с которыми другими способами никак не справиться.</p>

                <ul>
                    <li>Распознавние объектов на изображениях</li>
                    <li>Рисование картин</li>
                    <li>Понимание и обработка устной речи</li>
                    <li>Нахождение паттернов в больших объемах данных</li>
                    <li>Ориентация в пространстве</li>
                </ul>

                <p>Все эти задачи с легкостью могут решать нейросети. И это вовсем не весь список. Нейронные сети решают задачи, которые могут решать только люди.</p>

            </div>

            <div class="book-index">
	            <?php

	            $book_index = neuralnet_book_index();

                for($i = 0; $i < count($book_index); $i++) :
                    $chapter_number = $i + 1;
                    $chapter = $book_index[$i];
                    ?>

                    <div class="chapter chapter-<?php echo $chapter_number; ?>">


                        <h2 class="title"><a href="<?php echo get_permalink($chapter); ?>" >Глава <?php echo $chapter_number . '. ' . $chapter->post_title; ?></a></h2>

                        <div class="description">
                            <?php echo get_extended($chapter->post_content)['main']; ?>
                        </div>

                        <div class="toc">
                            <?php

                            /* Содержание главы */
                            preg_replace_callback('/<(h\d)>(.+)<\/h\d>/', function ($matches) use ($chapter) {


                                ?>
                                    <h3 <?php if($matches[1] === 'h3') echo 'class="sub"'; ?>><a href="<?php echo get_permalink($chapter) . '#' . preg_replace('/\s/', '-', $matches[2]); ?>"><?php echo $matches[2]; ?></a></h3>
                                <?
                            }, $chapter->post_content);

                            ?>
                        </div>

                    </div>

                <?php endfor; ?>
            </div>

		</div>
	</section>

<?php

get_sidebar();

get_footer();