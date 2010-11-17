<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.6b
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
// file: menumain.php
//----------------------------------------------------------------------
// menu based on a www.alsacreations.com css menu
//----------------------------------------------------------------------
//  Last update: 19/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
$crawlencode = urlencode($crawler);
?>
<div class="menumain">
<?php
//specific menu according pages
if ($navig == 0 || $navig == 23) //dashboard menu
{
	$title = '';
?>
  <div id="menum4">
  	<dl>
  		<dt onmouseover="javascript:montre('smenu7');"><a href="index.php?navig=0&amp;period=<?php echo $period ?>&amp;site=<?php echo $site ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['display_period2'] ?></a></dt>
  			<dd id="smenu7">
  				<ul>

    					<li><a href="index.php?navig=<?php echo $navig ?>&amp;period=0&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['today'] ?></a></li>
    					<li><a href="index.php?navig=<?php echo $navig ?>&amp;period=4&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['8days'] ?></a></li>
    					<li><a href="index.php?navig=<?php echo $navig ?>&amp;period=1&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['days'] ?></a></li>
    					<li><a href="index.php?navig=<?php echo $navig ?>&amp;period=2&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['month'] ?></a></li>
    					<li><a href="index.php?navig=<?php echo $navig ?>&amp;period=3&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['one_year'] ?></a></li>
    					<li><a href="index.php?navig=<?php echo $navig ?>&amp;period=5&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><?php echo $language['since_beginning'] ?></a></li>

  				</ul>
  			</dd>
  	</dl>
  </div>
<?php
} elseif ($navig == 1 || $navig == 2 || $navig == 3 || $navig == 4 || $navig == 8 || $navig == 9) //crawler menu
{
	$logodisplay = 'bug.png';
?>
  <div id="menum7">
  <dl>
  		<dt onmouseover="javascript:montre();"><a href="index.php?navig=0&amp;period=<?php echo $period ?>&amp;site=<?php echo $site ?>&amp;crawler=<?php echo $crawlencode ?>&amp;graphpos=<?php echo $graphpos ?>"><img src="./images/house.png" width="16" height="16" border="0" title="<?php echo $language['home'] ?>" alt="<?php echo $language['home'] ?>"></a></dt>
  	</dl>
  </div>
<?php
	if ($navig == 1) {
		$title = 'crawler_name';
	} elseif ($navig == 2) {
		$title = htmlentities($crawler);
	} elseif ($navig == 3) {
		$title = 'nbr_pages';
	} elseif ($navig == 4) {
		$title = crawltcuturl($crawler, '55');
	} elseif ($navig == 8) {
		$title = 'origin';
	} elseif ($navig == 9) {
		$title = 'nbr_pages';
	}
	echo "<div id=\"menum6\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu7');\"><a href=\"index.php?navig=1&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['crawler_name'] . "</a></dt>\n";
	echo "			<dd id=\"smenu7\">\n";
	echo "				<ul>\n";
	if ($navig == 2) {
		echo "					<li><a href=\"index.php?navig=2&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=2&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=2&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=2&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=2&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=2&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	} else {
		echo "					<li><a href=\"index.php?navig=1&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=1&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=1&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=1&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=1&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=1&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	}
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum5\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu2');\"><a href=\"index.php?navig=3&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['nbr_pages'] . "</a></dt>\n";
	echo "			<dd id=\"smenu2\" >\n";
	echo "				<ul>\n";
	if ($navig == 4) {
		echo "					<li><a href=\"index.php?navig=4&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=4&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=4&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=4&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=4&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=4&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	} else {
		echo "					<li><a href=\"index.php?navig=3&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=3&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=3&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=3&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=3&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=3&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	}
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum4\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu3');\"><a href=\"index.php?navig=8&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['origin'] . "</a></dt>\n";
	echo "			<dd id=\"smenu3\">\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"index.php?navig=8&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=8&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=8&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=8&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=8&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=8&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
} elseif ($navig == 11) //indexation menu
{
	$logodisplay = 'report_magnify.png';
	$title = 'index';
	echo "<div id=\"menum8\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=0&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/house.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['home'] . "\" alt=\"" . $language['home'] . "\"></a></dt>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum4\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu7');\"><a href=\"index.php?navig=11&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['index'] . "</a></dt>\n";
	echo "			<dd id=\"smenu7\">\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"index.php?navig=11&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=11&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=11&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=11&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=11&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=11&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
} elseif ($navig == 17 || $navig == 18 || $navig == 19) //hacking attempts menu
{
	$logodisplay = 'hacker.png';
	echo "<div id=\"menum8\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=0&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/house.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['home'] . "\" alt=\"" . $language['home'] . "\"></a></dt>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	if ($navig == 17) {
		$title = 'hacking2';
	} elseif ($navig == 18) {
		$title = 'hacking3';
	} elseif ($navig == 19) {
		$title = 'hacking4';
	}
	echo "<div id=\"menum4\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu7');\"><a href=\"index.php?navig=17&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['hacking'] . "</a></dt>\n";
	echo "			<dd id=\"smenu7\">\n";
	echo "				<ul>\n";
	if ($navig == 18) {
		echo "					<li><a href=\"index.php?navig=18&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=18&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=18&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=18&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=18&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=18&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	} elseif ($navig == 19) {
		echo "					<li><a href=\"index.php?navig=19&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=19&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=19&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=19&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=19&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=19&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	} else {
		echo "					<li><a href=\"index.php?navig=17&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=17&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=17&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=17&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=17&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=17&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	}
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
} elseif ($navig == 20 || $navig == 12 || $navig == 13 || $navig == 14 || $navig == 16 || $navig == 21) //visitors menu
{
	$logodisplay = 'group.png';
	echo "<div id=\"menum10\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=0&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/house.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['home'] . "\" alt=\"" . $language['home'] . "\"></a></dt>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	if ($navig == 20) {
		$title = 'visitors';
	} elseif ($navig == 12) {
		$title = 'keyword';
	} elseif ($navig == 13) {
		$title = 'entry-page';
	} elseif ($navig == 14) {
		$title = crawltcuturl($crawler, '55');
	} elseif ($navig == 16) {
		$title = crawltcuturl($crawler, '55');
	} elseif ($navig == 21) {
		$title = 'nbr_pages';
	}
	echo "<div id=\"menum11\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu4');\"><a href=\"index.php?navig=20&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['visitors'] . "</a></dt>\n";
	echo "			<dd id=\"smenu4\">\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"index.php?navig=20&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=20&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=20&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=20&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=20&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=20&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum6\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu7');\"><a href=\"index.php?navig=21&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['nbr_pages'] . "</a></dt>\n";
	echo "			<dd id=\"smenu7\">\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"index.php?navig=21&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=21&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=21&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=21&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=21&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=21&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum5\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu2');\"><a href=\"index.php?navig=12&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['keyword'] . "</a></dt>\n";
	echo "			<dd id=\"smenu2\" >\n";
	echo "				<ul>\n";
	if ($navig == 16) {
		echo "					<li><a href=\"index.php?navig=16&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=16&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=16&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=16&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=16&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=16&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	} else {
		echo "					<li><a href=\"index.php?navig=12&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=12&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=12&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=12&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=12&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=12&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	}
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum4\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu3');\"><a href=\"index.php?navig=13&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['entry-page'] . "</a></dt>\n";
	echo "			<dd id=\"smenu3\">\n";
	echo "				<ul>\n";
	if ($navig == 14) {
		echo "					<li><a href=\"index.php?navig=14&amp;period=0&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=14&amp;period=4&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=14&amp;period=1&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=14&amp;period=2&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=14&amp;period=3&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=14&amp;period=5&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	} else {
		echo "					<li><a href=\"index.php?navig=13&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=13&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=13&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=13&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=13&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
		echo "					<li><a href=\"index.php?navig=13&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	}
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
} elseif ($navig == 22) //error menu
{
	$logodisplay = 'error.png';
	$title = 'error';
	echo "<div id=\"menum8\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=0&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/house.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['home'] . "\" alt=\"" . $language['home'] . "\"></a></dt>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	echo "<div id=\"menum4\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre('smenu7');\"><a href=\"index.php?navig=22&amp;period=$period&amp;site=$site&amp;graphpos=$graphpos\">" . $language['error'] . "</a></dt>\n";
	echo "			<dd id=\"smenu7\">\n";
	echo "				<ul>\n";
	echo "					<li><a href=\"index.php?navig=22&amp;period=0&amp;site=$site&amp;graphpos=$graphpos\">" . $language['today'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=22&amp;period=4&amp;site=$site&amp;graphpos=$graphpos\">" . $language['8days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=22&amp;period=1&amp;site=$site&amp;graphpos=$graphpos\">" . $language['days'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=22&amp;period=2&amp;site=$site&amp;graphpos=$graphpos\">" . $language['month'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=22&amp;period=3&amp;site=$site&amp;graphpos=$graphpos\">" . $language['one_year'] . "</a></li>\n";
	echo "					<li><a href=\"index.php?navig=22&amp;period=5&amp;site=$site&amp;graphpos=$graphpos\">" . $language['since_beginning'] . "</a></li>\n";
	echo "				</ul>\n";
	echo "			</dd>\n";
	echo "	</dl>\n";
	echo "</div>\n";
} elseif ($navig == 6 || $navig == 5) {
	echo "<div id=\"menum9\">\n";
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=0&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/house.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['home'] . "\" alt=\"" . $language['home'] . "\"></a></dt>\n";
	echo "	</dl>\n";
	echo "</div>\n";
	$title = '';
}

//part of the menu common to all pages
if ($navig != 6 && $navig != 5) {
	echo "<div class=\"dashboard\"><br>\n";
	echo crawltbackforward($title, $period, $daytodaylocal, $monthtodaylocal, $yeartodaylocal, $daybeginlocal, $monthbeginlocal, $yearbeginlocal, $dayendweek, $monthendweek, $yearendweek, $crawler, $navig, $site, $graphpos);
	echo "</div><br>\n";
}
echo "<div id=\"menud2\">\n";
echo "	<dl>\n";
echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=5&amp;site=$site&amp;graphpos=$graphpos\"><img src=\"./images/magnifier.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['magnifier'] . "\" alt=\"" . $language['magnifier'] . "\"></a></dt>\n";
echo "	</dl>\n";
echo "	<dl>\n";
echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"./php/refresh.php?navig=$navig&amp;period=$period&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/refresh.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['refresh'] . "\" alt=\"" . $language['refresh'] . "\"></a></dt>\n";
echo "	</dl>\n";
echo "	<dl>\n";
echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=6&amp;site=$site&amp;crawler=$crawlencode&amp;graphpos=$graphpos\"><img src=\"./images/wrench.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['wrench'] . "\" alt=\"" . $language['wrench'] . "\"></a></dt>\n";
echo "	</dl>\n";
echo "	<dl>\n";
echo "		<dt onmouseover=\"javascript:montre();\" onclick=\"window.print()\"><a href=\"#\"><img src=\"./images/printer.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['printer'] . "\" alt=\"" . $language['printer'] . "\"></a></dt>\n";
echo "	</dl>\n";
echo "	<dl>\n";
if ($crawltlang == 'french' || $crawltlang == 'frenchiso') {
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"http://www.crawltrack.net/fr/documentation.php\"><img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['information'] . "\" alt=\"" . $language['information'] . "\"></a></dt>\n";
} else {
	echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"http://www.crawltrack.net/documentation.php\"><img src=\"./images/information.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['information'] . "\" alt=\"" . $language['information'] . "\"></a></dt>\n";
}
echo "	</dl>\n";
if ($crawltlang == 'french' || $crawltlang == 'frenchiso') {
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\" onclick=\"return window.open('./html/infofr.htm','CrawlTrack','top=300,left=350,height=200,width=350')\"><a href=\"#\"><img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['help'] . "\" alt=\"" . $language['help'] . "\"></a></dt>\n";
	echo "	</dl>\n";
} else {
	echo "	<dl>\n";
	echo "		<dt onmouseover=\"javascript:montre();\" onclick=\"return window.open('./html/infoen.htm','CrawlTrack','top=300,left=350,height=200,width=350')\"><a href=\"#\"><img src=\"./images/help.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['help'] . "\" alt=\"" . $language['help'] . "\"></a></dt>\n";
	echo "	</dl>\n";
}
echo "	<dl>\n";
echo "		<dt onmouseover=\"javascript:montre();\"><a href=\"index.php?navig=7\"><img src=\"./images/cross.png\" width=\"16\" height=\"16\" border=\"0\" title=\"" . $language['cross'] . "\" alt=\"" . $language['cross'] . "\"></a></dt>\n";
echo "	</dl>\n";
echo "</div>\n";
echo "<br><br><br>\n";
echo "</div>\n";

//printing
if ($navig != 6 && $navig != 5) {
	echo "<div class=\"dashboardprint\"><br><br>\n";
	echo crawltbackforward($title, $period, $daytodaylocal, $monthtodaylocal, $yeartodaylocal, $daybeginlocal, $monthbeginlocal, $yearbeginlocal, $dayendweek, $monthendweek, $yearendweek, $crawler, $navig, $site, $graphpos);
	echo "<br></div>\n";
}
if ($navig != 6) {
	echo "<br>\n";
}
?>
