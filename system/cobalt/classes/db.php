<?php

class Db {

	/**
	 * Stores all the MySQL connections
	 *
	 * @var array
	 */
	private $connections = array();

	/**
	 * Stores the active MySQL connection
	 *
	 * @var resource
	 */
	private $connection;

	public function __construct()
	{
		$this->connect('default');
	}

	/**
	 * Creates an MySQL connection
	 *
	 * @return void
	 */
	public function connect($name, $config = NULL)
	{
		if(is_array($config))
		{
			extract($config);
		}

		else
		{
			$hostname = Config::get('database.'.$name.'.hostname');
			$username = Config::get('database.'.$name.'.username');
			$password = Config::get('database.'.$name.'.password');
			$database = Config::get('database.'.$name.'.database');
			$pconnect = Config::get('database.'.$name.'.pconnect');
		}

		if($pconnect == TRUE)
		{
			$this->connection = mysql_pconnect($hostname, $username, $password, TRUE);	
		}

		else
		{
			$this->connection = mysql_connect($hostname, $username, $password, TRUE);
		}

		$this->connections[$name] = $this->connection;

		$this->select_db($database, $name);
	}

	/**
	 * Selects a database
	 *
	 * @param  string  $database
	 * @param  string  $connection
	 * @return void
	 */
	public function select_db($database, $connection = 'default')
	{
		mysql_select_db($database, $this->connections[$connection]);
	}

	/**
	 * Executes an MySQL query
	 *
	 * @param  string  $query
	 * @return mixed
	 */
	public function query($query, $bindings = NULL, $connection = 'default')
	{
		if(! is_null($bindings))
		{
			if( ! is_array($bindings))
			{
				$bindings = array($bindings);
			}

			foreach($bindings as $binding)
			{
				if(($position = strpos($query, '?')) !== FALSE)
				{
					if(strpos($binding, '?') !== FALSE)
					{
						$binding = str_replace('?', '&#63;', $binding);
					}

					$binding = mysql_real_escape_string($binding, $this->connection);
					$binding = "'$binding'";

					$query = substr_replace($query, $binding, $position, 1);
				}
			}
		}

		if (strpos($query, 'SELECT') === 0)
		{
			return new Select_Query($query, $this->connections[$connection]);
		}

		if(strpos($query, 'INSERT') === 0)
		{
			return new Insert_Query($query, $this->connections[$connection]);
		}

		else
		{
			mysql_query($query, $this->connections[$connection]);
		}
	}
}

class Select_Query {

	/**
	 * Stores the executed query
	 *
	 * @var resource
	 */
	private $query;

	/**
	 * Stores the query's results
	 *
	 * @var array
	 */
	 private $results = NULL;

	/**
	 * Stores the current row number
	 *
	 * @var int
	 */
	private $row = 0;

	/**
	 * Saves the query's result
	 *
	 * @param  resource  $query
	 * @return void
	 */
	public function __construct($query, $connection)
	{
		if( ! ($this->query = mysql_query($query, $connection)))
		{
			error(mysql_error() . ' in "' . $query . '"');
		}
	}

	/**
	 * Returns all the results from the database
	 *
	 * @return array
	 */
	public function results()
	{
		if(is_null($this->results))
		{
			while($row = mysql_fetch_object($this->query))
			{
				$this->results[] = $row;
			}
		}

		return $this->results;
	}

	/**
	 * Returns the next result from the database
	 *
	 * @return array
	 */
	public function row()
	{
		$results = $this->results();

		$this->row++;

		if(isset($results[$this->row - 1]))
		{
			return $results[$this->row - 1];
		}

		else
		{
			return NULL;
		}
	}

	/**
	 * Returns the number of results
	 *
	 * @return int
	 */
	 public function num_rows()
	 {
		return mysql_num_rows($this->query);
	 }
}

class Insert_Query {

	/**
	 * Stores the executed query
	 *
	 * @var resource
	 */
	private $query;

	/**
	 * Stores the active MySQL connection
	 *
	 * @var resource
	 */
	private $connection;

	/**
	 * Saves the query's result
	 *
	 * @param  resource  $query
	 * @return void
	 */
	public function __construct($query, $connection)
	{
		if( ! ($this->query = mysql_query($query, $connection)))
		{
			error(mysql_error() . ' in "' . $query . '"');
		}

		$this->connection = $connection;
	}

	/**
	 * Returns the ID of an inserted record
	 *
	 * @return int
	 */
	public function id()
	{
		return mysql_insert_id($this->connection);
	}
}