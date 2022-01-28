<?php

require("config.php");
session_start();
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

if ($action != "login" && $action != "logout" && !$username) {
    login();
    exit;
}

switch ($action) {
    case 'login':
        login();
        break;
    case 'logout':
        logout();
        break;
    case 'newArticle':
        newArticle();
        break;
    case 'editArticle':
        editArticle();
        break;
    case 'deleteArticle':
        deleteArticle();
        break;
    case 'listCategories':
        listCategories();
        break;
    case 'newCategory':
        newCategory();
        break;
    case 'editCategory':
        editCategory();
        break;
    case 'deleteCategory':
        deleteCategory();
        break;
    case 'newUser':
        newUser();
        break;
    case 'editUser':
        editUser();
        break;
    case 'listUsers':
        listUsers();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    case 'newSubcategory':
        newSubcategory();
        break;
    case 'editSubcategory':
        editSubcategory();
        break;
    case 'listSubcategories':
        listSubcategories();
        break;
    case 'deleteSubcategory':
        deleteSubcategory();
        break;
    default:
        listArticles();
}

/**
 * Авторизация пользователя (админа) -- установка значения в сессию
 */
function login() {

    $results = array();
    $results['pageTitle'] = "Admin Login | Widget News";

    if (isset($_POST['login'])) {

        // Пользователь получает форму входа: попытка авторизировать пользователя

        if ($_POST['username'] == ADMIN_USERNAME 
                && $_POST['password'] == ADMIN_PASSWORD) {

          // Вход прошел успешно: создаем сессию и перенаправляем на страницу администратора
          $_SESSION['username'] = ADMIN_USERNAME;
          header( "Location: admin.php");

        }  elseif (User::loginExist($_POST['username']) && 
		User::loginActive($_POST['username']) &&
		$_POST['password'] == User::loginPassword($_POST['username'])) {
	    // Вход прошел успешно: пользователь есть в базе данных, его статус активен,
	    //  создаем сессию и перенаправляем на страницу администратора
	    $_SESSION['username'] = $_POST['username'];
          header("Location: admin.php");
	} else {
	  // Ошибка входа: выводим сообщение об ошибке для пользователя
          $results['errorMessage'] = "Неправильный пароль или логин, попробуйте ещё раз.";
          require(TEMPLATE_PATH . "/admin/loginForm.php");
	}
    } else {
      // Пользователь еще не получил форму: выводим форму
      require(TEMPLATE_PATH . "/admin/loginForm.php");
    }
}


function logout() {
    unset( $_SESSION['username'] );
    header( "Location: admin.php" );
}


function newArticle() {
	  
    $results = array();
    $results['pageTitle'] = "New Article";
    $results['formAction'] = "newArticle";
    $results['group'] = Subcategory::getGroup();
    $results['categories'] = Category::getList()['results'];
    $results['subcategories'] = Subcategory::getList()['results'];

    if (isset($_POST['saveChanges'])) {
	$article = new Article();
        $article->storeFormValues($_POST);
	if ($_POST['categoryId'] && $_POST['subcategoryId'] && $results['group'][$_POST['categoryId']] !== []
		&& !in_array($_POST['subcategoryId'], $results['group'][$_POST['categoryId']])) {
	    $results['errorMessage'] = 'Ошибка: категория не соответствует подкатегории';
	    require (TEMPLATE_PATH . '/admin/editArticle.php');
	} else {
	    $article->insert();
	    header("Location: admin.php?status=changesSaved");
	}
	
    } elseif ( isset( $_POST['cancel'] ) ) {

        // Пользователь сбросил результаты редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {

        // Пользователь еще не получил форму редактирования: выводим форму
        $results['article'] = new Article;
        require( TEMPLATE_PATH . "/admin/editArticle.php" );
    }
}


/**
 * Редактирование статьи
 * 
 * @return null
 */
function editArticle() {
	  
    $results = array();
    $results['pageTitle'] = "Edit Article";
    $results['formAction'] = "editArticle";
    $results['group'] = Subcategory::getGroup();
    $results['categories'] = Category::getList()['results'];
    $results['subcategories'] = Subcategory::getList()['results'];
    
    if (isset($_POST['saveChanges'])) {

        // Пользователь получил форму редактирования статьи: сохраняем изменения
        if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
            header( "Location: admin.php?error=articleNotFound" );
            return;
        }
	$article->storeFormValues($_POST);
	if ($_POST['categoryId'] && $_POST['subcategoryId'] && $results['group'][$_POST['categoryId']] !== []
		&& !in_array($_POST['subcategoryId'], $results['group'][$_POST['categoryId']])) {
	    $results['errorMessage'] = 'Ошибка: категория не соответствует подкатегории';
	    require (TEMPLATE_PATH . '/admin/editArticle.php');
	} else {
	    $article->update();
	    header("Location: admin.php?status=changesSaved");
	}
    } elseif ( isset( $_POST['cancel'] ) ) {
        // Пользователь отказался от результатов редактирования: возвращаемся к списку статей
        header( "Location: admin.php" );
    } else {
        // Пользвоатель еще не получил форму редактирования: выводим форму
        $results['article'] = Article::getById((int)$_GET['articleId']);
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }
}


