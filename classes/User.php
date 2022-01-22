<?php

/**
 * Класс для обработки пользователей
 */
class User
{
    // Свойства
    /**
    * @var int ID пользователя из базы данных
    */
    public $id = null;

    /**
    * @var string логин пользователя
    */
    public $login = null;

    /**
    * @var string пароль пользователя
    */
    public $passw = null;
    
    /**
    * @var boolean активность пользователя
    */
    public $active = null;

    /**
    * Устанавливаем свойства объекта с использованием значений в передаваемом массиве
    */
    public function __construct($data = array()) {
      if (isset($data['id'])) $this->id = (int) $data['id'];
      if (isset($data['login'])) $this->login = $data['login'];
      if (isset($data['passw'])) $this->passw = $data['passw'];
      if (isset($data['active'])) $this->active = (bool) $data['active'];
    }

    /**
    * Устанавливаем свойства объекта с использованием значений из формы редактирования
    */
    public function storeFormValues($params) {

      $this->__construct($params);
    }


    /**
    * Возвращаем объект User, соответствующий заданному ID    
    */
    public static function getById($id) 
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM users WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) return new User($row);
    }

    
    /**
    * Возвращаем все или диапазон объектов User из базы данных
    */
    public static function getList($numRows=1000000, $order="login ASC") 
    { 
	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$fromPart = "FROM users";
	$sql = "SELECT * $fromPart ORDER BY $order LIMIT :numRows";
	$st = $conn->prepare($sql);
	$st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
	$st->execute();
	$list = array();
	while ($row = $st->fetch()) {
	    $user = new User($row);
	    $list[] = $user;
	}
	// Получаем общее количество пользователей
	$sql = "SELECT COUNT(*) AS totalRows $fromPart";
	$totalRows = $conn->query($sql)->fetch();
	$conn = null;
	return (array("results" => $list, "totalRows" => $totalRows[0]));
    }


    /**
    * Вставляем объект User в базу данных и устанавливаем его свойство ID.
    */
    public function insert() {

	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$sql = "INSERT INTO users (login, passw, active) VALUES (:login, :passw, :active)";
	$st = $conn->prepare($sql);
	$st->bindValue(":login", $this->login, PDO::PARAM_STR);
	$st->bindValue(":passw", $this->passw, PDO::PARAM_STR);
	$st->bindValue(":active", $this->active, PDO::PARAM_BOOL);
	$st->execute();
	$this->id = $conn->lastInsertId();
	$conn = null;
    }


    /**
    * Обновляем объект User в базе данных.
    */
    public function update() {

	if (is_null($this->id)) trigger_error("User::update(): Попытка"
		. " обновить объект User, у которого не установлено свойство ID.", E_USER_ERROR);

	// Обновляем пользователя
	$conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
	$sql = "UPDATE users SET login = :login, passw = :passw, active = :active WHERE id = :id";
	$st = $conn->prepare($sql);
	$st->bindValue(":login", $this->login, PDO::PARAM_STR);
	$st->bindValue(":passw", $this->passw, PDO::PARAM_STR);
	$st->bindValue(":active", $this->active, PDO::PARAM_BOOL);
	$st->bindValue(":id", $this->id, PDO::PARAM_INT);
	$st->execute();
	$conn = null;
    }


    /**
    * Удаляем объект User из базы данных.
    */
    public function delete() {

      if (is_null($this->id)) trigger_error("User::delete(): Попытка "
	      . "удалить объект User, у которого не установлено свойство ID.", E_USER_ERROR);
      // Удаляем пользователя
      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
      $st->bindValue(":id", $this->id, PDO::PARAM_INT);
      $st->execute();
      $conn = null;
    }
    
    
    /**
    * Проверяем существование пользователя с идентичным логином.
    */
    public static function loginExist($login) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("SELECT * FROM users WHERE login = :login");
      $st->bindValue(":login", $login, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row ? true : false;
    }
    
    
    /**
    * Получаем пароль пользователя.
    */
    public static function loginPassword($login) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("SELECT * FROM users WHERE login = :login");
      $st->bindValue(":login", $login, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row['passw'];
    }
    
    
    /**
    * Проверяем активность пользователя.
    */
    public static function loginActive($login) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("SELECT * FROM users WHERE login = :login");
      $st->bindValue(":login", $login, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row['active'] ? true : false;
    }
    
    
    /**
    * Получаем id пользователя.
    */
    public static function loginId($login) {

      $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
      $st = $conn->prepare("SELECT * FROM users WHERE login = :login");
      $st->bindValue(":login", $login, PDO::PARAM_STR);
      $st->execute();
      $row = $st->fetch();
      $conn = null;
      return $row['id'];
    }
}