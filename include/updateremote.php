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
// file: updateremote.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
//initialize array
$updatelistua = array();
$updatelistname = array();
$updatelisturl = array();
$updatelistuser = array();
$listcrawler = array();
$crawlernameadd = array();
$crawleruaadd = array();

//databaseconnection
$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");

//query to get the actual liste id
$sqlupdate = "SELECT * FROM crawlt_update";
$requeteupdate = db_query($sqlupdate, $connexion);
$idlastupdate = 0;
while ($ligne = mysql_fetch_object($requeteupdate)) {
	$update = $ligne->update_id;
	if ($update > $idlastupdate) {
		$idlastupdate = $update;
	}
}
// we get the actual list number on www.crawltrack.net
$idlist = "";
$file = "";

// Try to temporarly allow url_fopen
if (ini_get('allow_url_fopen') != 1) {
	@ini_set('allow_url_fopen', '1');
}
if (ini_get('allow_url_fopen') == 1) {
	$nofile = false;
	$file = fopen("http://www.crawltrack.net/listcrawler/idlist.txt", "r");
} else {
	$nofile = true;
}
if (!$file || $nofile) {
	//no connection to the file
	echo "<br><br><h5>" . $language['no_access'] . "</h5>\n";
	echo "<h2><a href=\"http://www.crawltrack.net/crawlttest/php/countdownload.php?url=http://www.crawltrack.net/download/crawlerlist.zip\">" . $language['download'] . "</a></h2>\n";
	echo "<h5>" . $language['download_update'] . "</h5>\n";
	echo "<div class=\"form\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value='15'>\n";
	echo "<input type=\"hidden\" name ='navig' value='6'>\n";
	echo "<input name='ok' type='submit'  value='OK ' size='20'>\n";
	echo "</form>\n";
	echo "</div><br>\n";
} else {
	while (!feof($file)) {
		$data = fgets($file, 1024);
		$idlist = $idlist . $data;
	}
	fclose($file);
	if ($idlist == "") {
		//file empty
		echo "<br><br><h2>" . $language['no_access2'] . "</h2><br><br>";
	} else {
		//test to know is the crawler list is up to date.
		if ($idlist == $idlastupdate) {
			//the list is up to date
			echo "<br><br><h1>" . $language['list_up_to_date'] . "</h1><br><br>";
		} else {
			//we can update the list
			
			// we get the actual list on www.crawltrack.net
			$crawlerlist = "";
			$file2 = "";
			
			// Try to temporarly allow url_fopen
			if (ini_get('allow_url_fopen') != 1) {
				@ini_set('allow_url_fopen', '1');
			}
			if (ini_get('allow_url_fopen') == 1) {
				$nofile2 = false;
				$file2 = fopen("http://www.crawltrack.net/listcrawler/crawlerlist.txt", "r");
			} else {
				$nofile2 = true;
			}
			if (!$file2 || $nofile2) {
				//no connection to the file
				echo "<br><br><h2>" . $language['no_access2'] . "</h2><br><br>";
			} else {
				while (!feof($file2)) {
					$data2 = fgets($file2, 1024);
					$crawlerlist = $crawlerlist . $data2;
				}
				fclose($file2);
				if ($crawlerlist == "") {
					//file empty
					echo "<br><br><h2>" . $language['no_access2'] . "</h2><br><br>";
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
					$requeteexist = mysql_query($sqlexist, $connexion);
					while ($ligne = mysql_fetch_object($requeteexist)) {
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
								VALUES ('" . sql_quote($ua) . "','" . sql_quote($name) . "','" . sql_quote($url) . "','" . sql_quote($user) . "','')";
							$requeteinsert = db_query($sqlinsert, $connexion);
							$nbrupdate = $nbrupdate + 1;
							$crawlernameadd[] = $name;
							$crawleruaadd[] = $ua;
						}
					}
					echo "<h1><br><br>$nbrupdate&nbsp;" . $language['crawler_add'] . "<br></h1>";
					$sqlinsertid = "INSERT INTO crawlt_update (update_id) VALUES ('" . sql_quote($idlist) . "')";
					$requeteinsertid = db_query($sqlinsertid, $connexion);
					
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
			}
		}
	}
}
mysql_close($connexion);
?>
