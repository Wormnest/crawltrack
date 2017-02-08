<?php
//----------------------------------------------------------------------
//  CrawlTrack
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Updating: Jacob Boerema
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// This script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: updateattack.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

//crawlt_update table creation if not exist in case of upgrade from a previous version
//check if table already exist
require_once("jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

// Call the maintenance script which will do the job
// Override the default tables_to_check array
$tables_to_check = array(
	array(
		'table_name' => 'crawlt_update_attack',
		'action' => 'create',
		'create_delete_query' => "CREATE TABLE crawlt_update_attack (
			idcrawlt_update INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
			update_id INTEGER UNSIGNED NULL,
			PRIMARY KEY(idcrawlt_update)
			)",
		'insert_query' => "INSERT INTO crawlt_update_attack VALUES (1,'1')"
	)
);

$maintenance_mode = 'update';
$tables_to_touch = array('crawlt_update_attack');
include 'maintenance.php';

if($existing_crawlt_update_attack_table)
{
	//query to get the actual liste id
	
	$sqlupdate = "SELECT * FROM crawlt_update_attack";
	$requeteupdate = db_query($sqlupdate, $connexion);
	$idlastupdate = 0;
	while ($ligne = $requeteupdate->fetch_object()) {
		$update = $ligne->update_id;
		if ($update > $idlastupdate) {
			$idlastupdate = $update;
		}
	}
}
mysqli_close($connexion);
if (!empty($tables_actions_error_messages)) {
	//case we had a problem during table creation
	echo "<br><br><h5>" . $language['step1_install_no_ok3'] . "</h5><br><br>";
} else {
	echo "<br><br><h1>" . $language['update_title_attack'] . "</h1>\n";
	echo "<h2>" . $language['your_list'] . "&nbsp;Attacklist $idlastupdate</h2>\n";
	echo "<h2>" . $language['crawltrack_list'] . "&nbsp;<iframe name=\"I1\" src=\"http://www.crawltrack.net/listattack/infolistid.htm\" marginwidth=\"1\" marginheight=\"1\" scrolling=\"no\" border=\"0\" frameborder=\"0\" width=\"150px\" height=\"24px\"></iframe></h2>\n";
	echo "<div class=\"form\">\n";
	echo "<h2><form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='28'>\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input name='ok' type='submit'  value='" . $language['update_attack'] . "' size='20'>\n";
	echo "</form><br>\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input name='ok' type='submit'  value='" . $language['no_update_attack'] . "' size='20'>\n";
	echo "</form></h2>\n";
	echo "</div><br>\n";
}
?>
