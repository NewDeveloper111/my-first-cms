<?php

//phpinfo(); die();

require("config.php");

try {
    initApplication();
} catch (Exception $e) { 
    $results['errorMessage'] = $e->getMessage();
    require(TEMPLATE_PATH . "/viewErrorPage.php");
}


function initApplication()
{
    $action = isset($_GET['action']) ? $_GET['action'] : "";

    switch ($action) {
        case 'archive':
          archive();
          break;
        case 'viewArticle':
          viewArticle();
          break;
        default:
          homepage();
    }
}

function archive() 
{
    $results = [];
    
    $categoryId = isset($_GET['categoryId']) ? (int) $_GET['categoryId'] : null;
    $subcategoryId = isset($_GET['subcategoryId']) ? (int) $_GET['subcategoryId'] : null;
    $results['category'] = Category::getById($categoryId);
    $results['subcategory'] = Subcategory::getById($subcategoryId);
    $data = Article::getList(100000, $categoryId);
    $datas = Article::getBySubcat(100000, $subcategoryId);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['articless'] = $datas['results'];
    $results['totalRowss'] = $datas['totalRows'];
    $results['categories'] = Category::getList()['results'];
    $results['subcategories'] = Subcategory::getList()['results'];
    
    $results['pageHeading'] = $categoryId === 0 ? 'Без категории' : ($subcategoryId === 0 ?
	    'Без подкатегории' : ($results['category'] ?  $results['category']->name : 
	($results['subcategory'] ?  $results['subcategory']->name : "Article Archive")));
    $results['pageTitle'] = $results['pageHeading'] . " | Widget News";
    
    require( TEMPLATE_PATH . "/archive.php" );
}

/**
 * Загрузка страницы с конкретной статьёй
 * 
 * @return null
 */
function viewArticle() 
{   
    if ( !isset($_GET["articleId"]) || !$_GET["articleId"] ) {
      homepage();
      return;
    }

    $results = array();
    $articleId = (int)$_GET["articleId"];
    $results['article'] = Article::getById($articleId);
    
    if (!$results['article']) {
        throw new Exception("Статья с id = $articleId не найдена");
    }
    
    $results['category'] = Category::getById($results['article']->categoryId);
    $results['subcategory'] = Subcategory::getById($results['article']->subcategoryId);
    $results['pageTitle'] = $results['article']->title . " | Простая CMS";
    
    require(TEMPLATE_PATH . "/viewArticle.php");
}

/**
 * Вывод домашней ("главной") страницы сайта
 */
function homepage() 
{
    $results = array();
    $data = Article::getList(HOMEPAGE_NUM_ARTICLES);
    $results['articles'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['categories'] = Category::getList()['results'];
    $results['subcategories'] = Subcategory::getList()['results'];
    $results['pageTitle'] = "Простая CMS на PHP";
    
//    echo "<pre>";
//    print_r($data);
//    echo "</pre>";
//    die();
    
    require(TEMPLATE_PATH . "/homepage.php");
    
}