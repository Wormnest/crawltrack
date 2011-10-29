<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.1
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
//  Last update: 29/10/2011
//----------------------------------------------------------------------
error_reporting(0);
//initialize array
//call back the page
$keywordurl=$_GET['q'];
$crawltlang=$_GET['lang'];
require_once('JSON.phps');
$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&q=" . rawurlencode($keywordurl);
		
$handle = fopen($url, 'rb');
$body = '';
while (!feof($handle)) {
$body .= fread($handle, 8192);
}
fclose($handle);

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
$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&start=5&q=" . rawurlencode($keywordurl);
	
$handle = fopen($url, 'rb');
$body = '';
while (!feof($handle)) {
$body .= fread($handle, 8192);
}
fclose($handle);

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
$url = "http://ajax.googleapis.com/ajax/services/search/web?v=1.0&start=9&q=" . rawurlencode($keywordurl);
		
$handle = fopen($url, 'rb');
$body = '';
while (!feof($handle)) {
$body .= fread($handle, 8192);
}
fclose($handle);

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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" type="text/css" href="stylegoogle.css" media="screen" />
</head>
<body>
<?php
include ("../language/" . $crawltlang . ".php");
echo "<h1>Google API</h1>";
echo "<h1>" . $language['keyword'] . ":<span class=\"browntitle\"> " . $keyworddisplay . "</span></h1>\n";
echo $formattedresults;
?>
</body>
</html>
