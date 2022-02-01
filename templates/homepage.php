
<?php include "templates/include/header.php" ?>
    <ul id="headlines">
    <?php foreach ($results['articles'] as $article) { ?>
        <li class='<?php echo $article->id?>'>
            <h2>
                <span class="pubDate">
                    <?php echo date('j F', $article->publicationDate)?>
                </span>
                
                <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                    <?php echo htmlspecialchars( $article->title )?>
                </a>
                <span class="category">
		    in 
		    <a href=".?action=archive&amp;categoryId=<?php echo $article->categoryId ?? 0 ?>">
			<?php echo htmlspecialchars($results['categories'][$article->categoryId]->name ?? "Без категории")?>
		    </a>
                </span>
                <span class="category">
		    Подкатегория 
		    <a href=".?action=archive&amp;subcategoryId=<?php echo $article->subcategoryId ?? 0 ?>">
                        <?php echo htmlspecialchars($results['subcategories'][$article->subcategoryId]->name ?? "Без подкатегории")?>
                    </a>
                </span>
		
            </h2>
            <p class="summary"><?php echo htmlspecialchars(mb_strimwidth($article->content, 0, 53, '...'))?></p>
            <?php if ($article->authors !== []) { ?>
	    <p class="category" style="font-size: 90%; margin-top: 3px; color: #555">Автор<?php echo count($article->authors) == 1 ? '' : 'ы' ?>: <?php echo implode(', ', $article->authors) ?></p>
	    <?php } ?>
	    <img id="loader-identity" src="JS/ajax-loader.gif" alt="gif">
            
            <ul class="ajax-load">
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByPost" data-contentId="<?php echo $article->id?>">Показать продолжение (POST)</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="ajaxArticleBodyByGet" data-contentId="<?php echo $article->id?>">Показать продолжение (GET)</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="">(POST) -- NEW</a></li>
                <li><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="">(GET)  -- NEW</a></li>
            </ul>
            <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>" class="showContent" data-contentId="<?php echo $article->id?>">Показать полностью</a>
        </li>
    <?php } ?>
    </ul>
<!--<?php echo '<pre>'; print_r($results['categories']); echo '</pre>'; ?>-->
    <p><a href="./?action=archive">Article Archive</a></p>
<?php include "templates/include/footer.php" ?>