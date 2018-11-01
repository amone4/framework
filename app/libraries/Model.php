<?php

defined('_INDEX_EXEC') or die('Restricted access');

abstract class Model {
	private static $dbh = null;
	private static $stmt = null;

	protected $tableName;
	protected $primaryKey;

	private static function init() {
		// set DSN
		$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		// create PDO instance
		try {
			self::$dbh = new PDO($dsn, DB_USER, DB_PASS, $options);
		} catch (PDOException $e) {
			die($e->getMessage());
		}
	}

	public function __construct() {
		if (self::$dbh === null)
			self::init();
	}

	// prepare statement with query
	protected function query($sql) {
		self::$stmt = self::$dbh->prepare($sql);
	}

	// bind values
	protected function bind($param, $value, $type = null) {
		if (is_null($type)) {
			if (is_int($value)) {
				$type = PDO::PARAM_INT;
			} elseif (is_bool($value)) {
				$type = PDO::PARAM_BOOL;
			} elseif (is_null($value)) {
				$type = PDO::PARAM_NULL;
			} else {
				$type = PDO::PARAM_STR;
			}
		}

		self::$stmt->bindValue($param, $value, $type);
	}

	// execute the prepared statement
	protected function execute() {
		return self::$stmt->execute();
	}

	// get result set as array of objects
	protected function resultSet() {
		self::execute();
		return self::$stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get single record as object
	protected function single() {
		self::execute();
		return self::$stmt->fetch(PDO::FETCH_OBJ);
	}

	// get row count
	public function rowCount() {
		return self::$stmt->rowCount();
	}

	// function to get the primary key name of the table
	private function getPrimaryKey() {
		$this->query('SHOW KEYS FROM ' . $this->tableName . ' WHERE Key_name = \'PRIMARY\'');
		$row = $this->single();
		return $row->Column_name;
	}

	// function to insert a record
	public function insert($data) {
		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');

		$query1 = 'INSERT INTO ' . $this->tableName . '(';
		$query2 = ') VALUES (';
		$query3 = ')';

		foreach ($data as $key => $value) {
			$query1 .= $key . ', ';
			$query2 .= ':' . $key . ', ';
		}
		$query1 = chop($query1, ', ');
		$query2 = chop($query2, ', ');

		$this->query($query1 . $query2 . $query3);

		foreach ($data as $key => $value)
			$this->bind($key, $value);

		return $this->execute();
	}

	// function to find a record using the primary key
	public function select($id = null) {
		if ($id == null) return $this->selectWhere();

		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');
		if (!isset($this->primaryKey) || empty($this->primaryKey))
			$this->primaryKey = $this->getPrimaryKey();

		$query = 'SELECT * FROM ' . $this->tableName;
		$query .= ' WHERE ' . $this->primaryKey . ' = :id';
		$this->query($query);
		$this->bind('id', $id);
		return $this->single();
	}

	// function to find records using (key => value) pairs
	public function selectWhere($clause = null, $convertToArray = false) {
		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');

		$query = 'SELECT * FROM ' . $this->tableName;

		if ($clause != null) {
			$query .= ' WHERE ';
			foreach ($clause as $key => $value)
				$query .= $key . ' = :' . $key . ' AND ';
			$query = chop($query, ' AND ');
		}

		$this->query($query);

		if ($clause != null)
			foreach ($clause as $key => $value)
				$this->bind($key, $value);

		$set = $this->resultSet();
		if ($this->rowCount() > 1 || $convertToArray) return $set;
		else if ($this->rowCount() === 1) return $set[0];
		else return null;
	}

	// function to update a record using the primary key
	public function update($id, $data) {
		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');
		if (!isset($this->primaryKey) || empty($this->primaryKey))
			$this->primaryKey = $this->getPrimaryKey();

		$query = 'UPDATE ' . $this->tableName . ' SET ';

		foreach ($data as $key => $value)
			$query .= $key . ' = :' . $key . ', ';

		$query = chop($query, ', ');

		$query .= ' WHERE ' . $this->primaryKey . ' = :' . $this->primaryKey;

		$this->query($query);

		foreach ($data as $key => $value)
			$this->bind($key, $value);
		$this->bind($this->primaryKey, $id);

		return $this->execute();
	}

	// function to update records using (key => value) pairs
	public function updateWhere($data, $clause = null) {
		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');

		$query = 'UPDATE ' . $this->tableName . ' SET ';

		foreach ($data as $key => $value)
			$query .= $key . ' = :' . $key . '1, ';
		$query = chop($query, ', ');

		if ($clause != null) {
			$query .= ' WHERE ';
			foreach ($clause as $key => $value)
				$query .= $key . ' = :' . $key . '2 AND ';
			$query = chop($query, ' AND ');
		}

		$this->query($query);

		foreach ($data as $key => $value)
			$this->bind($key . '1', $value);

		if ($clause != null)
			foreach ($clause as $key => $value)
				$this->bind($key . '2', $value);

		return $this->execute();
	}

	// function to delete a record using the primary key
	public function delete($id) {
		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');
		if (!isset($this->primaryKey) || empty($this->primaryKey))
			$this->primaryKey = $this->getPrimaryKey();

		$query = 'DELETE FROM ' . $this->tableName;
		if ($id != null) $query .= ' WHERE ' . $this->primaryKey . ' = :id';
		$this->query($query);
		if ($id != null) $this->bind('id', $id);
		return $this->execute();
	}

	// function to delete records using (key => value) pairs
	public function deleteWhere($clause = null) {
		if (!isset($this->tableName) || empty($this->tableName))
			$this->tableName = strtolower(get_called_class() . 's');

		$query = 'DELETE FROM ' . $this->tableName;

		if ($clause != null) {
			$query .= ' WHERE ';
			foreach ($clause as $key => $value)
				$query .= $key . ' = :' . $key . ' AND ';
			$query = chop($query, ' AND ');
		}

		$this->query($query);

		if ($clause != null)
			foreach ($clause as $key => $value)
				$this->bind($key, $value);

		return $this->execute();
	}
}