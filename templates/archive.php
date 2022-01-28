<?php include "templates/include/header.php" ?>
	  
    <h1><?php echo htmlspecialchars($results['pageHeading']) ?></h1>
    
    <h3 class="categoryDescription"><?php echo htmlspecialchars($results['category']->description ?? ' ') ?></h3>
    

    <ul id="headlines" class="archive">

    <?php $arr = isset($_GET['categoryId']) ? $results['articles'] : $results['articless']; foreach ($arr as $article) { ?>

            <li>
                <h2>
                    <span class="pubDate">
                        <?php echo date('j F Y', $article->publicationDate)?>
                    </span>
                    <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>">
                        <?php echo htmlspecialchars( $article->title )?>
                    </a>
                    
                    <span class="category">
                        in 
                        <a href=".?action=archive&amp;categoryId=<?php echo $article->categoryId ?? 0 ?>">
                            <?php echo htmlspecialchars($results['categories'][$article->categoryId]->name ?? "Без категории") ?>
                        </a>
                    </span>
		    
		    <span class="category">
                        Подкатегория 
                        <a href=".?action=archive&amp;subcategoryId=<?php echo $article->subcategoryId ?? 0?>">
                            <?php echo htmlspecialchars($results['subcategories'][$article->subcategoryId]->name ?? "Без подкатегории") ?>
                        </a>
                    </span>
                              
                </h2>
              <p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
            </li>

    <?php } ?>

    </ul>

    <p><?php echo isset($_GET['categoryId']) ? $results['totalRows'] : $results['totalRowss']?> article<?php 
    echo (isset($_GET['categoryId']) ? $results['totalRows'] : $results['totalRowss']) != 1 ? 's' : '' ?> in total.</p>

    <p><a href="./">Return to Homepage</a></p>
	  
<?php include "templates/include/footer.php" ?>