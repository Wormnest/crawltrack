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
// file: functions.php
//----------------------------------------------------------------------
//  Last update:12/09/2010
//----------------------------------------------------------------------
//function to format the numbers for display
function numbdisp($value) {
	global $crawltlang;
	if ($crawltlang == 'french') {
		$value = number_format($value, 0, ",", " ");
	} else {
		$value = number_format($value, 0, ".", ",");
	}
	return $value;
}

//function to format the numbers with 1 decimals for display
function numbdisp2($value) {
	global $crawltlang;
	if ($crawltlang == 'french') {
		$value = number_format($value, 1, ",", " ");
	} else {
		$value = number_format($value, 1, ".", ",");
	}
	return $value;
}

//function to format the numbers with 2 decimals for display
function numbdisp3($value) {
	global $crawltlang;
	if ($crawltlang == 'french') {
		$value = number_format($value, 2, ",", " ");
	} else {
		$value = number_format($value, 2, ".", ",");
	}
	return $value;
}

//function to check if there is a link to the site on the page
function islinking($url, $website) {
	global $timestart, $maxtime, $stoptest;
	
	// Try to temporarly allow url_fopen
	if (ini_get('allow_url_fopen') != 1) {
		@ini_set('allow_url_fopen', '1');
	}
	
	$timenow = time();
	if (($timenow - $timestart) > $maxtime) {
		$nofound = false;
		$stoptest = 1;
	} elseif (ini_get('allow_url_fopen') == 1) {
		$stoptest = 0;
		$fp = @fopen($url, "r");
		if ($fp) {
			if (strncmp($website, 'http://', 7) == 0) {
				$website = rtrim(substr($website, 7), '/');
			} else {
				$website = rtrim($website, '/');
			}
			$content = '';
			$nofound = false;
			$iteration = 0;
			while (!feof($fp) && $iteration < 300) {
				$content.= fgets($fp, 1024);
				$iteration++;
			}
			fclose($fp);
			if (strpos($content, $website) !== false) {
				$nofound = true;
			}
		} else {
			$nofound = false;
		}
	} else {
		$nofound = false;
		$stoptest = 0;
	}
	if ($nofound) {
		return true;
	} else {
		return false;
	}
}

//function to give the link for mapgraph
function linkmapgraph($monthdate, $actualmonth, $yeardate, $actualyear) {
	if ($monthdate >= $actualmonth && $yeardate == $actualyear) {
		$value = 2;
	} else {
		$value = 99 + ($actualmonth - $monthdate) + (12 * ($actualyear - $yeardate));
	}
	return $value;
}

//function to put the page in cache (http://spellbook.infinitiv.it/2006/07/03/caching-your-queries-with-php.htm)
function cache($cachename) {
	global $nocachetest, $crawlthost, $crawltuser, $crawltpassword, $crawltdb, $caching, $numbquery, $language;
	$caching = false;
	if (file_exists("./cachecloseperiod/$cachename.gz")) {
			//Grab the cache:
			$fgz = @fopen("./cachecloseperiod/$cachename.gz", "r");
			$data = fread($fgz, filesize("./cachecloseperiod/$cachename.gz"));
			fclose($fgz);
			$data = gzuncompress($data);
			echo $data;
			echo "<div class='smalltextgrey'>--" . $numbquery . " mysql query             " . getTime() . " s--</div>";
			echo "</body>\n";
			echo "</html>\n";
			exit();
		}
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword);
	$selection = mysql_select_db($crawltdb);
	$sqlcache = "SELECT time FROM crawlt_cache WHERE cachename='$cachename'";
	$requetecache = mysql_query($sqlcache, $connexion);
	$nbrresult = mysql_num_rows($requetecache);
	if ($nbrresult >= 1) {
		$ligne = mysql_fetch_row($requetecache);
		$time = $ligne[0];
	} else {
		$time = 0;
	}

	if (file_exists("./cache/$cachename.txt") && ($time + 3600) > time() && $nocachetest != 1) {
		//Grab the cache:
		include ("./cache/$cachename.txt");
		echo "<div class='smalltextgrey'>" . $numbquery . " mysql query             " . getTime() . " s</div>";
		echo "</body>\n";
		echo "</html>\n";
		exit();
	} else {
		//create cache :
		if ($time == 0) {
			$timecache = time();
			$sqlcache2 = "INSERT INTO crawlt_cache (cachename, time) VALUES ('$cachename','$timecache')";
			$requetecache2 = mysql_query($sqlcache2, $connexion);
		} else {
			$timecache = time();
			$sqlcache3 = "UPDATE crawlt_cache SET time='$timecache' where cachename='$cachename'";
			$requetecache3 = mysql_query($sqlcache3, $connexion);
		}
		$caching = 'true';
		ob_start();
	}
