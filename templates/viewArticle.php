<?php include "templates/include/header.php" ?>
	  
    <h1 style="width: 75%;"><?php echo htmlspecialchars( $results['article']->title )?></h1>
    <div style="width: 75%; font-style: italic;"><?php echo htmlspecialchars( $results['article']->summary )?></div>
    <div style="width: 75%;"><?php echo $results['article']->content?></div>
    <p class="pubDate">Published on <?php  echo date('j F Y', $results['article']->publicationDate)?>
        
        in 
        <a href="./?action=archive&amp;categoryId=<?php echo $results['category']->id ?? 0 ?>">
            <?php echo htmlspecialchars($results['category']->name ?? 'Без категории') ?>
        </a>
	_
	<a href="./?action=archive&amp;subcategoryId=<?php echo $results['subcategory']->id ?? 0 ?>">
            <?php echo htmlspecialchars($results['subcategory']->name ?? 'Без подкатегории') ?>
        </a>
        
    </p>
    <?php if ($results['article']->authors !== []) { ?>
    <p style="font-style: italic">Автор<?php echo count($results['article']->authors) == 1 ? '' : 'ы' ?>: <?php 
    echo implode(', ', $results['article']->authors) ?></p>
    <?php } ?>

    <p><a href="./">Вернуться на главную страницу</a></p>
	  
<?php include "templates/include/footer.php" ?>    
                