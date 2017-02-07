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
// file: updateurl.php
//----------------------------------------------------------------------

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

//initialize array
$namesite = array();
$urlsite = array();
$listidsite = array();

echo "<div class=\"content\">\n";
if ($validsite == 0) {
	//database connection
	require_once("jgbdb.php");
	$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
	
	$sqlsite = "SELECT * FROM crawlt_site";
	$requetesite = db_query($sqlsite, $connexion);
	$nbrresult = $requetesite->num_rows;
	if ($nbrresult >= 1) {
		while ($ligne = $requetesite->fetch_object()) {
			$sitename = $ligne->name;
			$siteurl = $ligne->url;
			$siteid = $ligne->id_site;
			$namesite[$siteid] = $sitename;
			$urlsite[$siteid] = $siteurl;
			$listidsite[] = $siteid;
		}
	}
	mysqli_close($connexion);

	echo "<p>" . $language['set_up_url'] . "</p>\n";
	echo "<form action=\"index.php\" method=\"POST\" >\n";
	echo "<input type=\"hidden\" name ='navig' value=\"10\">\n";
	echo "<input type=\"hidden\" name ='validsite' value=\"1\">\n";
	echo "<table class=\"centrer\">\n";
	$i = 0;
	foreach ($listidsite as $idsite) {
		echo "<tr>\n";
		echo "<td>" . $language['site_name'] . "</td>\n";
		echo "<td><input name='sitename" . $i . "'  value='$namesite[$idsite]' type='text' maxlength='45' size='50'/></td>\n";
		echo "</tr>\n";
		echo "<tr>\n";
		echo "<tr>\n";
		echo "<td>" . $language['site_url'] . "</td>\n";
		echo "<td><input name='siteurl" . $i . "'  value='$urlsite[$idsite]' type='text' maxlength='250' size='50'/></td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "<input type=\"hidden\" name ='nbrsite' value=\"$i\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">\n";
	echo "<br>\n";
	echo "<input name='ok' type='submit'  value=' OK ' size='20'>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo "</form>\n";
} else {
	if (isset($_POST['nbrsite'])) {
		$nbrsite = $_POST['nbrsite'];
	} else {
		$nbrsite = '0';
	}
	for ($i = 0;$i <= $nbrsite;$i++) {
		$post = "sitename" . $i;
		if (isset($_POST[$post])) {
			$sitename = $_POST[$post];
		} else {
			$sitename = '';
		}
		$post2 = "siteurl" . $i;
		if (isset($_POST[$post2])) {
			$siteurl = $_POST[$post2];
		} else {
			$siteurl = '';
		}
		//database connection
		require_once("jgbdb.php");
		$connexion = db_connect($crawlthost, $crawltuser, $crawltpassword, $crawltdb);
		
		$sqlupdatesite = "UPDATE crawlt_site SET url='" . sql_quote($siteurl) . "' WHERE name='" . sql_quote($sitename) . "'";
		$requeteupdatesite = db_query($sqlupdatesite, $connexion);
		mysqli_close($connexion);
	}
	?>
	<p><?php echo $language['update']; ?></p>
	
	<!-- continue -->
	<div class="form">
	<form action="index.php" method="POST" >
		<table class="centrer">
		<tr>
		<td colspan="2">
		<input name="ok" type="submit"  value="ok" size="20">
		</td>
		</tr>
		</table>
	</form>
	</div>
<?php } ?>
