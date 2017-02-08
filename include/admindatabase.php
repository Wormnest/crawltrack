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
// file: admindatabase.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

require_once("jgbdb.php");
$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);

if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}
if (!isset($_SESSION['optimize'])) {
	//database query to optimize the tables
	$sqloptimize1 = "OPTIMIZE TABLE crawlt_visits";
	$requeteoptimize1 = db_query($sqloptimize1, $connexion);
	$sqloptimize2 = "OPTIMIZE TABLE crawlt_visits_human";
	$requeteoptimize2 = db_query($sqloptimize2, $connexion);
	$sqloptimize3 = "OPTIMIZE TABLE crawlt_pages";
	$requeteoptimize3 = db_query($sqloptimize3, $connexion);
	$sqloptimize4 = "OPTIMIZE TABLE crawlt_referer";
	$requeteoptimize4 = db_query($sqloptimize4, $connexion);
	$_SESSION['optimize'] = 1;
}
$requete = db_query("show table status", $connexion);
mysqli_close($connexion);
echo "<h1>" . $language['database_size'] . "</h1>\n";

//summary table display
echo "<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";
echo "<table   cellpadding='0px' cellspacing='0' width='850px'>\n";
echo "<tr><th class='tableau1' rowspan='2'>\n";
echo "" . $language['table_name'] . "\n";
echo "</th>\n";
echo "<th class='tableau1' rowspan='2'>\n";
echo "" . $language['nbr_of_data'] . "\n";
echo "</th>\n";
echo "<th class='tableau2' colspan='3'>\n";
echo "" . $language['table_size'] . "\n";
echo "</th></tr>\n";
echo "<tr><th class='tableau20'>\n";
echo "" . $language['data'] . "\n";
echo "</th>\n";
echo "<th class='tableau20'>\n";
echo "" . $language['index'] . "\n";
echo "</th>\n";
echo "<th class='tableau200'>\n";
echo "" . $language['nbr_tot_visits3'] . "\n";
echo "</th></tr>\n";

//counter for alternate color lane
$comptligne = 2;
$tablesize = 0;
$databasesize = 0;
$indexsize = 0;
$indexbasesize = 0;
while ($result = $requete->fetch_assoc()) {
	if (preg_match('/^crawlt_/i', $result['Name'])) {
		if ($comptligne % 2 == 0) {
			echo "<tr><td class='tableau3'>" . $result['Name'] . "</td>\n";
			echo "<td class='tableau3'>" . numbdisp($result['Rows']) . "</td>\n";
			$tablesize = ($tablesize + $result['Data_length']) / 1024;
			$databasesize+= $tablesize;
			$indexsize = ($indexsize + $result['Index_length']) / 1024;
			$indexbasesize+= $indexsize;
			echo "<td class='tableau3'>" . numbdisp(round($tablesize)) . " KB</td>\n";
			echo "<td class='tableau3'>" . numbdisp(round($indexsize)) . " KB</td>\n";
			echo "<td class='tableau5'>" . numbdisp(round($tablesize + $indexsize)) . " KB</td></tr>\n";
		} else {
			echo "<tr><td class='tableau30'>" . $result['Name'] . "</td>\n";
			echo "<td class='tableau30'>" . numbdisp($result['Rows']) . "</td>\n";
			$tablesize = ($tablesize + $result['Data_length']) / 1024;
			$databasesize+= $tablesize;
			$indexsize = ($indexsize + $result['Index_length']) / 1024;
			$indexbasesize+= $indexsize;
			echo "<td class='tableau30'>" . numbdisp(round($tablesize)) . " KB</td>\n";
			echo "<td class='tableau30'>" . numbdisp(round($indexsize)) . " KB</td>\n";
			echo "<td class='tableau50'>" . numbdisp(round($tablesize + $indexsize)) . " KB</td></tr>\n";
		}
		$comptligne++;
	}
}
if ($comptligne % 2 == 0) {
	echo "<tr><td class='tableau3d' colspan='2'><b>" . $language['total'] . "</b></td>\n";
	echo "<td class='tableau3'><b>" . numbdisp(round($databasesize)) . " KB</b></td>\n";
	echo "<td class='tableau3'><b>" . numbdisp(round($indexbasesize)) . " KB</b></td>\n";
	echo "<td class='tableau5'><b>" . numbdisp(round($databasesize + $indexbasesize)) . " KB</b></td></tr>\n";
} else {
	echo "<tr><td class='tableau30d' colspan='2'><b>" . $language['total'] . "</b></td>\n";
	echo "<td class='tableau30'><b>" . numbdisp(round($databasesize)) . " KB</b></td>\n";
	echo "<td class='tableau30'><b>" . numbdisp(round($indexbasesize)) . " KB</b></td>\n";
	echo "<td class='tableau50'><b>" . numbdisp(round($databasesize + $indexbasesize)) . " KB</b></td></tr>\n";
}
echo "</table></div><br><br>\n";
?>