mysql_close($connexion);
}

function close() {
	global $caching, $cachename, $numbquery, $period, $navig, $language;
	//You should have this at the end of each page
	if ($caching == 'true') {
		//You were caching the contents so display them, and write the cache file
		$data = ob_get_contents();
		@ob_end_flush();
		if (($period >= 1000 || ($period >= 100 && $period < 200) || ($period >= 200 && $period < 300))&& $navig!=0 && $navig!=20 && $navig!=21  && $navig!=23 && $navig!=12 && $navig!=13 && $navig!=14 && $navig!=16) {
			$fp = fopen("./cachecloseperiod/$cachename.gz", 'w');
			fwrite($fp, gzcompress($data));
		} else {
			$fp = fopen("./cache/$cachename.txt", 'w');
			fwrite($fp, $data);
		}
		fclose($fp);
		echo "<div class='smalltextgrey'>" . $numbquery . " mysql query             " . getTime() . " s</div>";
		echo "</body>\n";
		echo "</html>\n";
	}
}

//function to escape query string
function sql_quote($value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	//check if this function exists
	if (function_exists("mysql_real_escape_string")) {
		$value = mysql_real_escape_string($value);
	}
	//for PHP version < 4.3.0 use addslashes
	else {
		$value = addslashes($value);
	}
	return $value;
}

//function to escape query string
function crawlt_sql_quote($value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	//check if this function exists
	if (function_exists("mysql_real_escape_string")) {
		$value = mysql_real_escape_string($value);
	}
	//for PHP version < 4.3.0 use addslashes
	else {
		$value = addslashes($value);
	}
	return $value;
}

//function to know if the string is encode in utf8
function isutf8($string) {
	return (utf8_encode(utf8_decode($string)) == $string);
}

//function to cut and wrap the url to avoid oversize display
function crawltcuturl($url, $length) {
	global $crawltcharset;
	if ($crawltcharset == 1) {
		if (!isutf8($url)) {
			if (function_exists("mb_convert_encoding")) {
				$url = @mb_convert_encoding($url, "UTF-8", "auto");
			}
		}
	} else {
		if (function_exists("mb_convert_encoding")) {
			$url = mb_convert_encoding($url, "ISO-8859-1", "auto");
		}
	}
	$urldisplaylength = strlen("$url");
	$cutvalue = 0;
	$urldisplay = '';
	while ($cutvalue <= $urldisplaylength) {
		$cutvalue2 = $cutvalue + $length;
		$urldisplay = $urldisplay . htmlspecialchars(substr($url, $cutvalue, $length));
		if ($cutvalue2 <= $urldisplaylength) {
			$urldisplay = $urldisplay . '<br>&nbsp;&nbsp;';
			$urlcut = 1;
		}
		$cutvalue = $cutvalue2;
	}
	return $urldisplay;
}

//function to cut and wrap the keyword to avoid oversize display
function crawltcutkeyword($keyword, $length) {
	global $keywordcut, $keywordtoolong, $crawltcharset;
	if ($crawltcharset == 1) {
		if (!isutf8($keyword)) {
			if (function_exists("mb_convert_encoding")) {
				$keyword = @mb_convert_encoding($keyword, "UTF-8", "auto");
			}
		}
	} else {
		if (function_exists("mb_convert_encoding")) {
			$keyword = @mb_convert_encoding($keyword, "ISO-8859-1", "auto");
		}
	}
	if (preg_match_all("/%/i", $keyword, $out) > 3) {
		$length = 0.6 * $length;
	}
	if (strlen("$keyword") > $length) {
		$keyworddisplay = substr("$keyword", 0, $length) . "...";
		$keywordcut = 1;
	} else {
		$keyworddisplay = $keyword;
		$keywordcut = 0;
	}
	if (strlen("$keyword") > 50) {
		$keywordtoolong = 1;
	} else {
		$keywordtoolong = 0;
	}
	return htmlspecialchars($keyworddisplay);
}

