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
// file: updatelocalattack.php
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
if (file_exists('include/attacklist.php')) {
	include ("include/attacklist.php");
	
	//databaseconnection
	require_once("jgbdb.php");
	$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
	
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
	//test to know is the crawler list is up to date.
	if ($idlist == $idlastupdate) {
		//the list is up to date
		echo "<br><br><h1>" . $language['list_up_to_date'] . "</h1><br><br>";
	} else {
		$tabdata = explode("crawltototrack", $attacklist);
		$nbr = count($tabdata) / 4;
		//we treat the file content
		$i = 0;
		for ($j = 1;$j <= $nbr;$j++) {
			$updatelistid[$j] = $tabdata[$i];
			$i = $i + 1;
			$updatelistattack[$j] = $tabdata[$i];
			$i = $i + 1;
			$updatelistscript[$j] = $tabdata[$i];
			$i = $i + 1;
			$updatelisttype[$j] = $tabdata[$i];
			$i = $i + 1;
		}
		$sqlexist = "SELECT * FROM crawlt_attack";
		$requeteexist = $connexion->query($sqlexist);
		while ($ligne = $requeteexist->fetch_object()) {
			$attackid = $ligne->id_attack;
			$listattack[] = $attackid;
		}
		$nbrdata = count($updatelistid);
		$nbrupdate = 0;
		
		for ($k = 1;$k <= $nbrdata;$k++) {
			$id = $updatelistid[$k];
			$attack = $updatelistattack[$k];
			$script = $updatelistscript[$k];
			$type = $updatelisttype[$k];
			if (in_array($id, $listattack)) {
			} else {
				$sqlinsert = "INSERT INTO crawlt_attack (id_attack,attack, script, type)
								VALUES ('" . crawlt_sql_quote($connexion, $id) . "','" . crawlt_sql_quote($connexion, $attack) . "','" . crawlt_sql_quote($connexion, $script) . "','" . crawlt_sql_quote($connexion, $type) . "')";
				$requeteinsert = db_query($sqlinsert, $connexion);
				$nbrupdate = $nbrupdate + 1;
				$crawlernameadd[] = $attack;
				$crawleruaadd[] = $script;
				$crawlertypeadd[] = $type;
			}
		}
		echo "<h1><br><br>$nbrupdate&nbsp;" . $language['attack_add'] . "<br></h1>";
		$sqlinsertid = "INSERT INTO crawlt_update_attack (update_id) VALUES ('" . crawlt_sql_quote($connexion, $idlist) . "')";
		$requeteinsertid = db_query($sqlinsertid, $connexion);
		
		echo "<div align='center'><table cellpadding='0px' cellspacing='0' width='750px'><tr><td class='tableau1'>" . $language['parameter'] . "</td><td class='tableau1'>" . $language['script'] . "</td><td class='tableau2'>" . $language['attack_type'] . "</td></tr>\n";
		for ($l = 0;$l < $nbrupdate;$l++) {
			$crawlnamedisplay = htmlentities($crawlernameadd[$l]);
			$crawluadisplay = htmlentities($crawleruaadd[$l]);
			$crawltypedisplay = htmlentities($crawlertypeadd[$l]);
			if ($l % 2 == 0) {
				echo "<tr><td class='tableau3'>$crawlnamedisplay</td>\n";
				echo "<td class='tableau3'>$crawluadisplay</td>\n";
				echo "<td class='tableau5'>$crawltypedisplay</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30'>$crawlnamedisplay</td>\n";
				echo "<td class='tableau30'>$crawluadisplay</td>\n";
				echo "<td class='tableau50'>$crawltypedisplay</td></tr>\n";
			}
		}
		echo "</tr></table></div><br><br>";
	}
mysqli_close($connexion);
} else {
	echo "<br><br><h1>" . $language['no_attack_list'] . "</h1><br>";
}
?>
