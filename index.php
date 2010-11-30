<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.7
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
// file: index.php
//----------------------------------------------------------------------
//  Last update: 09/11/2010
//----------------------------------------------------------------------

// make sure PHP version  >= 4.3.2 is used (and even this version is waaaay too old, 29-May-2003)
if (version_compare(PHP_VERSION, '4.3.2', '<')) exit("Sorry, CrawlTrack needs at least PHP version 4.3.2 to run ! You are running version " . PHP_VERSION . " \n");

error_reporting(0);
//initialize array & data
$listlangcrawlt = array();
$numbquery = 0;
//function to count the number of mysql query
function db_query($sql) {
	global $numbquery;
	$numbquery++;
	return mysql_query($sql);
}
//function to measure the time used for the calculation
function getTime() {
	static $timer = false, $start;
	if ($timer === false) {
		$start = array_sum(explode(' ', microtime()));
		$timer = true;
		return NULL;
	} else {
		$timer = false;
		$end = array_sum(explode(' ', microtime()));
		return round(($end - $start), 3);
	}
}
getTime();
//if already install get all the config datas
if (file_exists('include/configconnect.php')) {
	//connection file include
	require_once ("include/configconnect.php");
	if (!isset($crawlthost)) //case old version (before 150)
	{
		$crawlthost = $host;
		$crawltuser = $user;
		$crawltpassword = $password;
		$crawltdb = $db;
		$crawltlang = $lang;
		$crawltpublic = 0;
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	} else {
		$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
		$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
		$sqlconfig = "SELECT * FROM crawlt_config";
		$requeteconfig = db_query($sqlconfig, $connexion);
		$nbrresult = mysql_num_rows($requeteconfig);
		if ($nbrresult >= 1) {
			$ligne = mysql_fetch_assoc($requeteconfig);
			$times = $ligne['timeshift'];
			$crawltpublic = $ligne['public'];
			$crawltmail = $ligne['mail'];
			$crawltlastday = $ligne['datelastmail'];
			$crawltdest = $ligne['addressmail'];
			$crawltlang = $ligne['lang'];
			$version = $ligne['version'];
			if ($version > 160) {
				$firstdayweek = $ligne['firstdayweek'];
			}
			if ($version > 171) {
				$datecleaning = $ligne['datelastcleaning'];
			}
			if ($version > 210) {
				$rowdisplay = $ligne['rowdisplay'];
				$order = $ligne['orderdisplay'];
			} else {
				$rowdisplay = 30;
				$order = 0;
			}
			if ($version > 220) {
				$crawltmailishtml = $ligne['typemail'];
				$crawltcharset = $ligne['typecharset'];
			} else {
				$crawltmailishtml = 1;
				$crawltcharset = 1;
			}
			if ($version > 281) {
				$crawltblockattack = $ligne['blockattack'];
				$crawltsessionid = $ligne['sessionid'];
				$crawltincludeparameter = $ligne['includeparameter'];
			} else {
				$crawltblockattack = 0;
				$crawltsessionid = 0;
				$crawltincludeparameter = 1;
			}
		}
	}
	$charset = 1;
} else {
	$charset = 0;
	$crawltcharset = 1;
}
require_once ("include/post.php");
if ($charset == 1) {
	if ($crawltcharset != 1) {
		$crawltlang = $crawltlang . "iso";
	}
}
//for the install we need to give a value to $times
if (!isset($times)) {
	$times = 0;
}
require_once ("include/listlang.php");
require_once ("include/functions.php");
if ($charset == 1) {
	mysql_close($connexion);
	}