//function to set up the keyword position window
function crawltkeywordwindow($keyword) {
	$value = "onclick=\"return window.open('php/keywordposition.php?keyword=" . $keyword . "','CrawlTrack','top=0,left=0,height=700,width=1020, scrollbars=yes')\"";
	return $value;
}

//function to treat css attacks url
function crawltattackcss($page) {
	global $listattack, $tableurldisplay, $totallistattack, $listbadsite, $crawltcssaattack;
	if (strncmp($page, 'http://', 7) == 0) {
		$page = substr($page, 7);
	}
	$parseurl = parse_url('http://site.com/' . ltrim($page, "/"));
	if (isset($parseurl['query'])) {
		$chaine = $parseurl['query'];
		if (strpos($chaine, '&amp;')) {
			$queryEx = explode('&amp;', $chaine);
		} elseif (strpos($chaine, '&')) {
			$queryEx = explode('&', $chaine);
		} else {
			$queryEx[] = $chaine;
		}
		foreach ($queryEx as $value) {
			$varAndValue = explode('=', $value);
			$badsite = "";
			if (sizeof($varAndValue) >= 2) {
				for ($i = 1;$i < sizeof($varAndValue);$i++) {
					$crawlturl = str_replace($crawltcssaattack, 'http:', $varAndValue[$i]);
					if (preg_match("/http\:/i", $crawlturl)) {
						$badsite.= $varAndValue[$i] . "=";
					}
				}
			}
			// include only parameters
			$testattacktype = 0;
			foreach ($crawltcssaattack as $attacktype) {
				if (strpos($badsite, $attacktype) !== false) {
					$testattacktype = 1;
				}
			}
			if (sizeof($varAndValue) >= 2 && $testattacktype == 1) {
				$listattack[urldecode($varAndValue[0]) . "="] = urldecode($varAndValue[0]) . "=";
				$totallistattack[urldecode($varAndValue[0]) . "="] = urldecode($varAndValue[0]) . "=";
				$listbadsite[urldecode(rtrim($badsite, "=")) ] = urldecode(rtrim($badsite, "="));
			} else {
				$listattack[' '] = "";
				$totallistattack[' '] = "";
				$listbadsite[' '] = "";
			}
		}
	} else {
		$listattack[' '] = "";
		$totallistattack[' '] = "";
		$listbadsite[' '] = "";
	}
	$tableurldisplay[crawltcuturl($page, '80') ] = crawltcuturl($page, '80');
}

//function to treat sql attacks url
function crawltattacksql($page) {
	global $listattack, $tableurldisplay, $totallistattack, $listbadsite;
	if (strncmp($page, 'http://', 7) == 0) {
		$page = substr($page, 7);
	}
	$parseurl = parse_url('http://site.com/' . ltrim($page, "/"));
	if (isset($parseurl['query'])) {
		$chaine = $parseurl['query'];
		if (strpos($chaine, '&amp;')) {
			$queryEx = explode('&amp;', $chaine);
		} elseif (strpos($chaine, '&')) {
			$queryEx = explode('&', $chaine);
		} else {
			$queryEx[] = $chaine;
		}
		foreach ($queryEx as $value) {
			$varAndValue = explode('=', $value);
			if (sizeof($varAndValue) >= 2) {
				$badsite = "";
				for ($i = 1;$i < sizeof($varAndValue);$i++) {
					$badsite.= $varAndValue[$i] . "=";
				}
			}
			// include only parameters
			if (sizeof($varAndValue) >= 2 && (strpos(strtolower($badsite), '%20select%20') !== false || strpos(strtolower($badsite), '%20or%20') !== false) || (strpos(strtolower($badsite), '%20like%20') !== false || strpos(strtolower($badsite), '%20where%20') !== false)) {
				$listattack[] = urldecode($varAndValue[0]) . "=";
				$totallistattack[] = urldecode($varAndValue[0]) . "=";
				$listbadsite[] = urldecode(rtrim($badsite, "="));
			} else {
				$listattack[] = "";
				$totallistattack[] = "";
				$listbadsite[] = "";
			}
		}
	} else {
		$listattack[] = "";
		$totallistattack[] = "";
		$listbadsite[] = "";
	}
	$tableurldisplay[] = crawltcuturl($page, '80');
}

