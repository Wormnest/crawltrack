<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.6
//----------------------------------------------------------------------
// Crawler Tracker for website
//----------------------------------------------------------------------
// Author: Jean-Denis Brun
//----------------------------------------------------------------------
// Code cleaning: Philippe Villiers
//----------------------------------------------------------------------
// Website: www.crawltrack.net
//----------------------------------------------------------------------
// That script is distributed under GNU GPL license
//----------------------------------------------------------------------
// file: update.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//crawlt_update table creation if not exist in case of upgrade from a previous version
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

// Call the maintenance script which will do the job
// Override the default tables_to_check array
$tables_to_check = array(
	array(
		'table_name' => 'crawlt_update',
		'action' => 'create',
		'create_delete_query' => "CREATE TABLE crawlt_update (
			idcrawlt_update int(10) unsigned NOT NULL auto_increment,
			update_id int(10) unsigned default NULL,
			PRIMARY KEY  (idcrawlt_update)
		)",
		'insert_query' => "INSERT INTO crawlt_update VALUES (1,'0')",
	)
);

$maintenance_mode = 'update';
$tables_to_touch = array('crawlt_update');
include 'maintenance.php';

$idlastupdate = 0;
if ($existing_crawlt_update_table)
{
	//query to get the actual liste id
	$sqlupdate = "SELECT * FROM crawlt_update";
	$requeteupdate = db_query($sqlupdate, $connexion);
	while ($ligne = mysql_fetch_object($requeteupdate)) {
		$update = $ligne->update_id;
		if ($update > $idlastupdate) {
			$idlastupdate = $update;
		}
	}
}
mysql_close($connexion);
if (!empty($tables_actions_error_messages)) {
	//case we had a problem during table creation
	echo "<br><br><h5>" . $language['step1_install_no_ok3'] . "</h5><br><br>";
} else {
?>
		<br><br><h1><?php echo $language['update_title_attack'] ?></h1>
		
		<h2><?php echo $language['your_list'] ?>&nbsp;Crawlerlist <?php echo $idlastupdate ?></h2>
		<h2><?php echo $language['crawltrack_list'] ?>&nbsp;<iframe name="I1" src="http://www.crawltrack.net/listcrawler/infolistid.htm" marginwidth="1" marginheight="1" scrolling="no" border="0" frameborder="0" width="150px" height="24px"></iframe></h2>
	
		
		<div class="form">
	
		<h2><form action="index.php" method="POST" >
		<input type="hidden" name="validform" value="14">
		<input type="hidden" name="navig" value="6">
		<input name='ok' type="submit" value="<?php echo $language['update_crawler'] ?>" size="20">
		</form><br>

		<form action="index.php" method="POST" >
		<input type="hidden" name="navig" value="6">
		<input name="ok" type="submit" value="<?php echo $language['no_update'] ?>" size="20">
		</form></h2>
		
		</div><br>

<?php
}
?>
