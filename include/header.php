<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.8
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
// file: header.php
//----------------------------------------------------------------------
//  Last update: 02/12/2010
//----------------------------------------------------------------------
if ($crawltcharset == 1) {
	header('Content-Type: text/html; charset=utf-8');
} else {
	header('Content-Type: text/html; charset=iso-8859-1');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>CrawlTrack</title>
	<meta name="author" content="Jean-Denis Brun">
	<meta name="description" content="CrawlTrack spiders and crawlers tracker, web analytics and SEO script">
	<meta name="keywords" content="crawler,tracker,webmaster,statistics,robots,site,webmestre,statistiques,searchengines,moteur de recherche">
	<?php
	if( $language['go_install']=="Installer")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"fr\">\n";
		}
	elseif( $language['go_install']=="Installation")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"de\">\n";
		}
	elseif( $language['go_install']=="Installeren")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"nl\">\n";
		}
	elseif( $language['go_install']=="Instalar")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"es\">\n";
		}
	elseif( $language['go_install']=="Kur")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"tr\">\n";
		}
	elseif( $language['go_install']=="Установить")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"ru\">\n";
		}
	elseif( $language['go_install']=="Инсталация")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"bg\">\n";
		}
	elseif( $language['go_install']=="Istalla")
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"it\">\n";
		}	
	else
		{
		echo"<meta http-equiv=\"Content-Language\" content=\"en\">\n";
		}
	 if (isset($crawltcharset)): ?>
		<?php if ($crawltcharset == 1): ?>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<?php else: ?>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<?php endif ?>
	<?php else:?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<?php endif ?>
	<link rel="stylesheet" type="text/css" href="./styles/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./styles/imprim.css" media="print" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="./styles/ie.css" />
	<![endif]-->
	<script type="text/javascript">
		<!--
		function montre(id) {
		var d = document.getElementById(id);
			for (var i = 1; i<=300; i++) {
				if (document.getElementById('smenu'+i)) {document.getElementById('smenu'+i).style.display='none';}
			}
		if (d) {d.style.display='block';}
		}
		//-->
	</script>
</head>
<body>
<div class="main">
<div class="header" onmouseover="javascript:montre();">
CrawlTrack <span class="headertext"><?php echo $language['webmaster_dashboard'] ?></span>
</div>
