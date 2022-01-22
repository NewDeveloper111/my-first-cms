<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
<style> input[type="checkbox"] { width: 30px; position: relative; top: 10px; height: 16px } </style>
    <h1><?php echo $results['pageTitle']?></h1>
    <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
            <input type="hidden" name="userId" value="<?php echo $results['user']->id ?? $_POST['userId'] ?? '' ?>">

    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage" style="padding: .8em"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

            <ul>

              <li>
                <label for="login">Логин</label>
                <textarea name="login" id="login" placeholder="Максимум 20 символов"
			  required maxlength="20" style="height: 1.5em;"><?php
			  echo htmlspecialchars($results['user']->login ?? $_POST['login'] ?? '')?></textarea>
              </li>

              <li>
                <label for="passw">Пароль</label>
                <textarea name="passw" id="passw" placeholder="Максимум 20 символов"
			  required maxlength="20" style="height: 1.5em;"><?php
			  echo htmlspecialchars($results['user']->passw ?? $_POST['passw'] ?? '')?></textarea>
              </li>

              <li>
		<label for="active">Активен</label>
		<input type="hidden" name="active" value="0"/>
		<input type="checkbox" name="active" value="1" <?php 
		if (isset($results['user']->active)) echo $results['user']->active ? 'checked' : "" ?>/>
              </li>
	    
	    </ul>

            <div class="buttons">
		<input type="submit" style="padding-left: 6.5px" name="saveChanges" value="Сохранить изменения"/>
		<input type="submit" formnovalidate name="cancel" value="Отмена"/>
            </div>

        </form>

    <?php if (isset($results['user']->id)) { ?>
    <p><a href="admin.php?action=deleteUser&amp;userId=<?php echo $results['user']->id ?>"
	  onclick="return confirm('Удалить пользователя ?')">Удалить пользователя</a></p>
    <?php } ?>
	  
<?php include "templates/include/footer.php" ?>              