//function to check if the email address is valid from Christian Kruse
function check_email($email) {
	// RegEx begin
	$nonascii = "\x80-\xff"; # Les caractères Non-ASCII ne sont pas permis
	$nqtext = "[^\\\\$nonascii\015\012\"]";
	$qchar = "\\\\[^$nonascii]";
	$protocol = '(?:mailto:)';
	$normuser = '[a-zA-Z0-9][a-zA-Z0-9_.-]*';
	$quotedstring = "\"(?:$nqtext|$qchar)+\"";
	$user_part = "(?:$normuser|$quotedstring)";
	$dom_mainpart = '[a-zA-Z0-9][a-zA-Z0-9._-]*\\.';
	$dom_subpart = '(?:[a-zA-Z0-9][a-zA-Z0-9._-]*\\.)*';
	$dom_tldpart = '[a-zA-Z]{2,5}';
	$domain_part = "$dom_subpart$dom_mainpart$dom_tldpart";
	$regex = "$protocol?$user_part\@$domain_part";
	// RegEx end
	return preg_match("/^$regex$/", $email);
}

//function to display title and back and forward button
function crawltbackforward($title, $period, $daytodaylocal, $monthtodaylocal, $yeartodaylocal, $daybeginlocal, $monthbeginlocal, $yearbeginlocal, $dayendweek, $monthendweek, $yearendweek, $crawler, $navig, $site, $graphpos) {
	global $language, $testdate, $urlsite, $logodisplay, $hostsite;
	$crawlencode = urlencode($crawler);
	if ($navig == 0 || $navig == 6) {
		$titledisplay = '';
	} elseif ($navig == 23) {
		$titledisplay = $language['summary'];
		$logodisplay = "application_cascade.png";
	} elseif ($navig == 2 || $navig == 4) {
		$titledisplay = $title;
	} elseif ($navig == 16) {
		$titledisplay = $language['keyword'] . ":<span class=\"browntitle\"> " . $title . "</span>";
	} elseif ($navig == 14) {
		$titledisplay = $language['entry-page'] . ":<span class=\"browntitle\"> " . $title . "</span>&nbsp;&nbsp;<a href='" . $hostsite . $crawler . "'><img src=\"./images/page.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $language['entry-page'] . "\"></a>";
	} else {
		$titledisplay = $language[$title];
	}
	if ($navig == 0) {
		$firstline = '';
	} elseif ($navig == 4 || $navig == 14 || $navig == 16) {
		$firstline = "<br><div class='title'><img src=\"./images/" . $logodisplay . "\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $logodisplay . "\">&nbsp;" . $titledisplay . "</div>";
	} else {
		$firstline = "<div class='title'><img src=\"./images/" . $logodisplay . "\" width=\"16\" height=\"16\" border=\"0\" alt=\"" . $logodisplay . "\">&nbsp;" . $titledisplay . "</div>";
	}
	if ($period == 0 || $period >= 1000) {
		$testdate = 1;
		if ($period == 0) {
			$value = "
            <h2>" . $language['display_period'] . "&nbsp;" . $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2>           
            <h2><a href=\"index.php?navig=$navig&amp;period=1000&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 1000) {
				$periodgo = 0;
			}
			$value = "
            <h2>" . $language['display_period'] . "&nbsp;" . $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2>                 
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 2 || ($period >= 100 && $period < 200)) {
		$testdate = 0;
		if ($period == 2) {
			$value = "         
            <h2>" . $language['display_period'] . "&nbsp;" . $language[$monthtodaylocal] . "&nbsp;" . $yeartodaylocal . "</h2>            	
            <h2><a href=\"index.php?navig=$navig&amp;period=100&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 100) {
				$periodgo = 2;
			}
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language[$monthtodaylocal] . "&nbsp;" . $yeartodaylocal . "</h2>                 
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 3 || ($period >= 200 && $period < 300)) {
		$testdate = 0;
		if ($period == 3) {
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language['one_year'] . "&nbsp;" . $yeartodaylocal . "</h2>            	
            <h2><a href=\"index.php?navig=$navig&amp;period=200&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 200) {
				$periodgo = 3;
			}
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language['one_year'] . "&nbsp;" . $yeartodaylocal . "</h2>                  
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 1 || ($period >= 300 && $period < 400)) {
		$testdate = 0;
		if ($period == 1) {
			$value = "            
            <h2>" . $language['display_period'] . "&nbsp;" . $language['days'] . "&nbsp;" . $language['from'] . "&nbsp;" . $daybeginlocal . "/" . $monthbeginlocal . "/" . $yearbeginlocal . "&nbsp;" . $language['to'] . "&nbsp;" . $dayendweek . "/" . $monthendweek . "/" . $yearendweek . "</h2>            	
            <h2><a href=\"index.php?navig=$navig&amp;period=300&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <img src=\"./images/control_play.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\">
            <img src=\"./images/control_end.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></h2>";
		} else {
			$periodback = $period + 1;
			$periodgo = $period - 1;
			if ($periodgo < 300) {
				$periodgo = 1;
			}
			$value = "           
            <h2>" . $language['display_period'] . "&nbsp;" . $language['days'] . "&nbsp;" . $language['from'] . "&nbsp;" . $daybeginlocal . "/" . $monthbeginlocal . "/" . $yearbeginlocal . "&nbsp;" . $language['to'] . "&nbsp;" . $dayendweek . "/" . $monthendweek . "/" . $yearendweek . "</h2>    
            <h2><a href=\"index.php?navig=$navig&amp;period=$periodback&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_back_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"back\"></a>
            <a href=\"index.php?navig=$navig&amp;period=$periodgo&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_play_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"play\"></a>
            <a href=\"index.php?navig=$navig&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/control_end_blue.png\" width=\"16\" height=\"16\" border=\"0\" alt=\"end\"></a></h2>";
		}
	} elseif ($period == 4 || $period == 5) {
		$testdate = 0;
		$value = "
        <h2>" . $language['display_period'] . "&nbsp;" . $daybeginlocal . "/" . $monthbeginlocal . "/" . $yearbeginlocal . "
        ---> " . $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2><br><br>";
	}
	$value = $firstline . $value;
	if ($navig == 6) {
		$value = '';
	}
	return $value;
}

