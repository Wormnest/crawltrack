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
// file: logochoice.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT_ADMIN') && !defined('IN_CRAWLT_INSTALL')) {
	exit('<h1>No direct access</h1>');
}
?>
<h1><?php echo $language['site_name2']; ?></h1>
<div class="form3">
<form action="index.php" method="POST">
<table>
<tr><td>
<?php
$sitechoice = 0;
foreach ($listsite as $siteid) {
	if ($sitechoice == 0) {
		echo '<input type="radio" name="site" value="' . $siteid . '" checked="checked" />' . $namesite[$siteid] . '<br /><br />'."\n";
	} else {
		echo '<input type="radio" name="site" value="' . $siteid . '" />' . $namesite[$siteid] . '<br /><br />'."\n";
	}
	$sitechoice = 1;
}
?>
</td></tr>
</table>

<!-- continue -->
<?php
if ($navig == 6) {
	$validform = 3;
} elseif ($navig == 15) {
	$validform = 7;
}
?>
<input type="hidden" name="navig" value="<?php echo $navig; ?>" />
<input type="hidden" name="validform" value="<?php echo $validform; ?>" />
<table>
<tr>
<td>
<input name="ok" type="submit" value="OK" size="40" />
</td>
</tr>
</table>
</form>
</div><br /><br />
