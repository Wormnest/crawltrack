<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.3.0
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
// file: footer.php
//----------------------------------------------------------------------
//  Last update: 09/04/2011
//----------------------------------------------------------------------

?>
</div>
<?php include ("include/sponsors.php"); ?>
	<div class="footer">
		<table width="100%">
			<tr><td width="33%">&nbsp;</td><td valign="top">
			<a href="http://www.crawltrack.net" onclick="window.open(this.href);return(false);">CrawlTrack</a>
			</td><td align="right" valign="top" width="33%">
			<?php
			if (!isset($crawlencode)) {
				$crawlencode = '';
			}
			?>
			<a href="index.php?navig=<?php echo $settings->navig ?>&amp;graphpos=<?php echo $settings->graphpos ?>&amp;period=<?php echo $settings->period ?>&amp;site=<?php echo $settings->siteid ?>&amp;crawler=<?php echo $crawlencode ?>"><img src="./images/star.png" width="16" height="16" border="0" title="<?php echo $language['bookmark'] ?>" alt="<?php echo $language['bookmark'] ?>" /></a>
			</td></tr>
		</table>
	</div>
</div>
