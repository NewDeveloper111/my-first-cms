<?php

/**
 * Класс для обработки пользователей
 */
class Subcategory
{
    // Свойства
    /**
    * @var int ID подкатегории из базы данных
    */
    public $id = null;

    /**
    * @var string название подкатегориии
    */
    public $name = null;

    /**
    * @var int ID из категории
    */
    public $categoryId = null;
    
    /**
    * Устанавливаем свойства объекта с использованием значений в передаваемом массиве
    */
    public function __construct($data = array()) {
      if (isset($data['id'])) $this->id = (int) $data['id'];
      if (isset($data['name'])) $this->name = $data['name'];
      if (isset($data['categoryId'])) $this->categoryId = (int) $data['categoryId'];
    }

    /**
    * Устанавливаем свойства объекта с использованием значений из формы редактирования
    */
    public function storeFormValues($params) {

      $this->__construct($params);
    }


    /**
    * Возвращаем объект Subcategory, соответствующий заданному ID    
    */
    public static function getById($id) 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM subcategories WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) return new Subcategory($row);
    }

    
    /**
    * Возвращаем все или диапазон объектов Subcategory из базы данных
    */
    public static function getList($numRows=1000000, $order="name ASC", $categoryId=null) 
    { 
	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$fromPart = "FROM subcategories";
	$where = $categoryId ? "WHERE categoryId = :categoryId" : "";
	$sql = "SELECT * $fromPart $where ORDER BY $order LIMIT :numRows";
	$st = $conn->prepare($sql);
	$st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
	if ($categoryId) {
	    $st->bindValue(":categoryId", $categoryId, PDO::PARAM_INT);	    
	}
	$st->execute();
	$list = array();
	while ($row = $st->fetch()) {
	    $subcategory = new Subcategory($row);
	    $list += [$subcategory->id => $subcategory];
	}
	// Получаем общее количество подкатегорий
	$sql = "SELECT COUNT(*) AS totalRows $fromPart";
	$totalRows = $conn->query($sql)->fetch();
	$conn = null;
	return (array("results" => $list, "totalRows" => $totalRows[0]));
    }


    /**
    * Вставляем объект Subcategory в базу данных и устанавливаем его свойство ID.
    */
    public function insert() {

	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$sql = "INSERT INTO subcategories (name, categoryId) VALUES (:name, :categoryId)";
	$st = $conn->prepare($sql);
	$st->bindValue(":name", $this->name, PDO::PARAM_STR);
	$st->bindValue(":categoryId", $this->categoryId, $this->categoryId ? 
		PDO::PARAM_INT : PDO::PARAM_NULL);
	$st->execute();
	$this->id = $conn->lastInsertId();
	$conn = null;
    }


    /**
    * Обновляем объект Subcategory в базе данных.
    */
    public function update() {

	if (is_null($this->id)) trigger_error("Subcategory::update(): Попытка обновить"
		. " объект Subcategory, у которого не установлено свойство ID.", E_USER_ERROR);

	// Обновляем подкатегорию
	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$sql = "UPDATE subcategories SET name = :name, categoryId = :categoryId WHERE id = :id";
	$st = $conn->prepare($sql);
	$st->bindValue(":name", $this->name, PDO::PARAM_STR);
	$st->bindValue(":categoryId", $this->categoryId, 
		$this->categoryId ? PDO::PARAM_INT : PDO::PARAM_NULL);
	$st->bindValue(":id", $this->id, PDO::PARAM_INT);
	$st->execute();
	$conn = null;
    }


    /**
    * Удаляем объект Subcategory из базы данных.
    */
    public function delete() {

      if (is_null($this->id)) trigger_error("Subcategory::delete(): Попытка удалить "
	      . "объект Subcategory, у которого не установлено свойство ID.", E_USER_ERROR);
      // Удаляем подкатегорию
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("DELETE FROM subcategories WHERE id = :id LIMIT 1");
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
      $conn = null;
    }
    
    
    /**
    * Проверяем существование идентичного названия подкатегории (или категории).
    */
    public static function nameExist($name, $table=false) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $x = $table ? "categories" : "subcategories";
      $st = $conn->prepare("SELECT * FROM $x WHERE name = :name");
      $st->bindValue(":name", $name, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row ? true : false;
    }
    
    
    /**
    * Получаем ID от категории подкатегории.
    */
    public static function nameCategoryId($name) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("SELECT * FROM subcategories WHERE name = :name");
      $st->bindValue(":name", $name, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row['categoryId'] ?? null;
    }
    
    
    /**
    * Получаем id подкатегории (или категории).
    */
    public static function nameId($name, $table=false) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $x = $table ? "categories" : "subcategories";
      $st = $conn->prepare("SELECT * FROM $x WHERE name = :name");
      $st->bindValue(":name", $name, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row['id'];
    }
    
    
    /**
    * Получаем массив для группировки подкатегорий по категориям, где ключ - id
     * категории, значение - массив из id подкатегорий.
    */
    public static function getGroup() {
	
	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$sql = "SELECT id FROM categories UNION "
		. "SELECT categoryId FROM subcategories WHERE categoryId IS NULL";
	$st = $conn->prepare($sql);
	$st->execute();
	$list = array();
	while ($row = $st->fetch()) {
	    $x = (int) $row[0];
	    $list += [$x => $conn->query("SELECT id FROM subcategories WHERE categoryId "
		    . ($x ? "= $x" : "IS NULL"))->fetchAll(PDO::FETCH_COLUMN)];
	}
	$conn = null;
	return $list;
    }
}