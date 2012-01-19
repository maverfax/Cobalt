<?php

class Schema {

	/**
	 * Edits or creates a table
	 *
	 * @param  string   $table
	 * @param  Closure  $callback
	 * @return void
	 */ 
	public function table($table, $callback)
	{
		$table = new Schema_Table($table);

		$callback($table);

		return $table->save();
	}
}

class Schema_Table extends Cobalt {

	/**
	 * Stores the table's name
	 *
	 * @var string
	 */
	private $table;

	/**
	 * Stores the type of query that will be run
	 *
	 * @var string
	 */
	private $type = 'alter';

	/**
	 * Stores the fields to be created
	 *
	 * @var array
	 */
	private $fields = array();

	/**
	 * Wether or not the table will be altered. If true, then
	 * it will store the name of the field that will be altered
	 *
	 * @param mixed
	 */
	private $alter = FALSE;

	/**
	 * Stores the data of the field to be altered
	 *
	 * @param array
	 */
	private $alter_field = array();

	/**
	 * Wether or not a field will be dropped. If true, then
	 * it will store the name of the field to be dropped
	 *
	 * @param mixed
	 */
	private $drop_field = FALSE;

	/**
	 * Saves the table's name
	 *
	 * @param  string  $table
	 * @return void
	 */
	public function __construct($table)
	{
		$this->table = $table;
	}

	/**
	 * Creates a string field
	 *
	 * @param  string        $name
	 * @param  int           $size
	 * @return Schema_Table
	 */
	public function string($name, $size = 255)
	{
		$field = array(
			'name'   => $name,
			'type'   => 'VARCHAR',
			'size'   => $size,
			'null'   => TRUE,
			'unique' => FALSE,
		);

		if( ! $this->alter)
		{
			$this->fields[] = $field;
		}

		else
		{
			$this->alter_field = $field;
		}

		return $this;
	}

	/**
	 * Creates an integer field
	 *
	 * @param  string        $name
	 * @param  int           $size
	 * @return Schema_Table
	 */
	public function integer($name, $size = 11)
	{
		$field = array(
			'name'   => $name,
			'type'   => 'INT',
			'size'   => $size,
			'null'   => TRUE,
			'unique' => FALSE,
		);

		if( ! $this->alter)
		{
			$this->fields[] = $field;
		}

		else
		{
			$this->alter_field = $field;
		}

		return $this;
	}

	/**
	 * Creates a text field
	 *
	 * @param  string        $name
	 * @return Schema_Table
	 */
	public function text($name)
	{
		$field = array(
			'name'   => $name,
			'type'   => 'TEXT',
			'null'   => TRUE,
			'unique' => FALSE,
		);

		if( ! $this->alter)
		{
			$this->fields[] = $field;
		}

		else
		{
			$this->alter_field = $field;
		}

		return $this;
	}

	/**
	 * Makes a field unique
	 *
	 * @param  string        $field
	 * @return Schema_Table
	 */
	public function unique($field = FALSE)
	{
		$field = $this->_find_field($field);

		$this->fields[$field]['unique'] = TRUE;

		return $this;
	}

	/**
	 * Makes a field automatically increment
	 *
	 * @param  string        $field
	 * @return Schema_Table
	 */
	public function increments($field = FALSE)
	{
		$field = $this->_find_field($field);

		$this->fields[$field]['increments'] = TRUE;

		return $this;
	}

	/**
	 * Alters a column
	 *
	 * @param  string        $column
	 * @return Schema_Table
	 */
	public function change($column)
	{
		$this->alter = $column;

		return $this;
	}

	/**
	 * Removes a column
	 *
	 * @param  string        $column
	 * @return Schema_Table
	 */
	public function drop_column($column)
	{
		$this->drop_field = $column;

		return $this;
	}

	/**
	 * Marks that the query will create a table
	 *
	 * @return Schema_Table
	 */
	public function create()
	{
		$this->type = 'create';		

		return $this;
	}

	/**
	 * Marks that the query will destroy a table
	 *
	 * @return Schema_Table
	 */
	public function drop()
	{
		$this->type = 'delete';

		return $this;
	}

	/**
	 * Builds and performs the query
	 *
	 * @return void
	 */
	public function save()
	{
		// Create a table
		if($this->type == 'create')
		{
			$this->db->query('DROP TABLE IF EXISTS `'.$this->table.'`');

			$query  = 'CREATE TABLE  `'.$this->table.'` (';
			$fields = count($this->fields);

			for($i = 0; $i < $fields; $i++)
			{
				$query .= $this->_build_field($this->fields[$i]);

				if($i != $fields - 1)
				{
					$query .= ',';
				}
			}

			$query .= ') ENGINE = MYISAM;';
		}

		// Edit a table
		elseif($this->type == 'alter')
		{
			$query = 'ALTER TABLE `'.$this->table.'` ';

			if($this->alter != FALSE)
			{
				$query .= 'CHANGE ' . $this->_build_field($this->alter_field, $this->alter);
			}

			elseif($this->drop_field != FALSE)
			{
				$query .= 'DROP `'.$this->drop_field.'` ';
			}
		}

		// Delete a table
		elseif($this->type == 'delete')
		{
			$query = 'DROP TABLE `'.$this->table.'`';
		}

		$this->db->query($query);
	}

	/**
	 * Prepares a field in SQL
	 *
	 * @param  array  $field
	 * @param  string $change
	 * @return string
	 */
	private function _build_field($field, $change = FALSE)
	{
		$query = '';

		if($change != FALSE)
		{
			$query .= '`'.$change.'` ';
		}

		$query .= '`'.$field['name'].'` ';
		$query .= ($field['type'] == 'TEXT') ? 'TEXT ' : $field['type'].' ( '.$field['size'].' ) ';

		if($change != FALSE)
		{
			$query .= 'CHARACTER SET latin1 COLLATE latin1_swedish_ci ';
		}

		$query .= ($field['null'] == TRUE) ? 'NULL ' : 'NOT NULL ';

		if(isset($field['increments']) && $field['increments'] == TRUE)
		{
			$query .= 'AUTO_INCREMENT ';
		}

		if($field['unique'] == TRUE)
		{
			$query .= 'PRIMARY KEY ';
		}

		return $query;
	}

	/**
	 * Finds a field that contains the given name, or, if no name is given,
	 * returns the last added field.
	 *
	 * @param  string  $field
	 * @return int
	 */
	private function _find_field($field)
	{
		if($field == FALSE)
		{
			return count($this->fields) - 1;
		}

		else
		{
			foreach($fields as $key => $data)
			{
				if($data['name'] == $field)
				{
					return $key;
				}
			}
		}
	}
}