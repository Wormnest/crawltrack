<?php
//No-visitor-CrawlTrack
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
// file: crawltrack.php
//----------------------------------------------------------------------

// Don't show any errors, warnings, notices
error_reporting(0);

@set_time_limit(10);
$crawltattack=0;
// Function to remove http(s) at beginning of URLs
if(!function_exists('strip_protocol')) {
	function strip_protocol($url='')
	{
		return preg_replace("/^https?:\/\/(.+)$/i","\\1", $url);
	}
}
//connection to database
require_once("FILE_PATH/include/configconnect.php");
$connexion = mysqli_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb) or die("MySQL connection to database problem");
if (mysqli_connect_errno()) {
	die(mysqli_connect_error());
}

//query to get the good site list
$sql = "SELECT host_site FROM crawlt_good_sites";
$requete = $connexion->query($sql);
$nbrresult = $requete->num_rows;
if($nbrresult>=1)
{
	while ($ligne = $requete->fetch_row())
	{
		$crawltlistgoodsite[]=$ligne[0];
	}
}
else
{
	$crawltlistgoodsite=array();
}
//include searchenginelist.php file
require_once("FILE_PATH/include/searchenginelist.php");
 //query to get the session id list
$sql = "SELECT sessionid FROM crawlt_sessionid";
$requete = $connexion->query($sql);
$nbrresult = $requete->num_rows;
if($nbrresult>=1)
{
	while ($ligne = $requete->fetch_row())
	{
		$crawltlistsessionid[]=$ligne[0];
	}
}
else
{
	$crawltlistsessionid=array();
}
//mysql escape function
if(!function_exists( "crawlt_sql_quote" ))
{
	function crawlt_sql_quote( $connexion,$value )
	{
	if( get_magic_quotes_gpc() )
		{
		$value = stripslashes( $value );
		}
	//check if this function exists
	if( function_exists( "mysqli_real_escape_string" ) )
		{
		$value = $connexion->real_escape_string( $value );
		}
	//for PHP version < 4.3.0 use addslashes
	else
		{
		$value = addslashes( $value );
		}
	return $value;
	}
}
//function url treatment (base on phpMyVisites processParams function)
if(!function_exists( "crawlturltreatment" ))
{
	function crawlturltreatment($url)
	{
		global $crawltlistsessionid, $crawltsessionid, $crawltincludeparameter;
		if($crawltsessionid==0 && $crawltincludeparameter==1)
		{
			$toReturn=$url;
		}
		elseif($crawltincludeparameter==0)
		{
			$explodeurl=explode("?",$url);
			$toReturn=$explodeurl[0];
		}
		else
		{
			$url2=ltrim($url,"/");
			$urltreated =0;
			$parseurl = parse_url('http://site.com/'.$url2);
			if(isset($parseurl['query']))
			{
				$chaine=$parseurl['query'];
				if(strpos($chaine, '&amp;'))
				{
					$queryEx = explode('&amp;', $chaine);
					$separator='&amp;';
				}
				else
				{
					$queryEx = explode('&', $chaine);
					$separator='&';
				}
				$return = $parseurl['path'] . '?';
				foreach($queryEx as $value)
				{
					$varAndValue = explode('=', $value);
					// include only parameters
					if( sizeof($varAndValue) >= 2  && in_array($varAndValue[0], $crawltlistsessionid))
					{
						$urltreated=1;
					}
					elseif( sizeof($varAndValue) >= 2)
					{
						$return .= $varAndValue[0]."=";
						for($i=1; $i< sizeof($varAndValue);$i++)
						{
							$return .= $varAndValue[$i]."=";
						}
						$return = rtrim($return,"=").$separator;
					}
				}
				if(substr($return, strlen($return)-strlen($separator)) == $separator && $urltreated==1)
				{
					$toReturn = substr($return, 0, strlen($return)-strlen($separator));
				}
				elseif(substr($return, strlen($return)-1) == '?'  && $urltreated==1)
				{
					$toReturn = substr($return, 0, strlen($return)-1);
				}
				elseif($urltreated==0)
				{
					$toReturn=$url;
				}
			}
			else
			{
				$toReturn = $url;
			}
		}
		return $toReturn;
	}
}

