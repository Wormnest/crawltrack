<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.6
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
// file: admintime.php
//----------------------------------------------------------------------
//  Last update: 12/09/2010
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT_ADMIN')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
if ($validlogin == 0) {
	echo "<h1>" . $language['time_set_up'] . "</h1>\n";
	$hre = Date("H");
	$mn = Date("i");
	$day = Date("d");
	$month = Date("m");
	$year = Date("Y");
	echo "<p>" . $language['server_time'] . " " . date("d/m/Y H:i:s") . "</p>\n";
?>

	<script language="Javascript">
	function formate2(nombre) {
	return (nombre < 10) ? "0" + nombre : nombre;
	}
	Date.prototype.display_date_hour = function() {
		var ch = formate2(this.getDate()) + "/" + formate2(eval(this.getMonth() + 1)) + "/" + this.getFullYear();
		ch += " " + formate2(this.getHours()) + ":" + formate2(this.getMinutes()) + ":" + formate2(this.getSeconds());
		return ch;
	}
	var localtime = new Date();
	var hre=localtime.getHours();
	var hreserv=<?php echo $hre; ?>;
	var dayserv=<?php echo $day; ?>;
	var monthserv=<?php echo $month; ?>;
	var anserv=<?php echo $year; ?>;
	var mn=localtime.getMinutes();
	var day=localtime.getDate();
	var month=localtime.getMonth()+1;
	var an=localtime.getFullYear();

	if (mn >50 && <?php echo $mn; ?><10)
		{
		hre=hre+1
		}
	if(hre==24)
		{
		hre=0;
		day=day+1;
		}
	if(day==32)
		{
		day=1;
		month=month+1;
		}
	if(day==31 && (month==04 || month==06 || month==09 || month==11))
		{
		day=1;
		month=month+1;
		}
	if(day==29 && month==02 && an % 4 != 0)
		{
		day=1;
		month=month+1;
		}
	if(day==30 && month==02 && an % 4 == 0)
		{
		day=1;
		month=month+1;
		}	
		
	if(month==13)
		{
		month=1;
		}
				
	if (<?php echo $mn; ?> >50 && mn<10)
		{
		hreserv=hreserv+1
		}
	if(hreserv==24)
		{
		hreserv=0;
		dayserv=dayserv+1;
		}
	if(dayserv==32)
	{
		dayserv=1;
		monthserv=monthserv+1;
	}
	if(dayserv==31 && (monthserv==04 || monthserv==06 || monthserv==09 || monthserv==11))
	{
		dayserv=1;
		monthserv=monthserv+1;
	}
	if(dayserv==29 && monthserv==02 && an % 4 != 0)
	{
		dayserv=1;
		monthserv=monthserv+1;
	}
	if(dayserv==30 && monthserv==02 && an % 4 == 0)
		{
		dayserv=1;
		monthserv=monthserv+1;
		}
	if(monthserv==13)
	{
		monthserv=1;
	}
	
	
	if ( day == dayserv)
	{
		var diffh = Math.round(hreserv-hre);
	}
	if( day > dayserv && month == monthserv)
	{
		var diffh = Math.round(hreserv-24-hre);
	}
	if ( day < dayserv && month == monthserv)
	{
		var diffh = Math.round(hreserv-hre+24);
	}
	if ( month > monthserv)
	{
		var diffh = Math.round(hreserv-24-hre);
	}
	if ( month < monthserv && month !=1)
	{
		var diffh = Math.round(hreserv-hre+24);
	}
	if ( month < monthserv && month ==1)
	{
		var diffh = Math.round(hreserv-24-hre);
	}


	document.write("<p><?php echo $language['local_time']; ?> " + localtime.display_date_hour() + "</p>");
	document.write("<p><?php echo $language['time_difference']; ?> " + diffh + "</p>");

	<?php
	if ($times == 0) {
	?>
	document.write("<h5><?php echo $language['time_server']; ?></h5>");
	document.write("<h2><a href='index.php?decal=" + diffh + "&amp;navig=6&amp;validform=18&amp;validlogin=1'><?php echo $language['yes']; ?></a>&nbsp;&nbsp;&nbsp;<a href='index.php?navig=6'><?php echo $language['no']; ?></a></h2><br><br>");
	<?php
	} else {
	?>
	document.write("<h5><?php echo $language['time_local']; ?></h5>");
	document.write("<h2><a href='index.php?decal=" + diffh + "&amp;navig=6&amp;validform=18&amp;validlogin=2'><?php echo $language['yes']; ?></a>&nbsp;&nbsp;&nbsp;<a href='index.php?navig=6'><?php echo $language['no']; ?></a></h2><br><br>");
	<?php
	}
?>
	</script>
	<noscript>
	<?php echo "<h1> " . $language['need_javascript'] . " </h1>"; ?>
	</noscript>

	<?php
} elseif ($validlogin == 1) {
	$decal = (int)$_GET['decal'];
	//update the crawlt_config_table
	
	//database connection
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	$sqltime = "UPDATE crawlt_config SET timeshift='" . sql_quote($decal) . "'";
	$requetetime = db_query($sqltime, $connexion);
	
	//empty the cache table
	$sqlcache = "TRUNCATE TABLE crawlt_cache";
	$requetecache = db_query($sqlcache, $connexion);
	mysql_close($connexion);
	echo "<h1>" . $language['time_set_up'] . "</h1>\n";
	echo "<p>" . $language['decal_ok'] . "</p><br>\n";
} elseif ($validlogin == 2) {
	//update the crawlt_config_table
	
	//database connection
	$connexion = mysql_connect($crawlthost, $crawltuser, $crawltpassword) or die("MySQL connection to database problem");
	$selection = mysql_select_db($crawltdb) or die("MySQL database selection problem");
	
	$sqltime2 = "UPDATE crawlt_config SET timeshift='0'";
	$requetetime2 = db_query($sqltime2, $connexion);
	
	//empty the cache table
	$sqlcache = "TRUNCATE TABLE crawlt_cache";
	$requetecache = db_query($sqlcache, $connexion);
	mysql_close($connexion);
	echo "<h1>" . $language['time_set_up'] . "</h1>\n";
	echo "<p>" . $language['nodecal_ok'] . "</p><br>\n";
}
?>
