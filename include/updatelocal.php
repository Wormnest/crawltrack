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
// file: updatelocal.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$updatelistua = array();
$updatelistname = array();
$updatelisturl = array();
$updatelistuser = array();
$listcrawler = array();
$crawlernameadd = array();
$crawleruaadd = array();

if (file_exists('include/crawlerlist.php')) {
	include ("include/crawlerlist.php");
	
	//query to get the actual list id
	$sqlupdate = "SELECT * FROM crawlt_update";
	$queryupdate = db_query($sqlupdate, $db->connexion);
	$idlastupdate = 0;
	while ($ligne = $queryupdate->fetch_object()) {
		$update = $ligne->update_id;
		if ($update > $idlastupdate) {
			$idlastupdate = $update;
		}
	}
	//test to know is the crawler list is up to date.
	if ($idlist == $idlastupdate) {
		//the list is up to date
		echo "<br><br><h1>" . $language['list_up_to_date'] . "</h1><br><br>";
	} else {
		$tabdata = explode("crawltrack", $crawlerlist);
		$nbr = count($tabdata) / 4;
		//we treat the file content
		$i = 0;
		for ($j = 1;$j <= $nbr;$j++) {
			$updatelistua[$j] = $tabdata[$i];
			$i = $i + 1;
			$updatelistname[$j] = $tabdata[$i];
			$i = $i + 1;
			$updatelisturl[$j] = $tabdata[$i];
			$i = $i + 1;
			$updatelistuser[$j] = $tabdata[$i];
			$i = $i + 1;
		}
		
		$sqlexist = "SELECT * FROM crawlt_crawler";
		$queryexist = db_query($sqlexist, $db->connexion);
		while ($ligne = $queryexist->fetch_object()) {
			$crawlerua = $ligne->crawler_user_agent;
			$listcrawler[] = $crawlerua;
		}
		$nbrdata = count($updatelistua);
		$nbrupdate = 0;
		
		for ($k = 1;$k <= $nbrdata;$k++) {
			$uatest = stripslashes($updatelistua[$k]);
			$ua = $updatelistua[$k];
			$name = $updatelistname[$k];
			$url = $updatelisturl[$k];
			$user = $updatelistuser[$k];
			if (in_array($uatest, $listcrawler)) {
			} else {
				$sqlinsert = "INSERT INTO crawlt_crawler (crawler_user_agent,crawler_name, crawler_url, crawler_info, crawler_ip)
					VALUES ('" . crawlt_sql_quote($db->connexion, $ua) . "','" . crawlt_sql_quote($db->connexion, $name) . "','" . crawlt_sql_quote($db->connexion, $url) . "','" . crawlt_sql_quote($db->connexion, $user) . "','')";
				$queryinsert = db_query($sqlinsert, $db->connexion);
				$nbrupdate = $nbrupdate + 1;
				$crawlernameadd[] = $name;
				$crawleruaadd[] = $ua;
			}
		}
		
		echo "<h1><br><br>$nbrupdate&nbsp;" . $language['crawler_add'] . "<br></h1>";
		
		$sqlinsertid = "INSERT INTO crawlt_update (update_id) VALUES ('" . crawlt_sql_quote($db->connexion, $idlist) . "')";
		$queryinsertid = db_query($sqlinsertid, $db->connexion);
		echo "<div align='center'><table cellpadding='0px' cellspacing='0' width='750px'><tr><td class='tableau1'>" . $language['crawler_name'] . "</td><td class='tableau2'>" . $language['user_agent'] . "</td></tr>\n";
		for ($l = 0;$l < $nbrupdate;$l++) {
			$crawlnamedisplay = htmlentities($crawlernameadd[$l]);
			$crawluadisplay = htmlentities($crawleruaadd[$l]);
			if ($l % 2 == 0) {
				echo "<tr><td class='tableau3'>$crawlnamedisplay</td>\n";
				echo "<td class='tableau5'>$crawluadisplay</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30'>$crawlnamedisplay</td>\n";
				echo "<td class='tableau50'>$crawluadisplay</td></tr>\n";
			}
		}
		echo "</tr></table></div><br><br>";
	}
	$db->close(); // Close database
} else {
	echo "<br><br><h1>" . $language['no_crawler_list'] . "</h1><br>";
}
?>
