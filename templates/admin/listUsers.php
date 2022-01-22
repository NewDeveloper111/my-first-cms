<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>

    <h1>Пользователи</h1>

    <?php if (isset($results['errorMessage'])) { ?>
            <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
    <?php } ?>

    <?php if (isset($results['statusMessage'])) { ?>
            <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
    <?php } ?>

          <table>
            <tr>
              <th>Логин</th>
              <th>Пароль</th>
              <th>Активен</th>	      
            </tr>
		
		<?php foreach ($results['users'] as $user) { ?>

            <tr onclick="location='admin.php?action=editUser&amp;userId=<?php echo $user->id?>'">
              <td>
                <?php echo $user->login ?>                                                                                                                       
              </td>
              <td>                                                                                                                                           
                <?php echo $user->passw ?>
              </td>
	      <td><?php echo $user->active ? 'ДА' : 'НЕТ' ?></td>
            </tr>
		
		<?php } ?>

          </table>

          <p><?php echo $results['totalRows']?> пользовател<?php
	  $str = (string) $results['totalRows'];
	  $a = substr($str, -2, 1);
	  $b = substr($str, -1);
	  echo $a != 1 && $b == 1 || $str == 1 ? 'ь' : ($a != 1 && in_array($b, [2, 3, 4]) ? 'я' : 'ей') 
		  ?> всего.</p>

          <p><a href="admin.php?action=newUser">Добавить пользователя</a></p>

<?php include "templates/include/footer.php" ?>              