//language file include
if (file_exists("language/" . $crawltlang . ".php") && in_array($crawltlang, $listlangcrawlt)) {
	require_once ("language/" . $crawltlang . ".php");
} else {
	exit('<h1>No language files available !!!!</h1>');
}
//version id
$versionid = '327';
// do not modify
define('IN_CRAWLT', TRUE);
// session start 'crawlt'
if (!isset($_SESSION['flag'])) {
	session_name('crawlt');
	session_start();
	$_SESSION['flag'] = true;
}
//if already install
if (file_exists('include/configconnect.php') && $navig != 15) {
	if ($navig == 0) {
		$main = ("include/display-dashboard.php");
	} elseif ($navig == 1) {
		$main = ("include/display-all-crawlers.php");
	} elseif ($navig == 2) {
		$main = ("include/display-one-crawler.php");
	} elseif ($navig == 3) {
		$main = ("include/display-all-pages.php");
	} elseif ($navig == 4) {
		$main = ("include/display-one-page.php");
	} elseif ($navig == 5) {
		$main = ("include/search.php");
	} elseif ($navig == 6) {
		$main = ("include/admin.php");
	} elseif ($navig == 7) {
		$main = ("include/index.htm"); // to avoid notice error in Apache logs
		session_destroy();
		header("Location:index.php");
		exit;
	} elseif ($navig == 8) {
		$main = ("include/display-crawlers-info.php");
	} elseif ($navig == 10) {
		$main = ("include/updateurl.php");
	} elseif ($navig == 11) {
		$main = ("include/display-seo.php");
	} elseif ($navig == 12) {
		$main = ("include/display-keyword.php");
	} elseif ($navig == 13) {
		$main = ("include/display-entrypage.php");
	} elseif ($navig == 14) {
		$main = ("include/display-one-entrypage.php");
	}
	// 15 is used for installation
	elseif ($navig == 16) {
		$main = ("include/display-one-keyword.php");
	} elseif ($navig == 17) {
		$main = ("include/display-hacking.php");
	} elseif ($navig == 18) {
		$main = ("include/display-hacking-css.php");
	} elseif ($navig == 19) {
		$main = ("include/display-hacking-sql.php");
	} elseif ($navig == 20) {
		$main = ("include/display-visitors.php");
	} elseif ($navig == 21) {
		$main = ("include/display-pages-visitors.php");
	} elseif ($navig == 22) {
		$main = ("include/display-errors.php");
	} elseif ($navig == 23) {
		$main = ("include/display-summary.php");
	} else {
		$main = ("include/display-dashboard.php");
	}
	//  IF NO SESSION LOGIN
	if (!isset($_SESSION['userlogin']) && !isset($_SESSION['userpass'])) {
		if ($crawltpublic == 1 && $navig != 6 && $logitself != 1) {
			//case free access to the stats
			if (!isset($_SESSION['rightsite'])) {
				//clear the cache folder at the first entry on crawltrack to avoid to have it oversized
				$dir = dir('cache/');
				while (false !== $entry = $dir->read()) {
					// Skip pointers
					if ($entry == '.' || $entry == '..') {
						continue;
					}
					unlink("cache/$entry");
				}
			}
			// session start 'crawlt'
			if (!isset($_SESSION)) {
				session_name('crawlt');
				session_start();
				$_SESSION['rightsite'] = "0";
			} else {
				$_SESSION['rightsite'] = "0";
			}
			//test to see if version is up-to-date
			if (!isset($version)) {
				$version = 100;
			}
			if ($version == $versionid) {
				include ("include/nocache.php");
				//installation is up-to-date, display stats
				include ("include/header.php");
				include ("$main");
				include ("include/footer.php");
			} else {
				//update the installation
				include ("include/header.php");
				include ("include/updatecrawltrack.php");
				include ("include/footer.php");
			}
		} else {
			//get values
			if (isset($_POST['userlogin'])) {
				$userlogin = htmlentities($_POST['userlogin']);
			} else {
				$userlogin = '';
			}
			if (isset($_POST['userpass'])) {
				$userpass = htmlentities($_POST['userpass']);
			} else {
				$userpass = '';
			}
			//access form
			include ("include/header.php");
			echo "<div class=\"content\">\n";

			if ($crawltpublic == 1 && $logitself != 1) {
				echo "<h1>" . $language['admin_protected'] . "</h1>\n";
			} else {
				echo "<h1>" . $language['restrited_access'] . "</h1>\n";
			}
			echo "<h2>" . $language['enter_login'] . "</h2>\n";
			echo "<div class=\"form\">\n";
			echo "<form action=\"php/login.php\" method=\"POST\" name=\"login\" >\n";
			echo "<table align=\"left\" width=\"400px\">\n";
			echo "<tr>\n";
			echo "<td >" . $language['login'] . "&nbsp;<input name='userlogin' value='$userlogin' type='text' maxlength='20' size='20'/></td></tr>\n";
			echo "<tr><td></td></tr>\n";
			echo "<tr><td>" . $language['password'] . "&nbsp;<input name='userpass'  value='$userpass' type='password' maxlength='20' size='20'/></td</tr>\n";
			if (isset($lang)) {
				echo "<input type=\"hidden\" name ='lang' value='$lang'>\n";
			} else {
				echo "<input type=\"hidden\" name ='lang' value='$crawltlang'>\n";
			}
			echo "<input type=\"hidden\" name ='graphpos' value=\"$graphpos\">\n";
			echo "<input type=\"hidden\" name ='navig' value=\"$navig\">\n";
			echo "<input type=\"hidden\" name ='period' value=\"$period\">\n";
			echo "<input type=\"hidden\" name ='site' value=\"$site\">\n";
			echo "<input type=\"hidden\" name ='validform' value=\"$validform\">\n";
			echo "<input type=\"hidden\" name ='displayall' value=\"$displayall\">\n";
			echo "<input type=\"hidden\" name ='logitself' value=\"$logitself\">\n";
			echo "<tr><td><input name='ok' type='submit'  value='OK' size='20'></td></tr>\n";
			echo "</table></form>\n";
			echo "<script type=\"text/javascript\"> document.forms[\"login\"].elements[\"userlogin\"].focus()</script>\n";
			echo "<br><br><br><br><br>\n";
			echo "<div align='center'><br><iframe name=\"I1\" src=\"http://www.crawltrack.net/news/rel.php?r=".$versionid."&p=".PHP_VERSION."&l=".$crawltlang."\" marginwidth=\"0\" marginheight=\"0\" scrolling=\0\" frameborder=\"0\" width=\"400px\" height=\"20px\"></iframe></div><br><br>\n";
			echo "</div>\n";
			include ("include/footer.php");
		}
	} else {
		//test to see if version is up-to-date
		if (!isset($version)) {
			$version = 100;
		}
		if ($version == $versionid) {
			include ("include/nocache.php");
			//installation is up-to-date, display stats
			include ("include/header.php");
			include ("$main");
			include ("include/footer.php");
		} else {
			//update the installation
			include ("include/header.php");
			include ("include/updatecrawltrack.php");
			include ("include/footer.php");
		}
	}
} else {
	//display install
	$navig = '';
	include ("include/header.php");
	include ("include/install.php");
	include ("include/footer.php");
}
if ($navig == 0 || $navig == 1 || $navig == 2 || $navig == 3 || $navig == 4 || $navig == 8 || $navig == 11 || $navig == 12 || $navig == 13 || $navig == 14 || $navig == 16 || $navig == 17 || $navig == 18 || $navig == 19 || $navig == 20 || $navig == 21 || $navig == 22 || $navig == 23) {
	//close the cache function
	close();
} else {
	echo "<div class='smalltextgrey'>" . $numbquery . " mysql query           " . getTime() . " s</div>";
	echo "</body>\n";
	echo "</html>\n";
}
?>
