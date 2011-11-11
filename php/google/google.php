<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.2
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
// file: google.php
//----------------------------------------------------------------------
//  Last update: 11/11/2011
//----------------------------------------------------------------------
error_reporting(0);
$times=0;
$period=0;
$formattedresults='';
include ("../../include/functions.php");
//call back the page
$keywordurl=$_GET['q'];
$crawltcharset=1;
$keyworddisplay = stripslashes(crawltcuturl($keywordurl, '55'));
$crawltlang=$_GET['lang'];

require_once('JSON.phps');

$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . rawurlencode($keywordurl);

$referer=$_SERVER['HTTP_HOST'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, $referer);
$body = curl_exec($ch);
curl_close($ch);
$json = new Services_JSON();
$json = $json->decode($body);


 if(is_array($json->responseData->results))
 	{
	foreach($json->responseData->results as $searchresult)
		{
		if($searchresult->GsearchResultClass == 'GwebSearch')
			{
			$formattedresults .= '
			<div class="google">
			<h3><a href="' . $searchresult->unescapedUrl . '">' . $searchresult->titleNoFormatting . '</a></h3>
			<p class="content">' . $searchresult->content . '</p>
			<p class="linkurl">' . $searchresult->visibleUrl . '</p>
			</div>';
			}
		}
		
	$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&start=5&q=" . rawurlencode($keywordurl);	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $referer);
	$body = curl_exec($ch);
	curl_close($ch);
	$json = new Services_JSON();
	$json = $json->decode($body);
	 
	foreach($json->responseData->results as $searchresult)
		{
		if($searchresult->GsearchResultClass == 'GwebSearch')
			{
			$formattedresults .= '
			<div class="google">
			<h3><a href="' . $searchresult->unescapedUrl . '">' . $searchresult->titleNoFormatting . '</a></h3>
			<p class="content">' . $searchresult->content . '</p>
			<p class="linkurl">' . $searchresult->visibleUrl . '</p>
			</div>';
			}
		}
	}
else
	{
	$formattedresults=$body;
	}

	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="stylegoogle.css" media="screen" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include ("../../language/" . $crawltlang . ".php");
echo "<h1>Google API</h1>";
echo "<h2>" . $language['keyword'] . ":<span class=\"browntitle\"> " . $keyworddisplay . "</span></h2>\n";
echo $formattedresults;
?>
</body>
</html>
