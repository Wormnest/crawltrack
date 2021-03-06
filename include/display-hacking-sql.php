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
// file: display-hacking.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$listip = array();
$countrycode = array();
$nbrcountry = array();
$listcountry = array();
$totallistattack = array();
$listscript = array();
$listbadsite = array();
$totalscriptdisplay = '';
$totalattackdisplay = '';
$nbrattack404 = 0;
$onlyarchive = 0;
$ipproxy = array();
$totallistattack = array();
$listscript = array();
$nbrvisits = array();
$nbrvisits2 = array();

if ($settings->period >= 1000) {
	$cachename = "permanent-" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-".$settings->displayall . "-" . date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
} elseif ($settings->period >= 100 && $settings->period < 200) //previous month
{
	$cachename = "permanent-month" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-".$settings->displayall . "-" . date("Y-m", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} elseif ($settings->period >= 200 && $settings->period < 300) //previous year
{
	$cachename = "permanent-year" . $settings->navig . "-" . $settings->siteid . "-".$settings->language . "-".$settings->displayall . "-" . date("Y", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
} else {
	$cachename = $settings->navig . $settings->period . $settings->siteid . $settings->firstdayweek . $localday . $settings->graphpos . $settings->language . $settings->displayall;
}

//start the caching
cache($cachename);

//include menu
include ("include/menumain.php");
include ("include/menusite.php");
include ("include/timecache.php");

//mysql query-----------------------------------------------------------------------------------------------
//date for the mysql query
if ($settings->period >= 10) {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2) . "'";
} else {
	$datetolookfor = " date >'" . crawlt_sql_quote($db->connexion, $daterequest) . "'";
}
//date format
if ($settings->period == 0 || $settings->period >= 1000) {
	$datequery = "DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), '%H&nbsp;hr&nbsp;%i&nbsp;mn')";
} else {
	$datequery = "DATE_FORMAT(FROM_UNIXTIME(UNIX_TIMESTAMP(date)-($settings->timediff*3600)), '<b>%d/%m/%Y</b><br>%H&nbsp;hr&nbsp;%i&nbsp;mn')";
}
$sqlstats = "SELECT crawlt_crawler_id_crawler,  crawlt_ip_used, date,  url_page, $datequery
FROM crawlt_visits
INNER JOIN  crawlt_pages_attack
ON  crawlt_visits.crawlt_pages_id_page=crawlt_pages_attack.id_page 
WHERE $datetolookfor       
AND crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
AND crawlt_crawler_id_crawler='65501'
ORDER BY date";
$requetestats = db_query($sqlstats, $db->connexion);
$nbrresult = $requetestats->num_rows;

//query to get the error 404 attacks
if ($settings->period >= 10) {
	$sql = "SELECT count FROM crawlt_error
    WHERE  idsite='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    AND  date >='" . crawlt_sql_quote($db->connexion, $daterequestseo) . "' 
    AND  date <'" . crawlt_sql_quote($db->connexion, $daterequest2seo) . "'
    AND attacktype='65501'";
} else {
	$sql = "SELECT  count FROM crawlt_error
    WHERE  idsite='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
    AND  date >='" . crawlt_sql_quote($db->connexion, $daterequestseo) . "'
    AND attacktype='65501'";
}
$requete = db_query($sql, $db->connexion);
$num_rows = $requete->num_rows;
if ($num_rows > 0) {
	$ligne = $requete->fetch_row();
	$nbrattack404 = $ligne[0];
}
$testip = 0;
if (($nbrresult + $nbrattack404) >= 1) {
	//treatment of 1st query result
	if ($nbrresult >= 1) {
		while ($ligne = $requetestats->fetch_row()) {
			if (!empty($ligne[1])) {
				$listip[$ligne[1]] = $ligne[1];
				@$nbrvisits[$ligne[1]]++;
				${'page' . $ligne[1]}[] = $ligne[3];
				${'date' . $ligne[1]}[] = $ligne[4];
			}
		}
	}
	//query to get the country code
	if (function_exists('geoip_country_code_by_name')) {
		// The server is running a (faster) standalone version of GeoIP
		foreach ($listip as $ip) {
			$code = geoip_country_code_by_name($ip);
			if ($code !== false) {
				$code = strtolower($code);
			} else {
				$code = "xx";
			}
			$countrycode[$ip] = $code;
			@$nbrcountry[$code]++;
		}
	} else {
		// Use bundled GeoIP
		include ("geoipdatabase/geoip.inc");
		$gi = geoip_open("geoipdatabase/GeoIP.dat", GEOIP_STANDARD);
		foreach ($listip as $ip) {
			$code = strtolower(geoip_country_code_by_addr($gi, $ip));
			if ($code == "" || $code == "a1") {
				$code = "xx";
			}
			$countrycode[$ip] = $code;
			@$nbrcountry[$code]++;
		}
		geoip_close($gi);
	}
	//query to get the attack=> script infos
	$sql = "SELECT attack, script FROM crawlt_attack WHERE type='sql'";
	$requete = db_query($sql, $db->connexion);
	$nbrresult3 = $requete->num_rows;
	if ($nbrresult3 >= 1) {
		while ($ligne = $requete->fetch_row()) {
			${'attack' . $ligne[0]}[] = $ligne[1];
		}
	}
	//group by attacker-------------------------------------------------------------------------------------------
	/*definition of same attacker:
	   -same sql query with the same crawler(s) and using the same type of url (length)

	   and in the same period of time 

	   (yes it's not 100% accurate but it's the better I have till now)

	*/
	foreach ($nbrvisits as $crawlip => $value) {
		$listattack = array();
		$pagetype2 = 0;
		$pagetype3 = 0;
		foreach (${'page' . $crawlip} as $page) {
			crawltattacksql($page, $settings->useutf8);
			//give a page type1
			$pagelength = floor(strlen($page) / 10) * 10;
			if ($pagelength < 51) {
				$pagetype1[] = 1;
			} elseif ($pagelength > 50 && $pagelength < 101) {
				$pagetype1[] = 2;
			} elseif ($pagelength > 100 && $pagelength < 151) {
				$pagetype1[] = 3;
			} elseif ($pagelength > 150 && $pagelength < 201) {
				$pagetype1[] = 4;
			} elseif ($pagelength > 200 && $pagelength < 251) {
				$pagetype1[] = 5;
			} elseif ($pagelength > 250) {
				$pagetype1[] = 6;
			}
		}
		//caculate the type1 page value
		$typepagevalue = max($pagetype1);
		$pagetype = array();
		//check the time period used
		foreach (${'date' . $crawlip} as $datehacking) {
			if ($settings->period == 0 || $settings->period >= 1000) {
				//on a one day period we class per hour period
				$time = explode('hr', $datehacking);
				$tabletime[] = intval($time[0]);
			} else {
				//on more than one day period we class per day
				$time = explode('/', $datehacking);
				$time2 = explode('>', $time[0]);
				$tabletime[] = intval($time2[1]);
			}
		}
		$typeperiod = array_sum($tabletime) / count($tabletime);
		if ($settings->period == 0 || $settings->period >= 1000) {
			if ($typeperiod <= 12) {
				$typeperiodvalue = 1;
			} else {
				$typeperiodvalue = 2;
			}
		} else {
			if ($daytodaylocal > $daybeginlocal) {
				if ($typeperiod <= ((($daytodaylocal - $daybeginlocal) / 2) + $daybeginlocal)) {
					$typeperiodvalue = 1;
				} else {
					$typeperiodvalue = 2;
				}
			} else {
				if ($typeperiod <= 15) {
					$typeperiodvalue = 1;
				} else {
					$typeperiodvalue = 2;
				}
			}
		}
		$tabletime = array();
		//prepare the attacker value
		$listbadsite = array_unique($listbadsite);
		asort($listbadsite);
		$attacker = $typepagevalue . serialize($listbadsite) . $typeperiodvalue;
		//create the table of IP per attacker
		$ipproxy[$attacker] = @$ipproxy[$attacker] . "-" . $crawlip;
	}
	//count the number of attack per attacker
	foreach ($ipproxy as $crawlip) {
		$crawlip = ltrim($crawlip, "-");
		$tableauip = explode('-', $crawlip);
		$listattack = array();
		foreach ($tableauip as $ip) {
			$nbrvisits2[$crawlip] = @$nbrvisits2[$crawlip] + $nbrvisits[$ip];
		}
	}
	//prepare the list of targeted script
	$totallistattack = array_unique($totallistattack);
	usort($totallistattack, "strcasecmp");
	$nbrparameters = 0;
	foreach ($totallistattack as $attack) {
		if (isset(${'attack' . $attack})) {
			if (is_array(${'attack' . $attack})) {
				${'attack' . $attack} = array_unique(${'attack' . $attack});
				foreach (${'attack' . $attack} as $script) {
					$listscript[] = $script;
				}
			} else {
				$listscript[] = '?';
			}
		} else {
			$listscript[] = '?';
		}
		if ($attack != '?') {
			$attack = htmlentities($attack);
			$totalattackdisplay.= $attack . "<b> / </b>";
			$nbrparameters++;
		}
	}
	$listscript = array_unique($listscript);
	usort($listscript, "strcasecmp");
	$nbrscript = 0;
	foreach ($listscript as $script) {
		if ($script != '?') {
			$script = htmlentities($script);
			$totalscriptdisplay.= $script . "<b> / </b>";
			$nbrscript++;
		}
	}
	// Don't close database here since mapgraph3 also needs the connection!

	$totalscriptdisplay = rtrim($totalscriptdisplay, "<b> / </b>");
	$totalattackdisplay = rtrim($totalattackdisplay, "<b> / </b>");
	//prepare datas for the summary table
	$nbrattack = array_sum($nbrvisits2) + $nbrattack404;
	$nbrip = count($listip);

	//display---------------------------------------------------------------------------------------------------------
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	//summary table display
	echo "<div class='tableau' align='center' onmouseout=\"javascript:montre();\">\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='750px'>\n";
	echo "<tr><th class='tableau1' >\n";
	echo "" . $language['total_hacking'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1' >\n";
	echo "" . $language['error_hacking'] . "*\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['crawler_ip_used'] . "\n";
	echo "</th></tr>\n";
	echo "<tr><td class='tableau3'>" . numbdisp($nbrattack) . "</td>\n";
	echo "<td class='tableau3'>" . numbdisp($nbrattack404) . "</td>\n";
	echo "<td class='tableau5'>" . numbdisp($nbrip) . "</td></tr>\n";
	echo "</table></div>\n";
	if ($settings->blockattacks == 1) {
		echo "<h2>" . $language['attack-blocked'] . "</h2>\n";
	} else {
		echo "<h2><span class=\"alert2\">" . $language['attack-no-blocked'] . "</span></h2>\n";
	}
	if ($settings->period != 5) {
		//graph
		echo "<div class='graphvisits' >\n";
		//mapgraph
		include "include/mapgraph.php";
		echo "<img src=\"./graphs/visit-graph.php?crawltlang=$settings->language&period=$settings->period&navig=$settings->navig&graphname=$graphname\" USEMAP=\"#visit\" alt=\"graph\" width=\"700\" height=\"300\"  border=\"0\"/>\n";
		echo "</div>\n";
		echo "<div class='imprimgraph'>\n";
		echo "&nbsp;<br><br><br><br><br><br><br><br></div>\n";
	}
	echo "<p align='center'>*" . $language['404_no_in_graph'] . "</p>\n";
	if ($nbrscript != 0) {
		echo "<br><div class='tableaularge' align='center'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
		echo "<tr><td class='tableau23'>\n";
		echo "<div class='alert'><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >" . $language['danger'] . " :</div><div class='scriptdisplay'>" . $totalscriptdisplay . "</div><br>\n";
		echo "</td></tr>\n";
		echo "</table></div>\n";
	}
	if ($nbrparameters != 0) {
		echo "<br><div class='tableaularge' align='center'>\n";
		echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
		echo "<tr><td class='tableau23'>\n";
		echo "<div class='alert'><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >" . $language['attack_sql'] . " :</div><div class='scriptdisplay'>" . $totalattackdisplay . "</div><br>\n";
		echo "</td></tr>\n";
		echo "</table></div>\n";
	}
	//change text if more than x attack	and display limited (value of x can be change in function.php,,it's displaynumber)
	if ($nbrattack >= $settings->displayrows && $settings->displayall == 'no') {
		echo "<h2>";
		printf($language['attack_number_display'], $settings->displayrows);
		echo "<br>\n";
		$crawlencode = urlencode($settings->crawler);
		echo "<span class=\"smalltext\"><a href=\"index.php?navig=$settings->navig&period=$settings->period&site=$settings->siteid&crawler=$crawlencode&order=$settings->displayorder&displayall=yes&graphpos=$settings->graphpos\">" . $language['show_all'] . "</a></span></h2>";
	} else {
		echo "<h2>" . $language['attack_detail'] . "</h2>\n";
	}
	echo "<div class='tableaularge' align='center'>\n";
	echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
	echo "<tr><th class='tableau1'>\n";
	echo "" . $language['crawler_ip_used'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['hacking'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau1'>\n";
	echo "" . $language['date_hacking'] . "\n";
	echo "</th>\n";
	echo "<th class='tableau2'>\n";
	echo "" . $language['attack_detail'] . "\n";
	echo "</th></tr>\n";
	//counter for alternate color lane
	$comptligne = 2;
	//sort by number of tentatives
	arsort($nbrvisits2);
	$i = 0;
	foreach ($nbrvisits2 as $crawlip => $value) {
		if (($settings->displayall == 'no' && $i < $settings->displayrows) || $settings->displayall == 'yes') {
			$i++;
			//Initialize array & variables
			$ipdisplay = "";
			$urldisplay = "";
			$datedisplay = "";
			$crawldisplay = "";
			$badsitedisplay = "";
			$attackdisplay = "";
			$scriptdisplay = "";
			$listbadsite = array();
			$listattack = array();
			$listscript = array();
			$tabledatedisplay = array();
			$tabledatedisplay2 = array();
			$tableurldisplay = array();
			$listday = array();
			$crawlip = ltrim($crawlip, "-");
			$tableauip = explode('-', $crawlip);
			sort($tableauip);
			//prepare data for display (group by attacker)
			foreach ($tableauip as $ip) {
				//prepare details of attacks
				foreach (${'page' . $ip} as $page) {
					crawltattacksql($page, $settings->useutf8);
				}
				//prepare time of attack
				foreach (${'date' . $ip} as $datehacking) {
					$tabledatedisplay[] = $datehacking;
				}
				//prepare IP used
				if (isset($countrycode[$ip])) {
					$code = $countrycode[$ip];
					$ipdisplay.= "&nbsp;" . $ip . "&nbsp;<a href=\"http://www.whois-search.com/whois/" . $ip . "\" target=\"blank\"><span class=\"noprint\"><img src=\"./images/report_user.png\" width=\"16\" height=\"16\" border=\"0\" ></a>&nbsp;</span><br>&nbsp;<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
				} else {
					$ipdisplay.= "&nbsp;" . $ip . "&nbsp;<a href=\"http://www.whois-search.com/whois/" . $ip . "\" target=\"blank\"><span class=\"noprint\"><img src=\"./images/report_user.png\" width=\"16\" height=\"16\" border=\"0\" ></a>&nbsp;</span><br>&nbsp;????<br>\n";
				}
			}
			$tabledatedisplay = array_unique($tabledatedisplay);
			sort($tabledatedisplay);
			//to group per day when not 1 day period
			if ($settings->period == 0 || $settings->period >= 1000) {
				$datedisplay = implode("<br>", $tabledatedisplay);
			} else {
				foreach ($tabledatedisplay as $datehacking) {
					$day = explode("<br>", $datehacking);
					if (in_array($day[0], $listday)) {
						$tabledatedisplay2[] = $day[1];
					} else {
						$tabledatedisplay2[] = $datehacking;
					}
					$listday[] = $day[0];
				}
				$datedisplay = implode("<br>", $tabledatedisplay2);
			}
			$tableurldisplay = array_unique($tableurldisplay);
			sort($tableurldisplay);
			$urldisplay = implode("<br>--------------------------------------------------------------------------------------------<br>", $tableurldisplay);
			$listbadsite = array_unique($listbadsite);
			$listattack = array_unique($listattack);
			foreach ($listbadsite as $badsite) {
				$badsitedisplay.= crawltcuturl(urldecode($badsite), '75', $settings->useutf8) . "<br>";
			}
			$firsttime = 0;
			usort($listattack, "strcasecmp");
			foreach ($listattack as $attack) {
				if (isset(${'attack' . $attack})) {
					if (is_array(${'attack' . $attack})) {
						${'attack' . $attack} = array_unique(${'attack' . $attack});
						foreach (${'attack' . $attack} as $script) {
							$listscript[] = $script;
						}
					} elseif ($firsttime == 0) {
						$listscript[] = '?';
						$firsttime = 1;
					}
				} elseif ($firsttime == 0) {
					$listscript[] = '?';
					$firsttime = 1;
				}
				if (!empty($attack)) {
					$attackdisplay.= crawltcuturl($attack, '75', $settings->useutf8) . "<br>";
				}
			}
			$listscript = array_unique($listscript);
			usort($listscript, "strcasecmp");
			$detect = false;
			foreach ($listscript as $script) {
				if ($script == '?') {
					$detect = true;
				} else {
					$scriptdisplay.= crawltcuturl($script, '75', $settings->useutf8) . "<br>";
				}
			}
			if ($detect) {
				$scriptdisplay.= "?<br>";
			}
			//table display
			if ($comptligne % 2 == 0) {
				echo "<tr><td class='tableau3hsg'>$ipdisplay</td> \n";
				echo "<td class='tableau3hsf' >$nbrvisits2[$crawlip]</td>\n";
				echo "<td class='tableau3hsf' width='10%'>" . $datedisplay . "</td>\n";
				echo "<td id='tableau5vsf' width='50%'><b><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >" . $language['danger'] . ": </b><br>" . $scriptdisplay . "<br><b>" . $language['attack_sql'] . ": </b><br>" . $attackdisplay . "<br><b>" . $language['bad_sql'] . ": </b><br>" . $badsitedisplay . "<br><b>" . $language['bad_url'] . ":</b><br><a href=\"#\">" . $urldisplay . "</a><br>&nbsp;</td></tr>\n";
			} else {
				echo "<tr><td class='tableau30hsg'>$ipdisplay</td> \n";
				echo "<td class='tableau30hsf' >$nbrvisits2[$crawlip]</td>\n";
				echo "<td class='tableau30hsf' width='10%'>" . $datedisplay . "</td>\n";
				echo "<td id='tableau50vsf' width='50%'><b><img src=\"./images/error.png\" width=\"16\" height=\"16\" border=\"0\" >" . $language['danger'] . ": </b><br>" . $scriptdisplay . "<br><b>" . $language['attack_sql'] . ": </b><br>" . $attackdisplay . "<br><b>" . $language['bad_sql'] . ": </b><br>" . $badsitedisplay . "<br><b>" . $language['bad_url'] . ":</b><br><a href=\"#\">" . $urldisplay . "</a><br>&nbsp;</td></tr>\n";
			}
			$comptligne++;
		}
	}
	echo "</table>\n";
	echo "<br>\n";
} else
//case no visits
{
	echo "<div class=\"content2\"><br><hr>\n";
	echo "</div>\n";
	echo "<div class='tableaularge' align='center'>\n";
	echo "<h1>" . $language['no_hacking'] . "</h1>\n";
	echo "<br>\n";
}
$db->close(); // Close database
?>
