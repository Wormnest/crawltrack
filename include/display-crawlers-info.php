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
// file: display-crawlers-info.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listcrawler = array();
$listip = array();
$usercrawler = array();
$countrycode = array();
$nbrcountry = array();
$listcountry = array();
$name = array();
$nbrcountry2 = array();
$name2 = array();
$values = array();

if ($settings->period >= 1000) {
	$cachename = "permanent-" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($settings->period >= 100 && $settings->period < 200) //previous month
{
	$cachename = "permanent-month" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($settings->period >= 200 && $settings->period < 300) //previous year
{
	$cachename = "permanent-year" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language;
}

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//mysql query
if ($settings->period >= 10) {
	$sqlstats = "SELECT crawler_name, crawlt_ip_used, date, crawler_info
    FROM crawlt_visits
    INNER JOIN crawlt_crawler
    ON  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    WHERE crawlt_visits.date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'     
    AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
} else {
	$sqlstats = "SELECT crawler_name, crawlt_ip_used, date, crawler_info
    FROM crawlt_visits
    INNER JOIN crawlt_crawler
    ON  crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
    WHERE crawlt_visits.date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'";
}
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult1 = $requetestats->num_rows;
$testip = false;
if ($nbrresult1 >= 1) {
	while ($ligne = $requetestats->fetch_row()) {
		if (!empty($ligne[1])) {
			$testip = true;
			if ($ligne[0] == 'MSN Bot' || $ligne[0] == 'Bingbot') {
				$ligne[0]='MSN Bot - Bingbot';
			}
			$listcrawler[$ligne[0]] = $ligne[0];
			${'ipcrawler' . $ligne[0]}[] = $ligne[1];
			$listip[$ligne[1]] = $ligne[1];
			@${'nbrvisits' . $ligne[0]}[$ligne[1]]++;
			${'crawler' . $ligne[1]}[] = $ligne[0];
			$usercrawler[$ligne[0]] = $ligne[3];
		}
	}
	mysqli_free_result($requetestats);
	if ($testip) {
		//query to get the country code
		if (function_exists('geoip_country_code_by_name')) {
			// The server is running a (faster) standalone version of GeoIP
			foreach ($listip as $ip) {
				$codeip = "code-" . $ip;
				if (isset($_SESSION[$codeip])) {
					$countrycode[$ip] = $_SESSION[$codeip];
					@$nbrcountry[$_SESSION[$codeip]]++;
				} else {
					$code = geoip_country_code_by_name($ip);
					if ($code !== false) {
						$code = strtolower($code);
					} else {
						$code = "xx";
					}
					$countrycode[$ip] = $code;
					@$nbrcountry[$code]++;
					$_SESSION[$codeip] = $code;
				}
			}
		} else {
			// Use included GeoIP
			include ("geoipdatabase/geoip.inc");
			$gi = geoip_open("geoipdatabase/GeoIP.dat", GEOIP_STANDARD);
			foreach ($listip as $ip) {
				$codeip = "code-" . $ip;
				if (isset($_SESSION[$codeip])) {
					$countrycode[$ip] = $_SESSION[$codeip];
					@$nbrcountry[$_SESSION[$codeip]]++;
				} else {
					$code = strtolower(geoip_country_code_by_addr($gi, $ip));
					if ($code == "" || $code == "a1") {
						$code = "xx";
					}
					$countrycode[$ip] = $code;
					@$nbrcountry[$code]++;
					$_SESSION[$codeip] = $code;
				}
			}
			geoip_close($gi);
		}
		//treatment to prepare the datas for the graph and to display the 5 top and group the other in the 'Other' category
		arsort($nbrcountry);
		foreach ($nbrcountry as $key => $value) {
			$name[] = $key;
		}
		$nbrtotcountry = count($nbrcountry);
		$i = 0;
		foreach ($nbrcountry as $nbr) {
			if ($i > 4 && $nbrtotcountry > 6) {
				$crawlername = $name[$i];
				$crawlerother = 'other';
				@$nbrcountry2[$crawlerother] = @$nbrcountry2[$crawlerother] + $nbrcountry[$crawlername];
			} else {
				$crawlername = $name[$i];
				@$nbrcountry2[$crawlername] = $nbrcountry[$crawlername];
			}
			$i++;
		}
		foreach ($nbrcountry2 as $key => $value) {
			$name2[] = $key;
		}
		$i = 0;
		foreach ($nbrcountry2 as $nbr2) {
			if ($name2[$i] == 'other') {
				$values['other'] = $nbr2;
			} else {
				$values[$name2[$i]] = $nbr2;
			}
			$i++;
		}
		//prepare data to be transferred to graph file
		$datatransferttograph = addslashes(urlencode(serialize($values)));
		//insert the values in the graph table
		$graphname = "origin-" . $cachename;
		//check if this graph already exists in the table
		$sql = "SELECT name  FROM crawlt_graph
                WHERE name= '" . crawlt_sql_quote($db->connexion, $graphname) . "'";
		$requete = db_query($sql, $db->connexion);
		$nbrresult = $requete->num_rows;
		if ($nbrresult >= 1) {
			$sql2 = "UPDATE crawlt_graph SET graph_values='" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "'
                  WHERE name= '" . crawlt_sql_quote($db->connexion, $graphname) . "'";
		} else {
			$sql2 = "INSERT INTO crawlt_graph (name, graph_values) VALUES ( '" . crawlt_sql_quote($db->connexion, $graphname) . "','" . crawlt_sql_quote($db->connexion, $datatransferttograph) . "')";
		}
		$requete2 = db_query($sql2, $db->connexion);
		$db->close(); // Close database

		//display---------------------------------------------------------------------------------------------------------
		echo "<div class=\"content2\"><br><hr>\n";
		echo "</div>\n";
		//graph
		echo "<div align='center'onmouseover=\"javascript:montre();\">\n";
		echo "<img src=\"./graphs/origine-graph.php?graphname=$graphname&amp;crawltlang=$settings->language\" alt=\"graph\"  width=\"450px\" height=\"200px\"/>\n";
		echo "</div>\n";

		//order per crawler name
		asort($listcrawler);
		echo "<div class='tableau' align='center'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
		echo "<tr><th class='tableau1'>\n";
		echo "" . $language['crawler_name'] . "\n";
		echo "</th>\n";
		echo "<th class='tableau1'>\n";
		echo "" . $language['crawler_ip_used'] . "\n";
		echo "</th>\n";
		echo "<th class='tableau1'>\n";
		echo "" . $language['nbr_visits'] . "\n";
		echo "</th>\n";
		echo "<th class='tableau2'>\n";
		echo "" . $language['crawler_country'] . "\n";
		echo "</th></tr>\n";

		//counter for alternate color lane
		$comptligne = 2;
		foreach ($listcrawler as $crawl) {
			$crawldisplay = htmlentities($crawl);
			//suppression of double entries in the tables
			${'ipcrawler' . $crawl} = array_unique(${'ipcrawler' . $crawl});
			sort(${'ipcrawler' . $crawl});
			if ($comptligne % 2 == 0) {
				$crawlencode = urlencode($crawl);
				echo "<tr><td class='tableau3h'><a href='index.php?navig=2&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "'>" . $crawldisplay . "</a></td>\n";
				echo "<td class='tableau3g' width='20%'>\n";
				foreach (${'ipcrawler' . $crawl} as $ip) {
					$nbip = count(${'crawler' . $ip});
					if ($nbip > 1 && $nbrresult1 < 30000) {
						//test to see in case of different crawlers using the same ip if the owner is the same only if there is less
						//than 30000 hit to avoid script memory issue
						for ($i = 0;$i < $nbip;$i++) {
							${'difuser' . $ip}[] = $usercrawler[${'crawler' . $ip}[$i]];
						}
						${'difuser' . $ip} = array_unique(${'difuser' . $ip});
						$nbuser = count(${'difuser' . $ip});
						if ($nbuser > 1) {
							$teststrangeip = true;
						} else {
							$teststrangeip = false;
						}
					} else {
						$teststrangeip = false;
					}
					if ($teststrangeip) {
						echo "&nbsp;&nbsp;&nbsp;<span class='red'>$ip&nbsp;<a href='index.php?navig=6&amp;iptosuppress=" . $ip . "&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;validform=19&amp;suppressip=1&amp;graphpos=" . $settings->graphpos . "'>???</a></span><br>\n";
					} else {
						echo "&nbsp;&nbsp;&nbsp;$ip<br>\n";
					}
				}
				echo "</td>\n";
				echo "<td class='tableau3' >\n";
				foreach (${'ipcrawler' . $crawl} as $ip) {
					echo "" . numbdisp(${'nbrvisits' . $crawl}[$ip]) . "<br>\n";
				}
				echo "</td>\n";
				echo "<td class='tableau5g' width='25%'>\n";
				foreach (${'ipcrawler' . $crawl} as $ip) {
					if (isset($countrycode[$ip])) {
						$code = $countrycode[$ip];
						echo "&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
					} else {
						echo "&nbsp;&nbsp;&nbsp;????<br>\n";
					}
				}
				echo "</td></tr> \n";
			} else {
				$crawlencode = urlencode($crawl);
				echo "<tr><td class='tableau30h'><a href='index.php?navig=2&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "&amp;graphpos=" . $settings->graphpos . "'>" . $crawldisplay . "</a></td>\n";
				echo "<td class='tableau30g' width='20%'>\n";
				foreach (${'ipcrawler' . $crawl} as $ip) {
					$nbip = count(${'crawler' . $ip});
					if ($nbip > 1 && $nbrresult1 < 30000) {
						//test to see in case of different crawlers using the same ip if the owner is the same
						for ($i = 0; $i < $nbip; $i++) {
							${'difuser' . $ip}[] = $usercrawler[${'crawler' . $ip}[$i]];
						}
						${'difuser' . $ip} = array_unique(${'difuser' . $ip});
						$nbuser = count(${'difuser' . $ip});
						if ($nbuser > 1) {
							$teststrangeip = 1;
						} else {
							$teststrangeip = 0;
						}
					} else {
						$teststrangeip = 0;
					}
					if ($teststrangeip == 1) {
						echo "&nbsp;&nbsp;&nbsp;<span class='red'>$ip&nbsp;<a href='index.php?navig=6&amp;iptosuppress=" . $ip . "&amp;period=" . $settings->period . "&amp;site=" . $settings->siteid . "&amp;validform=19&amp;suppressip=1&amp;graphpos=" . $settings->graphpos . "'>???</a></span><br>\n";
					} else {
						echo "&nbsp;&nbsp;&nbsp;$ip<br>\n";
					}
				}
				echo "</td>\n";
				echo "<td class='tableau30' >\n";
				foreach (${'ipcrawler' . $crawl} as $ip) {
					echo "" . numbdisp(${'nbrvisits' . $crawl}[$ip]) . "<br>\n";
				}
				echo "</td>\n";
				echo "<td class='tableau50g' width='25%'>\n";
				foreach (${'ipcrawler' . $crawl} as $ip) {
					if (isset($countrycode[$ip])) {
						$code = $countrycode[$ip];
						echo "&nbsp;&nbsp;&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
					} else {
						echo "&nbsp;&nbsp;&nbsp;????<br>\n";
					}
				}
				echo "</td></tr> \n";
			}
			$comptligne++;
		}
		echo "</table>\n";
		echo "<br>\n";
		echo "<p align='center'><span class='smalltext'>" . $language['maxmind'] . " <a href='http://maxmind.com'>http://maxmind.com</a></span></p>\n";
	} else {
		//case no ip in the visit table (upgrade to 1.50)
		echo "<div class=\"content2\"><br><hr>\n";
		echo "<h1>" . $language['no_ip'] . "</h1>\n";
		echo "<br>\n";
	}
} else
//case no visits
{
	echo "<div class=\"content2\"><br><hr>\n";
	echo "<h1>" . $language['no_visit'] . "</h1>\n";
	echo "<br>\n";
}
?>