//get information
$crawltispostdata=0;
if (!isset($_SERVER))
{
	$_SERVER = $HTTP_SERVER_VARS;
}
if(isset($_POST['agent']) && isset($_POST['ip']) && isset($_POST['url']) && isset($_POST['site']) && isset($_POST['cookie']))
{
	$crawltagent = $_POST['agent'];
	$crawltispostdata=1;
}
else
{
	$crawltagent = $_SERVER['HTTP_USER_AGENT'];
}
if($crawltispostdata==1)
{
	$crawltip = $_POST['ip'];
}
else
{
	$crawltip = $_SERVER['REMOTE_ADDR'];
}
if($crawltispostdata==1)
{
	$crawlturl2 = $_POST['url'];
	$crawltpostrequest=1;
}
else
{
	if(isset($_ENV['REQUEST_URI']) && substr($_ENV['REQUEST_URI'], -3)!='cgi')
	{
		$crawlturl2 = $_ENV['REQUEST_URI'];
	}
	else
	{
		$crawlturl2 = $_SERVER['REQUEST_URI'];
	}
	$crawltpostrequest=0;
}
if($crawltispostdata==1)
{
	$crawltsite = $_POST['site'];
}
else
{
	if(!isset($crawltsite))
	{
	$crawltsite=$site;
	}
}
if($crawltispostdata==1)
{
	$_COOKIE["crawltrackstats".$crawltsite] = $_POST['cookie'];
}
if($crawltispostdata==1)
{
	$crawlthttpcode = $_POST['httpcode'];
}
else
{
	if (isset($_SERVER['REDIRECT_STATUS'])) {
		$crawlthttpcode = $_SERVER['REDIRECT_STATUS'];
	}
	else {
		$crawlthttpcode = 0;
	}
}
//get config parameters
$sqlcrawltconfig = "SELECT mail, datelastmail, timeshift, lang, addressmail, datelastseorequest, loop1, loop2, typemail, typecharset, blockattack, datelastcleaning, sessionid,includeparameter FROM crawlt_config";
$requetecrawltconfig = $connexion->query($sqlcrawltconfig);
$nbrresultcrawlt = $requetecrawltconfig->num_rows;
if($nbrresultcrawlt>=1)
{
	$lignecrawlt = $requetecrawltconfig->fetch_row();
	$crawltmail=$lignecrawlt[0];
	$crawltdatemail=$lignecrawlt[1];
	$crawlttime=$lignecrawlt[2];
	$crawltlang=$lignecrawlt[3];
	$crawltdest=$lignecrawlt[4];
	$crawltdatelastseorequest=$lignecrawlt[5];
	$crawltloop=$lignecrawlt[6];
	$crawltloop2=$lignecrawlt[7];
	$crawltmailishtml=$lignecrawlt[8];
	$crawltcharset=$lignecrawlt[9];
	$crawltblockattack=$lignecrawlt[10];
	$datecleaning=$lignecrawlt[11];
	$crawltsessionid=$lignecrawlt[12];
	$crawltincludeparameter=$lignecrawlt[13];
	if( $crawltcharset !=1)
	{
		$crawltlang = $crawltlang."iso";
	}
	$crawltcheck=1;
}
$crawlturl = crawlturltreatment($crawlturl2);
//count all the hits---------------------------------------------------
//check if the date exist in the crawlt_hits table for that site
$crawlttodaylocal = date("Y-m-d",(time() - ($crawlttime * 3600)));
$crawltresult = $connexion-> query("SELECT id FROM crawlt_hits
	WHERE  date='".crawlt_sql_quote($connexion, $crawlttodaylocal)."'
	AND idsite='".crawlt_sql_quote($connexion, $crawltsite)."'");
$crawltnum_rows = $crawltresult->num_rows;
if($crawltnum_rows>0)
{
	//the date already exist in the table
	while ($crawltligne = $crawltresult->fetch_row())
	{
		$crawltid=$crawltligne[0];
	}
	//add 1 in the date count
	$crawltsqlupdate="UPDATE crawlt_hits SET count=count+1 WHERE id='".crawlt_sql_quote($connexion, $crawltid)."'";
	$crawltrequeteupdate = $connexion->query($crawltsqlupdate);
}
else
{
	//the link didn't exist in the table, create it
	$crawltsql="INSERT INTO crawlt_hits ( count,date, idsite) VALUES ('1','".crawlt_sql_quote($connexion, $crawlttodaylocal)."','".crawlt_sql_quote($connexion, $crawltsite)."')";
	$crawltrequete = $connexion->query($crawltsql);
}
//---------------------------------------------------------------------
//check if it's an attack
$crawlturl3 = str_replace($crawltcssaattack,'http:',$crawlturl2);
$crawlturl4 = str_replace($crawltsqlaattack,'%20select%20',$crawlturl2);
if(preg_match("/http\:/i", ltrim($crawlturl3,"h")))
{
	$crawlttypeattack=65500;
	$crawltattack=1;
	$crawlturl=$crawlturl2;
	$crawltnbrattack = substr_count($crawlturl3,'http:');
	$crawltnbrgoodsite=0;
	foreach($crawltlistgoodsite as $crawltgoodsite)
	{
		if(strpos($crawlturl, $crawltgoodsite))
		{
			$crawltnbrgoodsite++;
		}
	}
	if($crawltnbrgoodsite == $crawltnbrattack && $crawltnbrattack !=0)
	{
		$crawltattack=0;
	}
}
elseif(preg_match("/%20select%20/i", $crawlturl4))
{
	$crawlttypeattack=65501;
	$crawltattack=1;
	$crawlturl=$crawlturl2;
	$crawltnbrattack=substr_count($crawlturl, 'http:');
	$crawltnbrgoodsite=0;
	foreach($crawltlistgoodsite as $crawltgoodsite)
	{
		if(strpos($crawlturl, $crawltgoodsite))
		{
			$crawltnbrgoodsite++;
		}
	}
	if($crawltnbrgoodsite == $crawltnbrattack && $crawltnbrattack !=0)
	{
		$crawltattack=0;
	}
}
else
{
	$crawlttypeattack=0;
}
if($crawlttypeattack != 0 && $crawltattack==1)
{
	$crawltdate  = date("Y-m-d H:i:s");
	if($crawlthttpcode==404)
	{
		//we just count the number of 404 attack to avoid server overload and to big increase of database size
		//check if the date exist in the crawlt_error table for that site
		$crawltresult = $connexion->query("SELECT id FROM crawlt_error
		WHERE  date='".crawlt_sql_quote($connexion, $crawlttodaylocal)."'
		AND idsite='".crawlt_sql_quote($connexion, $crawltsite)."'
		AND attacktype='".crawlt_sql_quote($connexion, $crawlttypeattack)."'");
		$crawltnum_rows = $crawltresult->num_rows;
		if($crawltnum_rows>0)
		{
			//the date already exist in the table
			while ($crawltligne = $crawltresult->fetch_row())
			{
				$crawltid=$crawltligne[0];
			}
			//add 1 in the date count
			$crawltsqlupdate="UPDATE crawlt_error SET count=count+1
			WHERE id='".crawlt_sql_quote($connexion, $crawltid)."'";
			$crawltrequeteupdate = $connexion->query($crawltsqlupdate);
		}
		else
		{
			//the link didn't exist in the table, create it
			$crawltsql="INSERT INTO crawlt_error ( count,date, idsite, attacktype) VALUES ('1','".crawlt_sql_quote($connexion, $crawlttodaylocal)."','".crawlt_sql_quote($connexion, $crawltsite)."','".crawlt_sql_quote($connexion, $crawlttypeattack)."')";
			$crawltrequete = $connexion->query($crawltsql);
		}
	}
	else
	{
		//check if the page already exist, if not add it to the table
		$result2 = $connexion->query("SELECT id_page FROM crawlt_pages_attack WHERE url_page='".crawlt_sql_quote($connexion, $crawlturl)."'");
		$num_rows2 = $result2->num_rows;
		if ($num_rows2>0)
		{
			$crawltdata2 = $result2->fetch_row();
			$crawltpage= $crawltdata2[0];
		}
		else
		{
			$connexion->query("INSERT INTO crawlt_pages_attack (url_page) VALUES ('".crawlt_sql_quote($connexion, $crawlturl)."')");
			$crawltid_insert = mysqli_fetch_row($connexion->query("SELECT LAST_INSERT_ID()"));
			$crawltpage = $crawltid_insert[0];
		}
		//insertion of the visit datas in the visits database
		$connexion->query("INSERT INTO crawlt_visits (crawlt_site_id_site, crawlt_pages_id_page, crawlt_crawler_id_crawler, date, crawlt_ip_used, crawlt_error) VALUES ('".crawlt_sql_quote($connexion, $crawltsite)."', '".crawlt_sql_quote($connexion, $crawltpage)."', '".crawlt_sql_quote($connexion, $crawlttypeattack)."', '".crawlt_sql_quote($connexion, $crawltdate)."', '".crawlt_sql_quote($connexion, $crawltip)."','0')");
	}
}
else
{
	//treatment of ip to prepare the mysql request
	$crawltcptip=1;
	$crawltlgthip=strlen($crawltip);
	while($crawltcptip <=$crawltlgthip)
	{
		$crawlttableip[]=substr($crawltip,0,$crawltcptip);
		$crawltcptip++;
	}
	$crawltlistip=implode("','",$crawlttableip);
	// check if the user agent or the ip exist in the crawler table
	$result = $connexion->query("SELECT crawler_user_agent, crawler_ip,id_crawler FROM crawlt_crawler
	 WHERE INSTR('".crawlt_sql_quote($connexion, $crawltagent)."',crawler_user_agent) > 0
	OR crawler_ip IN ('$crawltlistip') ");
	$num_rows = $result->num_rows;
	if ($num_rows>0)
	{
		$crawltdata = $result->fetch_row();
		$crawltcrawler = $crawltdata[2];
		$crawltdate  = date("Y-m-d H:i:s");
		//check if the page already exist, if not add it to the table
		$result2 = $connexion->query("SELECT id_page FROM crawlt_pages WHERE url_page='".crawlt_sql_quote($connexion, $crawlturl)."'");
		$num_rows2 = $result2->num_rows;
		if ($num_rows2>0)
		{
			$crawltdata2 = $result2->fetch_row();
			$crawltpage= $crawltdata2[0];
		}
		else
		{
			$connexion->query("INSERT INTO crawlt_pages (url_page) VALUES ('".crawlt_sql_quote($connexion, $crawlturl)."')");
			$crawltid_insert = mysqli_fetch_row($connexion->query("SELECT LAST_INSERT_ID()"));
			$crawltpage = $crawltid_insert[0];
		}
		if($crawlthttpcode==404)
		{
			$crawlterror=1;
		}
		else
		{
			$crawlterror=0;
		}
		//insertion of the visit datas in the visits database
		$connexion->query("INSERT INTO crawlt_visits (crawlt_site_id_site, crawlt_pages_id_page, crawlt_crawler_id_crawler, date, crawlt_ip_used, crawlt_error) VALUES ('".crawlt_sql_quote($connexion, $crawltsite)."', '".crawlt_sql_quote($connexion, $crawltpage)."', '".crawlt_sql_quote($connexion, $crawltcrawler)."', '".crawlt_sql_quote($connexion, $crawltdate)."', '".crawlt_sql_quote($connexion, $crawltip)."', '".crawlt_sql_quote($connexion, $crawlterror)."')");
	}
}
//Email daily stats
//take in account timeshift
$crawltts = time()-($crawlttime*3600);
$crawltdatetoday = date("j",$crawltts);
$crawltdatetoday2 = date("Y-m-d",$crawltts);
$url_crawlt="URL_CRAWLTRACK";
if(($crawltdatetoday != $crawltdatelastseorequest) && $crawltcheck==1)
{
	$crawltpath="FILE_PATH";
	require_once("FILE_PATH/include/searchenginesposition.php");
}
if(($crawltdatetoday != $crawltdatemail) && $crawltmail==1 && ($crawltdatetoday == $crawltdatelastseorequest) && $crawltcheck==1)
{
	$crawltpath="FILE_PATH";
	require_once("FILE_PATH/include/mail.php");
}
mysqli_close($connexion);
if($crawltattack==1 && $crawltblockattack==1 && $crawltpostrequest==1)
{
	echo "crawltrack1";
}
elseif($crawltattack==1 && $crawltblockattack==1)
{
	$GLOBALS = array();
	$_COOKIES = array();
	$_FILES = array();
	$_ENV = array();
	$_REQUEST = array();
	$_POST = array();
	$_GET = array();
	$_SERVER = array();
	$_SESSION = array();
	@session_destroy();
	@mysqli_close();
	@header("Location: URL_CRAWLTRACKhtml/noacces.php");
	echo "<head>";
	echo "<META HTTP-EQUIV='Refresh' CONTENT='0;URL=URL_CRAWLTRACKhtml/noacces.php'>";
	echo "</head>";
}
?>