function deleteArticle() {

    if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
        header( "Location: admin.php?error=articleNotFound" );
        return;
    }

    $article->delete();
    header( "Location: admin.php?status=articleDeleted" );
}


function listArticles() {
    
    $results = array();
    $data = Article::getList(1000000, null, "publicationDate DESC", true);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['categories'] = Category::getList()['results'];
    $results['subcategories'] = Subcategory::getList()['results'];
    $results['pageTitle'] = "Все статьи";

    if (isset($_GET['error'])) { // вывод сообщения об ошибке (если есть)
        if ($_GET['error'] == "articleNotFound") 
            $results['errorMessage'] = "Error: Article not found.";
    }

    if (isset($_GET['status'])) { // вывод сообщения (если есть)
        if ($_GET['status'] == "changesSaved") {
            $results['statusMessage'] = "Your changes have been saved.";
        }
        if ($_GET['status'] == "articleDeleted")  {
            $results['statusMessage'] = "Article deleted.";
        }
    }

    require(TEMPLATE_PATH . "/admin/listArticles.php" );
}

function listCategories() {
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Article Categories";

    if ( isset( $_GET['error'] ) ) {
        if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
        if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
	if ( $_GET['error'] == "categoryContainsSubcategories" ) $results['errorMessage'] = "Ошибка: Категория содержит подкатегории. "
		. "Удалите подкатегории или назначьте их другой категории перед удалением этой категории.";
    }

    if ( isset( $_GET['status'] ) ) {
        if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
        if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
    }

    require( TEMPLATE_PATH . "/admin/listCategories.php" );
}
	  
	  
function newCategory() {

    $results = array();
    $results['pageTitle'] = "New Article Category";
    $results['formAction'] = "newCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category->storeFormValues( $_POST );
        if (Subcategory::nameExist($category->name, true)) {
	    $results['errorMessage'] = 'Ошибка: Категория существует';
	    require (TEMPLATE_PATH . '/admin/editCategory.php');
	} else {
	    $category->insert();
	    header("Location: admin.php?action=listCategories&status=changesSaved");
	}
    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function editCategory() {

    $results = array();
    $results['pageTitle'] = "Edit Article Category";
    $results['formAction'] = "editCategory";

    if ( isset( $_POST['saveChanges'] ) ) {

        // User has posted the category edit form: save the category changes

        if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
          header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
          return;
        }

        $category->storeFormValues($_POST);
        if (Subcategory::nameExist($category->name, true) && 
		$category->id != Subcategory::nameId($category->name, true)) {
	    $results['errorMessage'] = 'Ошибка: Категория существует';
	    require(TEMPLATE_PATH . '/admin/editCategory.php');
	} else {
	    $category->update();
	    header("Location: admin.php?action=listCategories&status=changesSaved");
	}
    } elseif ( isset( $_POST['cancel'] ) ) {

        // User has cancelled their edits: return to the category list
        header( "Location: admin.php?action=listCategories" );
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById( (int)$_GET['categoryId'] );
        require( TEMPLATE_PATH . "/admin/editCategory.php" );
    }

}


function deleteCategory() {

    if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
        header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
        return;
    }

    $articles = Article::getList(1000000, $category->id, 'publicationDate ASC', true);

    if ($articles['totalRows'] > 0) {
        header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
        return;
    }
    $subcategories = Subcategory::getList(1000000, 'name ASC', $category->id);
    if (count($subcategories['results']) > 0) {
        header("Location: admin.php?action=listCategories&error=categoryContainsSubcategories");
        return;
    }

    $category->delete();
    header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}


function newUser() {

    $results = array();
    $results['pageTitle'] = "Новый пользователь";
    $results['formAction'] = "newUser";
    if (isset($_POST['saveChanges'])) {
        $user = new User;
        $user->storeFormValues($_POST);
	if (User::loginExist($user->login)) {
	    $results['errorMessage'] = 'Ошибка: Логин занят';
	    require (TEMPLATE_PATH . '/admin/editUser.php');
	} else {
	    $user->insert();
	    header("Location: admin.php?action=listUsers&status=changesSaved");
	}
    } elseif (isset($_POST['cancel'])) {
        // Пользователь отменяет добавление: возврат к списку пользователей
        header("Location: admin.php?action=listUsers");
    } else {
	require(TEMPLATE_PATH . "/admin/editUser.php");
    }
}

