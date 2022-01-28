<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

    <h1><?php echo $results['pageTitle']?></h1>
    <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="subcategoryId" value="<?php echo $results['subcategory']->id ?? $_POST['subcategoryId'] ?? '' ?>">

    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage" style="padding: .8em"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="name">Подкатегория</label>
                <textarea name="name" id="name" placeholder="Максимум 100 символов"
			  required maxlength="100" style="height: 1.5em;"><?php
			  echo htmlspecialchars($results['subcategory']->name ?? $_POST['name'] ?? '')?></textarea>
              </li>

              <li>
                <label for="categoryId">Категория</label>
                <select name="categoryId">
                  <option value="0"<?php echo isset($results['subcategory']->categoryId) || 
		  isset($_POST['categoryId']) ? "" :  " selected" ?>>Без категории</option>
		      <?php foreach ($results['categories'] as $category) { ?>
		  <option value="<?php echo $category->id ?>"<?php 
		  echo ($category->id == ($_POST['categoryId'] ?? $results['subcategory']->categoryId)) ? 
		  " selected" : ""?>><?php echo htmlspecialchars($category->name) ?></option>
		      <?php  } ?>
                </select>
              </li>             
	    
	    </ul>
<!--<?php echo '<pre>'; print_r($results['categoryname']); echo '</pre>'; ?>-->
            <div class="buttons">
		<input type="submit" style="padding-left: 6.5px" name="saveChanges" value="Сохранить изменения"/>
		<input type="submit" formnovalidate name="cancel" value="Отмена"/>
            </div>

        </form>

    <?php if (isset($results['subcategory']->id)) { ?>
    <p><a href="admin.php?action=deleteSubcategory&amp;subcategoryId=<?php echo $results['subcategory']->id ?>"
	  onclick="return confirm('Удалить подкатегорию ?')">Удалить подкатегорию</a></p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>              