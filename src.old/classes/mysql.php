<?php


class MySQLException extends Exception{}


class Connection extends MySQLi {
	static public $queries = 0;
	
	public function prepare($query) {
		self::$queries++;

		if ($stmt = parent::prepare($query)) {
			return $stmt;
		}
		
		throw new MySQLException('<p>Query error:<br/>'.$query.'<br/>'.$this->error.'</p>');
		
	}
	
}


class MySQLConnection
{
	static protected $server;
	static protected $database;
	static protected $username;
	static protected $password;
	
	
	
	static protected $connection = null;

	public static function setup($server, $database, $username, $password) 
	{
		self::$server = $server;
		self::$database = $database;
		self::$username = $username;
		self::$password =$password;
	}
	
	

	static public function getInstance()
	{
		
		//echo 'getInstance<br/>';
		if (self::$connection == null)
		{
			//self::$connection = new mysqli(self::$server, self::$username, self::$password, self::$database);
			self::$connection = new Connection(self::$server, self::$username, self::$password, self::$database);
			
			
			if (mysqli_connect_error()) {
				throw new Exception('a',100);
			}
			
			
			self::$connection->query("SET CHARACTER SET 'utf8'");
		}
		
		
		return self::$connection;
	}
	
}


?>