function editUser() {

    $results = array();
    $results['pageTitle'] = "Редактировать пользователя";
    $results['formAction'] = "editUser";

    if (isset($_POST['saveChanges'])) {
	$user = User::getById((int) $_POST['userId']);
	$user->storeFormValues($_POST);
	if (User::loginExist($user->login) && 
		$user->id != User::loginId($user->login)) {
	    $results['errorMessage'] = 'Ошибка: Логин занят';
	    require(TEMPLATE_PATH . '/admin/editUser.php');
	} else {
	    $user->update();
	    header("Location: admin.php?action=listUsers&status=changesSaved");
	}
    } elseif (isset($_POST['cancel'])) {
        // Пользователь отменяет редактирование: возврат к списку пользователей
        header("Location: admin.php?action=listUsers" );
    } else {
        // Отобразить данные пользователя перед редактированием
        $results['user'] = User::getById((int) $_GET['userId']);
	require(TEMPLATE_PATH . "/admin/editUser.php");
    }
}

function listUsers() {
    $results = array();
    $data = User::getList();
    $results['users'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Пользователи";

    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved") {
	    $results['statusMessage'] = "Изменения сохранены.";
	} elseif ($_GET['status'] == "userDeleted") {
	    $results['statusMessage'] = "Пользователь удалён.";
	}
    }
    require(TEMPLATE_PATH . "/admin/listUsers.php");
}

function deleteUser() {

    if (!$user = User::getById((int)$_GET['userId'])) {
        header("Location: admin.php?action=listUsers&error=userNotFound");
        return;
    }
    $user->delete();
    header("Location: admin.php?action=listUsers&status=userDeleted");
}


function newSubcategory() {

    $results = array();
    $results['pageTitle'] = "Новая подкатегория";
    $results['formAction'] = "newSubcategory";
    $data = Category::getList();
    $results['categories'] = $data['results'];
    if (isset($_POST['saveChanges'])) {
        $subcategory = new Subcategory;
        $subcategory->storeFormValues($_POST);
	if (Subcategory::nameExist($subcategory->name)) {
	    $results['errorMessage'] = 'Ошибка: Подкатегория существует';
	    require (TEMPLATE_PATH . '/admin/editSubcategory.php');
	} else {
	    $subcategory->insert();
	    header("Location: admin.php?action=listSubcategories&status=changesSaved");
	}
    } elseif (isset($_POST['cancel'])) {
        // Пользователь отменяет добавление: возврат к списку подкатегорий
        header("Location: admin.php?action=listSubcategories");
    } else {
	// Пользователь еще не получил форму редактирования: выводим форму
        $results['subcategory'] = new Subcategory;
        
	require(TEMPLATE_PATH . "/admin/editSubcategory.php");
    }
}

function editSubcategory() {

    $results = array();
    $results['pageTitle'] = "Редактировать подкатегорию";
    $results['formAction'] = "editSubcategory";
    $data = Category::getList();
    $results['categories'] = $data['results'];
    
    if (isset($_POST['saveChanges'])) {
	$subcategory = Subcategory::getById((int) $_POST['subcategoryId']);
	$subcategory->storeFormValues($_POST);
	if (Subcategory::nameExist($subcategory->name) && 
		$subcategory->id != Subcategory::nameId($subcategory->name)) {
	    $results['errorMessage'] = 'Ошибка: Подкатегория существует';
	    require(TEMPLATE_PATH . '/admin/editSubcategory.php');
	} else {
	    $subcategory->update();
	    header("Location: admin.php?action=listSubcategories&status=changesSaved");
	}
    } elseif (isset($_POST['cancel'])) {
        // Пользователь отменяет редактирование: возврат к списку подкатегорий
        header("Location: admin.php?action=listSubcategories" );
    } else {
        // Отобразить данные подкатегории перед редактированием
        $results['subcategory'] = Subcategory::getById((int) $_GET['subcategoryId']);
	require(TEMPLATE_PATH . "/admin/editSubcategory.php");
    }
}

function listSubcategories() {
    
    $results = array();
    $data = Subcategory::getList();
    $results['subcategories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $data = Category::getList();
    $results['categories'] = array();
    foreach ($data['results'] as $category) { 
        $results['categories'][$category->id] = $category;
    }
    $results['pageTitle'] = "Подкатегории";
    if (isset($_GET['error'])) {
	if ($_GET['error'] == "subcategoryContainsArticles") $results['errorMessage'] = "Ошибка: Подкатегория содержит статьи. "
		. "Удалите статьи или назначьте их другой подкатегории перед удалением этой подкатегории.";
    }
    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved") {
	    $results['statusMessage'] = "Изменения сохранены.";
	} elseif ($_GET['status'] == "subcategoryDeleted") {
	    $results['statusMessage'] = "Подкатегория удалена.";
	}
    }
    require(TEMPLATE_PATH . "/admin/listSubcategories.php");
}

function deleteSubcategory() {

    if (!$subcategory = Subcategory::getById((int) $_GET['subcategoryId'])) {
        header("Location: admin.php?action=listSubcategories&error=subcategoryNotFound");
        return;
    }
    $articles = Article::getBySubcat(1000000, $subcategory->id, true, 'publicationDate ASC');
    if ($articles['totalRows'] > 0) {
        header("Location: admin.php?action=listSubcategories&error=subcategoryContainsArticles" );
        return;
    }
    $subcategory->delete();
    header("Location: admin.php?action=listSubcategories&status=subcategoryDeleted");
}