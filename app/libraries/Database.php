<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Database {
	private static $dbh = null;
	private static $stmt = null;

	public function __construct() {
		if (self::$dbh)
			return;

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

	// prepare statement with query
	public function query($sql) {
		self::$stmt = self::$dbh->prepare($sql);
	}

	// bind values
	public function bind($param, $value, $type = null) {
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
	public function execute() {
		return self::$stmt->execute();
	}

	// get result set as array of objects
	public function resultSet() {
		self::execute();
		return self::$stmt->fetchAll(PDO::FETCH_OBJ);
	}

	// get single record as object
	public function single() {
		self::execute();
		return self::$stmt->fetch(PDO::FETCH_OBJ);
	}

	// get row count
	public function rowCount() {
		return self::$stmt->rowCount();
	}
}