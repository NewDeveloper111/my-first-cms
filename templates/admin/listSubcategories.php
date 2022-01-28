<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

    <h1>Подкатегории</h1>

    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <?php if (isset($results['statusMessage'])) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Подкатегория</th>
              <th>Категория</th>	      
            </tr>
		
		<?php foreach ($results['subcategories'] as $subcategory) { ?>

            <tr onclick="location='admin.php?action=editSubcategory&amp;subcategoryId=<?php echo $subcategory->id?>'">
              <td>
                <?php echo $subcategory->name ?>                                                                                                                       
              </td>
              <td>                                                                                                                                           
                <?php 
                if (!empty($subcategory->categoryId)) {
                    echo $results['categories'][$subcategory->categoryId]->name;                        
                } else {
		    echo "Без категории";
                }?>
              </td>
            </tr>
		
		<?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> подкатегори<?php
	  $str = (string) $results['totalRows'];
	  $a = substr($str, -2, 1);
	  $b = substr($str, -1);
	  echo $a != 1 && $b == 1 || $str == 1 ? 'я' : ($a != 1 && in_array($b, [2, 3, 4]) ? 'и' : 'й') 
		  ?> всего.</p>

          <p><a href="admin.php?action=newSubcategory">Добавить подкатегорию</a></p>

<?php include "templates/include/footer.php" ?>              