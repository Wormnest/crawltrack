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
// file: search.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$list = array();

//include menu
include ("include/menumain.php");
include ("include/menusite.php");

echo "<div class=\"content\">\n";

//test if form valid
if ($settings->crawler == "" && $settings->validform == 1) {
	$settings->validform = 0;
}

//test form for navigation
if ($settings->validform == 0) {
	if ($settings->crawler == 0) {
		$settings->crawler = "";
	}
	$crawlencode = urlencode($settings->crawler);
	echo "<h1>" . $language['search2'] . "</h1>\n";
	echo "<table width=\"720px\" align=\"center\">\n";
	echo "<tr><td>\n";
	echo "<div class=\"form2\" align=\"centrer\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"1\">";
	echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">";
	echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">";
	echo "<table align=\"centrer\" width=\"300px\">\n";
	echo "<tr>\n";
	echo "<td><h1>" . $language['search_crawler'] . "</h1></td></tr>\n";
	echo "<tr><td align='center'>" . $language['crawler_name'] . ":<input name='crawler'  value='$crawlencode' type='text' size='20'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='center'>\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['go_search'] . " ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form></div>\n";
	echo "</td><td>\n";
	echo "<div class=\"form2\" align=\"centrer\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"2\">";
	echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">";
	echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">";
	echo "<table align=\"centrer\" width=\"300px\">\n";
	echo "<tr>\n";
	echo "<td><h1>" . $language['search_page'] . "</h1></td></tr>\n";
	echo "<tr><td align='center'>" . $language['page'] . ":<input name='crawler'  value='$crawlencode' type='text' size='20'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='center'>\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['go_search'] . " ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form></div>\n";
	echo "</td></tr><tr><td>&nbsp;</td></tr><tr><td>\n";
	echo "<div class=\"form2\" align=\"centrer\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"5\">";
	echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">";
	echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">";
	echo "<table align=\"centrer\" width=\"300px\">\n";
	echo "<tr>\n";
	echo "<td><h1>" . $language['search_user_agent'] . "</h1></td></tr>\n";
	echo "<tr><td align='center'>" . $language['crawler_user_agent'] . "<input name='crawler'  value='$crawlencode' type='text' size='20'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td align='center'>\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['go_search'] . " ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form></div>\n";
	echo "</td><td>\n";
	echo "<div class=\"form2\" align=\"centrer\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"3\">";
	echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">";
	echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">";
	echo "<table align=\"centrer\" width=\"300px\">\n";
	echo "<tr>\n";
	echo "<td><h1>" . $language['search_user'] . "</h1></td></tr>\n";
	echo "<tr><td align='center'>" . $language['Origin'] . ":<input name='crawler'  value='$crawlencode' type='text' size='20'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td  align='center'>\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['go_search'] . " ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form></div>\n";
	echo "</td></tr><tr><td>&nbsp;</td></tr><tr><td colspan=\"2\">\n";
	echo "<div class=\"form2\" align=\"centrer\">\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='validform' value=\"1\">";
	echo "<input type=\"hidden\" name ='navig' value=\"5\">";
	echo "<input type=\"hidden\" name ='search' value=\"7\">";
	echo "<input type=\"hidden\" name ='site' value=\"$settings->siteid\">";
	echo "<input type=\"hidden\" name ='period' value=\"$settings->period\">";
	echo "<table align=\"centrer\" width=\"300px\">\n";
	echo "<tr>\n";
	echo "<td><h1>" . $language['search_ip'] . "</h1></td></tr>\n";
	echo "<tr><td align='center'>" . $language['crawler_ip'] . "<input name='crawler'  value='$crawlencode' type='text' size='20'/></td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td  align='center'>\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' " . $language['go_search'] . " ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form></div>\n";
	echo "</td></tr></table><br><br>\n";
} else {
	if ($settings->searchtype == 7) {
		//test to see if the IP address is correct
		$modele = '/^\b(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\b$/';
		$settings->crawler = strtolower($settings->crawler);
		if (preg_match($modele, $settings->crawler)) {
			$validaddress = true;
		} else {
			$validaddress = false;
		}
		$crawlencode = urlencode($settings->crawler);
		if (!$validaddress) {
			echo "<h1>" . $language['search_ip'] . "</h1><br><br>\n";
			echo "<p>" . $language['ip_no_ok'] . "</p><br><br>\n";
			
			//continue
			echo "<form action=\"index.php\" method=\"POST\" >\n";
			echo "<input type=\"hidden\" name ='navig' value='5'>\n";
			echo "<input type=\"hidden\" name ='crawler' value='$crawlencode'>\n";
			echo "<table class=\"centrer\">\n";
			echo "<tr>\n";
			echo "<td colspan=\"2\">\n";
			echo "<input name='ok' type='submit'  value='OK ' size='20'>\n";
			echo "</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</form><br>\n";
		} else {
			//ip search
			$ipexplode = explode('.', $settings->crawler);
			if ($ipexplode[0] > 255 || $ipexplode[1] > 255 || $ipexplode[2] > 255 || $ipexplode[3] > 255) {
				echo "<h1>" . $language['search_ip'] . "</h1><br><br>\n";
				echo "<p>" . $language['ip_no_ok'] . "</p><br><br>\n";
				
				//continue
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='navig' value='5'>\n";
				echo "<input type=\"hidden\" name ='crawler' value='$crawlencode'>\n";
				echo "<table class=\"centrer\">\n";
				echo "<tr>\n";
				echo "<td colspan=\"2\">\n";
				echo "<input name='ok' type='submit'  value='OK ' size='20'>\n";
				echo "</td>\n";
				echo "</tr>\n";
				echo "</table>\n";
				echo "</form><br>\n";
			} else {
				//query to get the country code
				if (function_exists('geoip_country_code_by_name')) {
					// The server is running a (faster) standalone version of GeoIP
					$code = geoip_country_code_by_name($ip);
					if ($code !== false) {
						$code = strtolower($code);
					} else {
						$code = "xx";
					}
				} else {
					// Use bundled GeoIP
					include ("geoipdatabase/geoip.inc");
					$gi = geoip_open("geoipdatabase/GeoIP.dat", GEOIP_STANDARD);
					$code = strtolower(geoip_country_code_by_addr($gi, $settings->crawler));
					if ($code == "") {
						$code = "xx";
					}
					geoip_close($gi);
				}
				
				$crawlerdisplay = htmlentities($settings->crawler);
				echo "<h1>" . $language['search_ip'] . "</h1><br><br>\n";
				echo "<div class='tableau' align='center'>\n";
				echo "<table   cellpadding='0px' cellspacing='0' width='100%'>\n";
				echo "<tr><th class='tableau1'>\n";
				echo "" . $language['ip'] . "\n";
				echo "</th>\n";
				echo "<th class='tableau2'>\n";
				echo "" . $language['crawler_country'] . "\n";
				echo "</th></tr>\n";
				echo "<td class='tableau3'>" . $crawlerdisplay . "</td>\n";
				echo "<td class='tableau5'>\n";
				echo "<img src=\"./images/flags/$code.gif\" width=\"16px\" height=\"11px\"  border=\"0\" alt=\"$country[$code]\">&nbsp;&nbsp;$country[$code]<br>\n";
				echo "</td></tr> \n";
				echo "</table></div><br>\n";
				echo "<p align='center'><span class='smalltext'>" . $language['maxmind'] . " <a href='http://maxmind.com'>http://maxmind.com</a></span></p>\n";
			}
		}
	} else {
		//mysql query
		if ($settings->searchtype != 2) {
			//case crawler, we search in the whole crawler database
			$sqlstats = "SELECT crawler_name, crawler_info, crawler_user_agent 
				FROM crawlt_crawler
				ORDER BY crawler_name ASC";
		} else {
			//case page, we search in the visit database
			$sqlstats = "SELECT crawler_name, crawler_info, crawler_user_agent, url_page 
				FROM crawlt_visits
				INNER JOIN crawlt_crawler
				ON crawlt_visits.crawlt_crawler_id_crawler=crawlt_crawler.id_crawler
				INNER JOIN crawlt_pages  
				ON crawlt_visits.crawlt_pages_id_page=crawlt_pages.id_page 
				WHERE crawlt_visits.crawlt_site_id_site='" . crawlt_sql_quote($db->connexion, $settings->siteid) . "'
				ORDER BY crawlt_visits.date ASC";
		}
		$requetestats = db_query($sqlstats, $db->connexion);
		$nbrresult = $requetestats->num_rows;
		if ($nbrresult >= 1) {
			if ($settings->searchtype == 1) {
				while ($line = $requetestats->fetch_row()) {
					$crawlername = $line[0];
					if (preg_match('#' . $settings->crawler . '#i', $crawlername)) {
						$list[] = $crawlername;
					}
				}
				//cut the url to avoid oversize display
				$crawldisplaylength = strlen("$settings->crawler");
				$cutvalue = 0;
				$crawlerdisplay = '';
				while ($cutvalue <= $crawldisplaylength) {
					$cutvalue2 = $cutvalue + 55;
					$crawlerdisplay = $crawlerdisplay . htmlentities(substr($settings->crawler, $cutvalue, 55));
					if ($cutvalue2 <= $crawldisplaylength) {
						$crawlerdisplay = $crawlerdisplay . '<br>';
					}
					$cutvalue = $cutvalue2;
				}
				echo "<br><br><h1>" . $language['search2'] . "</h1>\n";
				echo "<h1>" . $language['search_crawler'] . "</h1>\n";
				echo "<h2>" . $language['result_crawler_1'] . "" . $crawlerdisplay . "</h2><br>\n";
				if (isset($list)) {
					$list = array_unique($list);
					sort($list);
					
					//change text if more than 100 answers
					$nbrtotanswer = sizeof($list);
					if ($nbrtotanswer > 100) {
						echo "<br><br><h2>" . $language['to_many_answer'] . "</h2>\n";
					}
					echo "<div class='tableau' align='center'>";
					echo "<table   cellpadding='0px' cellspacing='0' width='450px'>\n";
					echo "<tr><td class='tableau2'>" . $language['result_crawler'] . "</td><tr>\n";
					
					//counter for alternate color lane
					$comptligne = 2;
					//counter to limite number of datas displayed
					$comptdata = 0;
					foreach ($list as $crawl) {
						//cut the url to avoid oversize display
						$crawldisplaylength = strlen("$crawl");
						$cutvalue = 0;
						$crawldisplay = '';
						while ($cutvalue <= $crawldisplaylength) {
							$cutvalue2 = $cutvalue + 80;
							$crawldisplay = $crawldisplay . htmlentities(substr($crawl, $cutvalue, 80));
							if ($cutvalue2 <= $crawldisplaylength) {
								$crawldisplay = $crawldisplay . '<br>';
							}
							$cutvalue = $cutvalue2;
						}
						if ($comptdata < 100) {
							$crawlencode = urlencode($crawl);
							if ($comptligne % 2 == 0) {
								echo "<tr><td class='tableau5'><a href='index.php?navig=2&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "'>" . $crawldisplay . "</a></td><tr>\n";
							} else {
								echo "<tr><td class='tableau50'><a href='index.php?navig=2&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "'>" . $crawldisplay . "</a></td><tr>\n";
							}
						}
						$comptligne++;
						$comptdata++;
					}
					echo "</table></div><br>";
				} else {
					echo "<br><br><h2>" . $language['no_answer'] . "</h2>\n";
				}
			} elseif ($settings->searchtype == 2) {
				while ($line = $requetestats->fetch_row()) {
					$pagename = $line[3];
					if (preg_match('#' . $settings->crawler . '#i', $pagename)) {
						$list[] = $pagename;
					}
				}
				
				//cut the url to avoid oversize display
				$crawldisplaylength = strlen("$settings->crawler");
				$cutvalue = 0;
				$crawlerdisplay = '';
				while ($cutvalue <= $crawldisplaylength) {
					$cutvalue2 = $cutvalue + 55;
					$crawlerdisplay = $crawlerdisplay . htmlentities(substr($settings->crawler, $cutvalue, 55));
					if ($cutvalue2 <= $crawldisplaylength) {
						$crawlerdisplay = $crawlerdisplay . '<br>';
					}
					$cutvalue = $cutvalue2;
				}
				echo "<br><br><h1>" . $language['search2'] . "</h1>\n";
				echo "<h1>" . $language['search_page'] . "</h1>\n";
				echo "<h2>" . $language['result_crawler_1'] . "" . $crawlerdisplay . "</h2><br>\n";
				if (isset($list)) {
					$list = array_unique($list);
					sort($list);
					
					//change text if more than 100 answers
					$nbrtotanswer = sizeof($list);
					if ($nbrtotanswer > 100) {
						echo "<br><br><h2>" . $language['to_many_answer'] . "</h2>\n";
					}
					echo "<div class='tableau' align='center'>";
					echo "<table   cellpadding='0px' cellspacing='0' width='450px'>\n";
					echo "<tr><td class='tableau2'>" . $language['result_page'] . "</td><tr>\n";
					
					//counter for alternate color lane
					$comptligne = 2;
					//counter to limite number of datas displayed
					$comptdata = 0;
					foreach ($list as $crawl) {
						//cut the url to avoid oversize display
						$crawldisplaylength = strlen("$crawl");
						$cutvalue = 0;
						$crawldisplay = '';
						while ($cutvalue <= $crawldisplaylength) {
							$cutvalue2 = $cutvalue + 80;
							$crawldisplay = $crawldisplay . htmlentities(substr($crawl, $cutvalue, 80));
							if ($cutvalue2 <= $crawldisplaylength) {
								$crawldisplay = $crawldisplay . '<br>';
							}
							$cutvalue = $cutvalue2;
						}
						$crawlencode = urlencode($crawl);
						if ($comptdata < 100) {
							if ($comptligne % 2 == 0) {
								echo "<tr><td class='tableau5'><a href='index.php?navig=4&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "'>" . $crawldisplay . "</a></td><tr>\n";
							} else {
								echo "<tr><td class='tableau50'><a href='index.php?navig=4&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawlencode . "'>" . $crawldisplay . "</a></td><tr>\n";
							}
						}
						$comptligne++;
						$comptdata++;
					}
					echo "</table></div><br>";
				} else {
					echo "<br><br><h2>" . $language['no_answer'] . "</h2>\n";
				}
			} elseif ($settings->searchtype == 3) {
				while ($line = $requetestats->fetch_row()) {
					$crawlerinfo = $line[1];
					if (preg_match('#' . $settings->crawler . '#i', $crawlerinfo)) {
						$list[] = $crawlerinfo;
					}
				}
				
				//cut the url to avoid oversize display
				$crawldisplaylength = strlen("$settings->crawler");
				$cutvalue = 0;
				$crawlerdisplay = '';
				while ($cutvalue <= $crawldisplaylength) {
					$cutvalue2 = $cutvalue + 55;
					$crawlerdisplay = $crawlerdisplay . htmlentities(substr($settings->crawler, $cutvalue, 55));
					if ($cutvalue2 <= $crawldisplaylength) {
						$crawlerdisplay = $crawlerdisplay . '<br>';
					}
					$cutvalue = $cutvalue2;
				}
				echo "<br><br><h1>" . $language['search2'] . "</h1>\n";
				echo "<h1>" . $language['search_user'] . "</h1>\n";
				echo "<h2>" . $language['result_crawler_1'] . "" . $crawlerdisplay . "</h2><br>\n";
				if (isset($list)) {
					$list = array_unique($list);
					sort($list);
					
					//change text if more than 100 answers
					$nbrtotanswer = sizeof($list);
					if ($nbrtotanswer > 100) {
						echo "<br><br><h2>" . $language['to_many_answer'] . "</h2>\n";
					}
					echo "<div class='tableau' align='center'>";
					echo "<table   cellpadding='0px' cellspacing='0' width='450px'>\n";
					echo "<tr><td class='tableau2'>" . $language['result_user'] . "</td><tr>\n";
					
					//counter for alternate color lane
					$comptligne = 2;
					//counter to limite number of datas displayed
					$comptdata = 0;
					foreach ($list as $crawl) {
						//cut the url to avoid oversize display
						$crawldisplaylength = strlen("$crawl");
						$cutvalue = 0;
						$crawldisplay = '';
						while ($cutvalue <= $crawldisplaylength) {
							$cutvalue2 = $cutvalue + 80;
							$crawldisplay = $crawldisplay . htmlentities(substr($crawl, $cutvalue, 80));
							if ($cutvalue2 <= $crawldisplaylength) {
								$crawldisplay = $crawldisplay . '<br>';
							}
							$cutvalue = $cutvalue2;
						}
						if ($comptdata < 100) {
							$crawl2 = urlencode($crawl);
							if ($comptligne % 2 == 0) {
								echo "<tr><td class='tableau5'><a href='index.php?validform=1&amp;search=4&amp;navig=5&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawl2 . "'>" . $crawldisplay . "</a></td><tr>\n";
							} else {
								echo "<tr><td class='tableau50'><a href='index.php?validform=1&amp;search=4&amp;navig=5&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawl2 . "'>" . $crawldisplay . "</a></td><tr>\n";
							}
						}
						$comptligne++;
						$comptdata++;
					}
					echo "</table></div><br>";
				} else {
					echo "<br><br><h2>" . $language['no_answer'] . "</h2>\n";
				}
			} elseif ($settings->searchtype == 5) {
				while ($line = $requetestats->fetch_row()) {
					$crawlerua2 = $line[2];
					if (preg_match('#' . $settings->crawler . '#i', $crawlerua2)) {
						$list[] = $crawlerua2;
					}
				}
				
				//cut the url to avoid oversize display
				$crawldisplaylength = strlen("$settings->crawler");
				$cutvalue = 0;
				$crawlerdisplay = '';
				while ($cutvalue <= $crawldisplaylength) {
					$cutvalue2 = $cutvalue + 80;
					$crawlerdisplay = $crawlerdisplay . htmlentities(substr($settings->crawler, $cutvalue, 80));
					if ($cutvalue2 <= $crawldisplaylength) {
						$crawlerdisplay = $crawlerdisplay . '<br>';
					}
					$cutvalue = $cutvalue2;
				}
				echo "<br><br><h1>" . $language['search2'] . "</h1>\n";
				echo "<h1>" . $language['search_user_agent'] . "</h1>\n";
				echo "<h2>" . $language['result_crawler_1'] . "" . $crawlerdisplay . "</h2><br>\n";
				if (isset($list)) {
					$list = array_unique($list);
					sort($list);
					
					//change text if more than 100 answers
					$nbrtotanswer = sizeof($list);
					if ($nbrtotanswer > 100) {
						echo "<br><br><h2>" . $language['to_many_answer'] . "</h2>\n";
					}
					echo "<div class='tableau' align='center'>";
					echo "<table   cellpadding='0px' cellspacing='0' width='450px'>\n";
					echo "<tr><td class='tableau2'>" . $language['result_ua'] . "</td><tr>\n";
					
					//counter for alternate color lane
					$comptligne = 2;
					//counter to limite number of datas displayed
					$comptdata = 0;
					foreach ($list as $crawl) {
						//cut the url to avoid oversize display
						$crawldisplaylength = strlen("$crawl");
						$cutvalue = 0;
						$crawldisplay = '';
						while ($cutvalue <= $crawldisplaylength) {
							$cutvalue2 = $cutvalue + 80;
							$crawldisplay = $crawldisplay . htmlentities(substr($crawl, $cutvalue, 80));
							if ($cutvalue2 <= $crawldisplaylength) {
								$crawldisplay = $crawldisplay . '<br>';
							}
							$cutvalue = $cutvalue2;
						}
						if ($comptdata < 100) {
							$crawl2 = urlencode($crawl);
							if ($comptligne % 2 == 0) {
								echo "<tr><td class='tableau5'><a href='index.php?validform=1&amp;search=6&amp;navig=5&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawl2 . "'>" . $crawldisplay . "</a></td><tr>\n";
							} else {
								echo "<tr><td class='tableau50'><a href='index.php?validform=1&amp;search=6&amp;navig=5&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawl2 . "'>" . $crawldisplay . "</a></td><tr>\n";
							}
						}
						$comptligne++;
						$comptdata++;
					}
					echo "</table></div><br>";
				} else {
					echo "<br><br><h2>" . $language['no_answer'] . "</h2>\n";
				}
			} elseif ($settings->searchtype == 6) {
				$sqlexist = "SELECT crawler_name,crawler_user_agent, crawler_info, crawler_url FROM crawlt_crawler
					WHERE crawler_user_agent='" . crawlt_sql_quote($db->connexion, $settings->crawler) . "'";
				$requeteexist = db_query($sqlexist, $db->connexion);
				$line2 = $requeteexist->fetch_row();
				
				//crawler already exist
				$crawlernamedisplay = htmlentities($line2[0]);
				$useragdisplay = htmlentities($line2[1]);
				$crawlerinfodisplay = htmlentities($line2[2]);
				$crawlerurldisplay = htmlentities($line2[3]);
				echo "<br><br><h1>" . $language['search2'] . "</h1>\n";
				echo "<h1>" . $language['search_user_agent'] . "</h1>\n";
				echo "<p>" . $language['exist_data'] . "</p>\n";
				echo "<h5>" . $language['crawler_name2'] . "&nbsp;&nbsp;<a href='index.php?navig=2&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=$line2[0]'>" . $crawlernamedisplay . "</a></h5>";
				echo "<h5>" . $language['crawler_user_agent'] . "&nbsp;&nbsp;" . $useragdisplay . "</h5>";
				echo "<h5>" . $language['crawler_user'] . "&nbsp;&nbsp;" . $crawlerinfodisplay . "</h5>";
				echo "<h5>" . $language['crawler_url2'] . "&nbsp;&nbsp;<a href=\"$line->crawler_url\">" . $crawlerurldisplay . "</a></h5>";
				echo "<div class=\"form\">\n";
				echo "<form action=\"index.php\" method=\"POST\" >\n";
				echo "<input type=\"hidden\" name ='navig' value='5'>\n";
				echo "<input name='ok' type='submit'  value='OK' size='20'>\n";
				echo "</form>\n";
				echo "</div>\n";
			} else {
				$settings->crawler = urldecode($settings->crawler);
				while ($line = $requetestats->fetch_row()) {
					$crawlerinfo = $line[1];
					$crawlername = $line[0];
					if ($crawlerinfo == $settings->crawler) {
						$list[] = $crawlername;
					}
				}
				
				//cut the url to avoid oversize display
				$crawldisplaylength = strlen("$settings->crawler");
				$cutvalue = 0;
				$crawlerdisplay = '';
				while ($cutvalue <= $crawldisplaylength) {
					$cutvalue2 = $cutvalue + 55;
					$crawlerdisplay = $crawlerdisplay . htmlentities(substr($settings->crawler, $cutvalue, 55));
					if ($cutvalue2 <= $crawldisplaylength) {
						$crawlerdisplay = $crawlerdisplay . '<br>';
					}
					$cutvalue = $cutvalue2;
				}
				echo "<br><br><h1>" . $language['search2'] . "</h1>\n";
				echo "<h1>" . $language['search_user'] . "</h1>\n";
				echo "<h2>" . $language['result_user_1'] . "" . $crawlerdisplay . "</h2><br>\n";
				if (isset($list)) {
					$list = array_unique($list);
					sort($list);
					
					//change text if more than 100 answers
					$nbrtotanswer = sizeof($list);
					if ($nbrtotanswer > 100) {
						echo "<br><br><h2>" . $language['to_many_answer'] . "</h2>\n";
					}
					echo "<div class='tableau' align='center'>";
					echo "<table   cellpadding='0px' cellspacing='0' width='450px'>\n";
					echo "<tr><td class='tableau2'>" . $language['result_user_crawler'] . "</td><tr>\n";
					
					//counter for alternate color lane
					$comptligne = 2;
					//counter to limite number of datas displayed
					$comptdata = 0;
					foreach ($list as $crawl) {
						//cut the url to avoid oversize display
						$crawldisplaylength = strlen("$crawl");
						$cutvalue = 0;
						$crawldisplay = '';
						while ($cutvalue <= $crawldisplaylength) {
							$cutvalue2 = $cutvalue + 80;
							$crawldisplay = $crawldisplay . htmlentities(substr($crawl, $cutvalue, 80));
							if ($cutvalue2 <= $crawldisplaylength) {
								$crawldisplay = $crawldisplay . '<br>';
							}
							$cutvalue = $cutvalue2;
						}
						if ($comptdata < 100) {
							if ($comptligne % 2 == 0) {
								echo "<tr><td class='tableau5'><a href='index.php?navig=2&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawl . "'>" . $crawldisplay . "</a></td><tr>\n";
							} else {
								echo "<tr><td class='tableau50'><a href='index.php?navig=2&amp;period=3&amp;site=" . $settings->siteid . "&amp;crawler=" . $crawl . "'>" . $crawldisplay . "</a></td><tr>\n";
							}
						}
						$comptligne++;
						$comptdata++;
					}
					echo "</table></div><br>";
				} else {
					echo "<br><br><h2>" . $language['no_answer'] . "</h2>\n";
				}
			}
		}
	}
}
$db->close(); // Close database
?>
