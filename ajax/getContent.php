<?php
require ('../config.php');

if (isset($_GET['articleId'])) {
    $article = Article::getById((int) $_GET['articleId']);
    echo $article->content;
}
if (isset($_POST['articleId'])) {
    $article = Article::getById((int) $_POST['articleId']);
    echo $article->content;
}