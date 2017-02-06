<?php
//----------------------------------------------------------------------
//  CrawlTrack
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: jgbdb.php
//----------------------------------------------------------------------
// Author: Jacob Boerema
//----------------------------------------------------------------------
// Purpose: database handling functions
//			Temporary solution to make updating easier.
//			TODO: Use a database class!
//----------------------------------------------------------------------

// Can't use this check since it can be called before that is set.
/*if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access allowed</h1>');
}*/

/**
 * Create a new database connection with the crawltrack database.
 * Returns the connexion if successful.
 */
function db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb) {
	$connexion = mysqli_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb) or die("MySQL connection to database problem");
	if (mysqli_connect_errno()) {
		die(mysqli_connect_error());
	}
	return $connexion;
}

/**
 * Get the number of result rows in the executed query.
 */
function db_num_rows($query) {
	$result = $query->num_rows;
}

/**
 * Get the field value from a certain row of a result set.
 */
function db_result($res, $row, $field=0) {
    $res->data_seek($row);
    $datarow = $res->fetch_array();
    return $datarow[$field];
}

/**
 * Run the specified query on the specified connexion and return the result.
 */
function db_mysql_query($sql, $connexion) {
	$result = $connexion->query($sql) or die("MySQL query error");
	return $result;
// Below doesn't work because it returns a boolean not a sql object!
//	return $connexion->query($sql) or die("MySQL query error");
}

?>
