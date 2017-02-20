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
// file: functions.php
//----------------------------------------------------------------------

//create a unique and random string, thanks to phpsources(http://www.phpsources.org/scripts87-PHP.htm)
// Used for creating the secretkey.
// TODO: We might want to check if this is secure enough.
function random($car) {
	$string = "";
	$chaine = "abcdefghijklmnpqrstuvwxy";
	srand((double)microtime()*1000000);
	for($i=0; $i<$car; $i++) {
	$string .= $chaine[rand()%strlen($chaine)];
	}
	return $string;
}
//function to format the numbers with specified decimals for display
// TODO: Decimal separator should be determined differently.
// Probably a setting/variable DecimalSeparator.
function numbdisp($value, $decimals = 0) {
	global $crawltlang;
	//test if numeric
	if(is_numeric($value))
		{
		// Use a default value if needed
		if($decimals > 2 || $decimals < 0 || is_null($decimals))
			$decimals = 0;
		if ($crawltlang == 'french') {
			$value = number_format($value,  $decimals, ",", " ");
		} else {
			$value = number_format($value,  $decimals, ".", ",");
		}
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

// TODO: Use cache class that has access to our db class.
// TODO: Don't open a new connection but use existing connection!
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
	$db = new ctDb();
	$connexion = $db->connexion;
	$sqlcache = "SELECT time FROM crawlt_cache WHERE cachename='$cachename'";
	$requetecache = $connexion->query($sqlcache);
	$nbrresult = $requetecache->num_rows;
	if ($nbrresult >= 1) {
		$ligne = $requetecache->fetch_row();
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
			$requetecache2 = $connexion->query($sqlcache2);
		} else {
			$timecache = time();
			$sqlcache3 = "UPDATE crawlt_cache SET time='$timecache' where cachename='$cachename'";
			$requetecache3 = $connexion->query($sqlcache3);
		}
		$caching = 'true';
		ob_start();
	}
	$db->close();
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
		echo "<div class='smalltextgrey'>" . $numbquery . " mysql queries             " . getTime() . " s</div>";
		echo "</body>\n";
		echo "</html>\n";
	}
}

// Empty the given cache folder but leave index.htm alone.
// Note $cachefolder is expected to end in a '/'
function empty_cache($cachefolder) {
	$dir = dir($cachefolder);
	while (false !== $entry = $dir->read()) {
		// Skip pointers
		if ($entry == '.' || $entry == '..' || $entry == 'index.htm') {
			continue;
		}
		// Delete the cache file
		unlink($cachefolder . $entry);
	}
	// Clean up
	$dir->close();
}

/** JB: This seems to be exactly the same as crawlt_sql_quote, commented out, use the other function!
//function to escape query string
function sql_quote($connexion, $value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	//check if this function exists
	if (function_exists("mysqli_real_escape_string")) {
		$value = $connexion->real_escape_string($value);
	}
	//for PHP version < 4.3.0 use addslashes
	else {
		$value = addslashes($value);
	}
	return $value;
}*/

//function to escape query string
function crawlt_sql_quote($connexion, $value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}
	//check if this function exists
	if (function_exists("mysqli_real_escape_string")) {
		$value = $connexion->real_escape_string($value);
	}
	//for PHP version < 4.3.0 use addslashes
	else {
		$value = addslashes($value);
	}
	return $value;
}

// WARNING: Next to functions have similar functions in mail.php
//function to know if the string is encoded in utf8
function isutf8($string) {
	return (utf8_encode(utf8_decode($string)) == $string);
}

//function to cut and wrap the url to avoid oversize display
function crawltcuturl($url, $length, $crawltcharset) {
	//global $crawltcharset;
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
function crawltcutkeyword($keyword, $length, $crawltcharset) {
	global $keywordcut, $keywordtoolong; //, $crawltcharset;
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

//function to treat xss attacks url
// TODO: Also support https protocol
function crawltattackxss($page, $crawltcharset) {
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
	$tableurldisplay[crawltcuturl($page, '80', $crawltcharset) ] = crawltcuturl($page, '80', $crawltcharset);
}

//function to treat sql attacks url
// TODO: Also support https protocol
function crawltattacksql($page, $crawltcharset) {
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
	$tableurldisplay[] = crawltcuturl($page, '80', $crawltcharset);
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
		$dateoftheday=$yeartodaylocal."-".$monthtodaylocal."-".$daytodaylocal;
		$dayenglish=date('D', strtotime($dateoftheday));
		if($dayenglish=='Mon') {
			$jour='day0';
		} elseif($dayenglish=='Tue') {
			$jour='day1';
		} elseif($dayenglish=='Wed') {
			$jour='day2';
		} elseif($dayenglish=='Thu') {
			$jour='day3';
		} elseif($dayenglish=='Fri') {
			$jour='day4';
		} elseif($dayenglish=='Sat') {
			$jour='day5';
		} elseif($dayenglish=='Sun') {
			$jour='day6';}
		if ($period == 0) {
			$value = "
            <h2>" . $language['display_period'] . "&nbsp;" .$language[$jour]."&nbsp;". $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2>           
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
            <h2>" . $language['display_period'] . "&nbsp;" .$language[$jour]."&nbsp;". $daytodaylocal . "/" . $monthtodaylocal . "/" . $yeartodaylocal . "</h2>                 
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

//function to count the number of days from today
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

?>