//function to count the number of day from today
function nbdayfromtoday($date) {
	$today = strtotime("today");
	$daydate = strtotime($date);
	$delta = $today - $daydate;
	if ($delta <= 0) {
		$nbdayfromtoday = 0;
	} else {
		$nbdayfromtoday = $delta / 86400;
		$nbdayfromtoday = IntVal($nbdayfromtoday);
	}
	return ($nbdayfromtoday);
}

// Function to remove http(s) at beginning of URLs
function strip_protocol($url='')
{
	return preg_replace("/^https?:\/\/(.+)$/i","\\1", $url);
}

//request date calculation according period
//-------period calculation including time shift-----------------------------------------
//day server
$serverday = date("j", strtotime("today"));
$todayserver = date("Y-m-d", strtotime("today"));
$todayserver2 = explode('-', $todayserver);
$yeartodayserver = $todayserver2[0];
$monthtodayserver = $todayserver2[1];
$daytodayserver = $todayserver2[2];
//day local
$localday = date("j", (strtotime("today")) - ($times * 3600));
//test to calculate the reference time
if ($serverday == $localday) {
	$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600)));
} elseif ($serverday < $localday) {
	if ($serverday == 1 && $localday != 2) {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) - 86400));
	} else {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) + 86400));
	}
} elseif ($serverday > $localday) {
	if ($localday == 1 && $serverday != 2) {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) + 86400));
	} else {
		$reftime = date("Y-m-d H:i:s", (mktime(0, 0, 0, $monthtodayserver, $daytodayserver, $yeartodayserver) + ($times * 3600) - 86400));
	}
}
$datelocal = date("Y-m-d H:i:s",(strtotime("today")- ($times * 3600)));
$datelocalcut = explode(' ', $datelocal);
$todaylocal = explode('-', $datelocalcut[0]);
$yeartodaylocal = $todaylocal[0];
$monthtodaylocal = $todaylocal[1];
$daytodaylocal = $todaylocal[2];
if ($period == 0) {
	//case 1 day
	$daterequest = $reftime;
	$daterequestseo = date("Y-m-d", strtotime($reftime));
	$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
} elseif ($period == 1) {
	//case 1 week
	$testweekday = 0;
	do {
		$dayname = date("l", (strtotime($reftime) - ($times * 3600)));
		if ($dayname == $firstdayweek) {
			$daterequest = date("Y-m-d H:i:s", strtotime($reftime));
			$daterequestseo = date("Y-m-d", strtotime($reftime));
			$testweekday = 1;
		} else {
			$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - 86400));
		}
	} while ($testweekday == 0);
	$testweekday = 0;
	do {
		$dayname = date("l", strtotime($datelocal));
		if ($dayname == $firstdayweek) {
			$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
			$testweekday = 1;
		} else {
			$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 86400));
		}
	} while ($testweekday == 0);
} elseif ($period == 2) {
	//case 1 month
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0];
	$monthrequest = $daterequest2[1];
	$dayrequest = 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0];
	$monthbeginlocal = $datebeginlocal2[1];
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
} elseif ($period == 3) {
	//case 1 year
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0];
	$monthrequest = 1;
	$dayrequest = 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0];
	$monthbeginlocal = 1;
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
} elseif ($period >= 1000) {
	//case 1 day (back and forward)
	$shiftday = $period - 999;
	$shiftday2 = $period - 1000;
	$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday * 86400)));
	//case change to summer time----------------
	$explodedate1 = explode(' ', $daterequest);
	$explodedate2 = explode(':', $explodedate1[1]);
	if ($explodedate2[0] > 20) {
		$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday * 82800)));
		$daterequest2 = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday2 * 82800)));
		$daterequestseo = date("Y-m-d", (strtotime($reftime) - ($shiftday * 82800)));
		$daterequest2seo = date("Y-m-d", (strtotime($reftime) - ($shiftday2 * 82800)));
		$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - ($shiftday * 82800)));
	}
	//------------------------------
	else {
		$daterequest2 = date("Y-m-d H:i:s", (strtotime($reftime) - ($shiftday2 * 86400)));
		$daterequestseo = date("Y-m-d", (strtotime($reftime) - ($shiftday * 86400)));
		$daterequest2seo = date("Y-m-d", (strtotime($reftime) - ($shiftday2 * 86400)));
		$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - ($shiftday * 86400)));
	}
	$datebeginlocalcut = explode(' ', $datebeginlocal);
	$todaylocal2 = explode('-', $datebeginlocalcut[0]);
	$yeartodaylocal = $todaylocal2[0];
	$monthtodaylocal = $todaylocal2[1];
	$daytodaylocal = $todaylocal2[2];
} elseif ($period >= 100 && $period < 200) {
	//case 1 month (back and forward)
	$shiftmonth = $period - 99;
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0];
	$monthrequest = $daterequest2[1] - $shiftmonth;
	$dayrequest = 1;
	$monthrequest2 = $daterequest2[1] - $shiftmonth + 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2 = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest2, $dayrequest, $yearrequest));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2seo = date("Y-m-d", mktime(0, 0, 0, $monthrequest2, $dayrequest, $yearrequest));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0];
	$monthbeginlocal = $datebeginlocal2[1] - $shiftmonth;
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
	$datebeginlocalcut = explode(' ', $datebeginlocal);
	$todaylocal2 = explode('-', $datebeginlocalcut[0]);
	$yeartodaylocal = $todaylocal2[0];
	$monthtodaylocal = $todaylocal2[1];
	$daytodaylocal = $todaylocal2[2];
} elseif ($period >= 200 && $period < 300) {
	//case 1 year (back and forward)
	$shiftyear = $period - 199;
	$daterequestcut = explode(' ', $reftime);
	$daterequest2 = explode('-', $daterequestcut[0]);
	$yearrequest = $daterequest2[0] - $shiftyear;
	$monthrequest = 1;
	$dayrequest = 1;
	$yearrequest2 = $daterequest2[0] - $shiftyear + 1;
	$daterequest = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2 = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest2));
	$daterequestseo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest));
	$daterequest2seo = date("Y-m-d", mktime(0, 0, 0, $monthrequest, $dayrequest, $yearrequest2));
	$datelocalcut = explode(' ', $datelocal);
	$datebeginlocal2 = explode('-', $datelocalcut[0]);
	$yearbeginlocal = $datebeginlocal2[0] - $shiftyear;
	$monthbeginlocal = 1;
	$daybeginlocal = 1;
	$datebeginlocal = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, $daybeginlocal, $yearbeginlocal));
	$datebeginlocalcut = explode(' ', $datebeginlocal);
	$todaylocal2 = explode('-', $datebeginlocalcut[0]);
	$yeartodaylocal = $todaylocal2[0];
	$monthtodaylocal = $todaylocal2[1];
	$daytodaylocal = $todaylocal2[2];
} elseif ($period >= 300 && $period < 400) {
	//case 1 week (back and forward)
	$shiftweek = $period - 299;
	$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - (604800 * $shiftweek)));
	$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - (604800 * $shiftweek)));
	//case 1 week
	$testweekday = 0;
	do {
		$dayname = date("l", (strtotime($reftime) - ($times * 3600)));
		if ($dayname == $firstdayweek) {
			$daterequest = date("Y-m-d H:i:s", strtotime($reftime));
			$daterequestseo = date("Y-m-d", strtotime($reftime));
			$daterequest2 = date("Y-m-d H:i:s", (strtotime($reftime) + 604800));
			$daterequest2seo = date("Y-m-d", (strtotime($reftime) + 604800));
			$testweekday = 1;
		} else {
			$reftime = date("Y-m-d H:i:s", (strtotime($reftime) - 86400));
		}
	} while ($testweekday == 0);
	$testweekday = 0;
	do {
		$dayname = date("l", strtotime($datelocal));
		if ($dayname == $firstdayweek) {
			$datebeginlocal = date("Y-m-d H:i:s", strtotime($datelocal));
			$testweekday = 1;
		} else {
			$datelocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 86400));
		}
	} while ($testweekday == 0);
} elseif ($period == 4) {
	//case 8 days
	$daterequest = date("Y-m-d H:i:s", (strtotime($reftime) - 604800));
	$daterequestseo = date("Y-m-d", (strtotime($reftime) - 604800));
	$datebeginlocal = date("Y-m-d H:i:s", (strtotime($datelocal) - 604800));
} elseif ($period == 5) {
	//case since installation
	$sql = "SELECT  MIN(date) FROM crawlt_visits
    WHERE crawlt_visits.crawlt_site_id_site='" . sql_quote($site) . "'";
	$requete = db_query($sql, $connexion);
	$nbrresult = mysql_num_rows($requete);
	if ($nbrresult >= 1) {
		$ligne = mysql_fetch_row($requete);
		$reftimestart = $ligne[0];
	} else {
		$reftimestart = $reftime;
	}
	$daterequest = date("Y-m-d H:i:s", strtotime($reftimestart));
	$daterequestseo = date("Y-m-d", strtotime($reftimestart));
	$datebeginlocal = date("Y-m-d H:i:s", (strtotime($daterequest) - ($times * 3600)));
}
$daterequestcut = explode(' ', $daterequest);
$beginserver = explode('-', $daterequestcut[0]);
$yearbeginserver = $beginserver[0];
$monthbeginserver = $beginserver[1];
$daybeginserver = $beginserver[2];
$datebeginlocalcut = explode(' ', $datebeginlocal);
$beginlocal = explode('-', $datebeginlocalcut[0]);
$yearbeginlocal = $beginlocal[0];
$monthbeginlocal = $beginlocal[1];
$daybeginlocal = $beginlocal[2];
$oneweeklater = date("Y-m-d H:i:s", mktime(0, 0, 0, $monthbeginlocal, ($daybeginlocal + 6), $yearbeginlocal));
$endweek = explode(' ', $oneweeklater);
$endweek2 = explode('-', $endweek[0]);
$yearendweek = $endweek2[0];
$monthendweek = $endweek2[1];
$dayendweek = $endweek2[2];
//-------end of period calculation including time shift-----------------------------------------
?>
