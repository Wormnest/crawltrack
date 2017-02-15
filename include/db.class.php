<?php
/**
 *  CrawlTrack class for database access.
 *  CrawlTrack. A crawler tracker for websites.
 *  License: GPL version 2 or newer. See license.txt.
 *  File: Password.class.php
 *  Author: Jacob Boerema
 */

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

/**
 * Class for handling database access.
 */
class ctDb
{
	public $connexion;
	
	public $secret_key;
	
	// $oldversion is true if the version we are updating from is < 150
	public $oldversion = false;
	// old versions stored language in config file
	public $oldlang;
	
	private $configfile;
	
	/**
	 * Init our class and connect to the database.
	 */
	public function __construct() {
		$this->configfile = dirname(__FILE__) . '/configconnect.php';
		if (!file_exists($this->configfile)) {
			$this->configfile = '';
			die("config file not found!");
		} else {
			// Open database configuration settings
			// TODO For now we use require but we should see if we could change this
			// so we can use require_once or that we have a $config parameter in the
			// constructor that has a config class object with all these details!!!
			// TODO2: Counting of queries. Posisbly handle via a class var in config class.
			require($this->configfile);
			$this->secret_key = $secret_key;
			if (!isset($crawlthost)) {
				// Older configuration files before version 150 had different names
				$this->oldversion = true;
				$this->oldlang = $lang;
				$crawlthost = $host;
				$crawltuser = $user;
				$crawltpassword = $password;
				$crawltdb = $db;
			}
			$this->connexion = mysqli_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb) or die("MySQL: problem connecting to database");
			if (mysqli_connect_errno()) {
				die(mysqli_connect_error());
			}
		}
	}
	
	/**
	 * Close the database connection.
	 */
	public function close() {
		 if ($this->connexion) {
			mysqli_close($this->connexion);
		 }
	}
	
	/**
	 * Escape a query string.
	 */
	public function sql_quote($value) {
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		$value = $this->connexion->real_escape_string($value);
		return $value;
	}
	
	// More or less the same as the next function fetch_assoc_stmt
	// Since that last one uses copy_value instead of a loop I assume that
	// one might be faster but should be tested sometime.
	// See: http://php.net/manual/en/mysqli-stmt.bind-result.php#102179
	public static function fetch_results($result)
	{   
		$array = array();
	   
		if($result instanceof mysqli_stmt)
		{
			$result->store_result();
		   
			$variables = array();
			$data = array();
			$meta = $result->result_metadata();
		   
			while($field = $meta->fetch_field())
				$variables[] = &$data[$field->name]; // pass by reference
		   
			call_user_func_array(array($result, 'bind_result'), $variables);
		   
			$i=0;
			while($result->fetch())
			{
				$array[$i] = array();
				foreach($data as $k=>$v)
					$array[$i][$k] = $v;
				$i++;
			   
				// don't know why, but when I tried $array[] = $data, I got the same one result in all rows
			}
		}
		elseif($result instanceof mysqli_result)
		{
			while($row = $result->fetch_assoc())
				$array[] = $row;
		}
	   
		return $array;
	}

	/**
	* Fetches the results of a prepared statement as an array of associative
	* arrays such that each stored array is keyed by the result's column names.
	* @param stmt   Must have been successfully prepared and executed prior to calling this function
	* @param buffer Whether to buffer the result set; if true, results are freed at end of function
	* @return An array, possibly empty, containing one associative array per result row
	* See: http://php.net/manual/en/mysqli-stmt.fetch.php#117275
	*/
	public static function fetch_assoc_stmt(\mysqli_stmt $stmt, $buffer = true) {
		if ($buffer) {
			$stmt->store_result();
		}
		$fields = $stmt->result_metadata()->fetch_fields();
		$args = array();
		foreach($fields AS $field) {
			$key = str_replace(' ', '_', $field->name); // space may be valid SQL, but not PHP
			$args[$key] = &$field->name; // this way the array key is also preserved
		}
		call_user_func_array(array($stmt, "bind_result"), $args);
		$results = array();
		while($stmt->fetch()) {
			$results[] = array_map("copy_value", $args);
		}
		if ($buffer) {
			$stmt->free_result();
		}
		return $results;
	}
}

/**
* Copy value as value
* JB: Doesn't seem to work when put inside a class
*/
function copy_value($v) {
	return $v;
}

?>
