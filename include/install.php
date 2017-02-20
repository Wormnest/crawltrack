<?php
//----------------------------------------------------------------------
//  CrawlTrack 3.2.9
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
// file: install.php
//----------------------------------------------------------------------
//  Last update: 10/03/2011
//----------------------------------------------------------------------
if (!defined('IN_CRAWLT')) {
	exit('<h1>Hacking attempt !!!!</h1>');
}
// do not modify
define('IN_CRAWLT_INSTALL', TRUE);

if (isset($_POST['idmysql'])) {
	$idmysql = filter_input(INPUT_POST, 'idmysql', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
	$idmysql = '';
}
if (isset($_POST['passwordmysql'])) {
	// Filtering password is problematic. When storing in database we don't need to filter since we
	// will generate a hash. However here we save it in a .php variable.
	// TODO: What to do here.
	$passwordmysql = $_POST['passwordmysql'];
} else {
	$passwordmysql = '';
}
if (isset($_POST['hostmysql'])) {
	$hostmysql = filter_input(INPUT_POST, 'hostmysql', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
	$hostmysql = 'localhost';
}
if (isset($_POST['basemysql'])) {
	$basemysql = filter_input(INPUT_POST, 'basemysql', FILTER_SANITIZE_SPECIAL_CHARS);
} else {
	$basemysql = '';
}
?>
<div class="content">
<!-- test validity form -->
<?php if ($settings->validform == 1): ?>
	<!-- echo text -->
	<h1><?php echo $language['install']; ?></h1>
	<p><?php echo $language['welcome_install']; ?></p>
	<div align="left">
		<h5><?php echo $language['menu_install_1']; ?></h5>
		<h5><?php echo $language['menu_install_2']; ?></h5>
		<h5><?php echo $language['menu_install_3']; ?></h5>
	</div>
	<div class="form">
		<form action="index.php" method="POST">
			<input type="hidden" name="validform" value="2" />
			<input type="hidden" name="lang" value="<?php echo $settings->language; ?>" />
			<input name="ok" type="submit" value="<?php echo $language['go_install']; ?>" />
		</form>
		<br />
	</div>
<?php elseif($settings->validform == 2): ?>
	<!-- connection data collect -->
	<h1><?php echo $language['install']; ?></h1>
	<div align="left">
		<h5><?php echo $language['menu_install_1']; ?></h5>
		<h4><?php echo $language['menu_install_2']; ?></h4>
		<h4><?php echo $language['menu_install_3']; ?></h4>
	</div>
		<p><?php echo $language['step1_install']; ?></p>
</div>
	
	<!-- data collect form -->
	<div class="form">
		<form action="index.php" method="POST">
			<input type="hidden" name="validform" value="3" />
			<input type="hidden" name="navig" value="15" />
			<input type="hidden" name="lang" value="<?php echo $settings->language; ?>" />
			<table class="centrer">
				<tr>
				<td><?php echo $language['step1_install_login_mysql']; ?></td>
				<td><input name="idmysql" value="<?php echo $idmysql; ?>" type="text" size="50"/></td>
				</tr>
				<tr>
				<td><?php echo $language['step1_install_password_mysql']; ?></td>
				<td><input name="passwordmysql" value="<?php echo $passwordmysql; ?>" type="password" size="50" /></td>
				</tr>
				<tr>
				<td><?php echo $language['step1_install_host_mysql']; ?></td>
				<td><input name="hostmysql" value="<?php echo $hostmysql; ?>" type="text" size="50" /></td>
				</tr>
				<tr>
				<td><?php echo $language['step1_install_database_mysql']; ?></td>
				<td><input name="basemysql" value="<?php echo $basemysql; ?>" type="text" size="50" /></td>
				</tr>
				<tr>
				<td colspan="2">
				<br />
				<input name="ok" type="submit"  value=" OK " size="20" />
				</td>
				</tr>
			</table>
		</form><br />
<?php elseif($settings->validform == 3): ?>
	<!-- file and tables creation -->
	<h1><?php echo $language['install']; ?></h1>
	<div align="left">
		<h5><?php echo $language['menu_install_1']; ?></h5>
		<h4><?php echo $language['menu_install_2']; ?></h4>
		<h4><?php echo $language['menu_install_3']; ?></h4>
	</div>
	<?php include("include/createtable.php"); ?>
<?php elseif($settings->validform == 4): ?>
	<!-- site creation -->
	<h1><?php echo $language['install']; ?></h1>
	<div align="left">
		<h5><?php echo $language['menu_install_1']; ?></h5>
		<h5><?php echo $language['menu_install_2']; ?></h5>
		<h4><?php echo $language['menu_install_3']; ?></h4>
	</div>
	<?php include("include/createsite.php"); ?>
<?php elseif($settings->validform == 6): ?>
	<!-- user right -->
	<h1><?php echo $language['install']; ?></h1>
	<div align="left">
		<h5><?php echo $language['menu_install_1']; ?></h5>
		<h5><?php echo $language['menu_install_2']; ?></h5>
		<h5><?php echo $language['menu_install_3']; ?></h5>
	</div>
	<?php include("include/loginsetup.php"); ?>
<?php else: ?>
	<!-- language choice -->
	<br /><h1>Welcome in the CrawlTrack installation</h1>
	<br /><h2>First you have to choose your language:</h2><br />
	<div class="form">
		<form action="index.php" method="POST">
			<h1><table width="750px">        
				<tr><td align="left"><input type="radio" name="lang" value="english" /><img border="0" src="./images/flags/gb.gif" alt="english" width="18px" height="12px">&nbsp;&nbsp;English</td>
				<td align="left"><input type="radio" name="lang" value="spanish" /><img border="0" src="./images/flags/es.gif" alt="spanish" width="18px" height="12px">&nbsp;&nbsp;Spanish</td>
				<td align="left"><input type="radio" name="lang" value="german" /><img border="0" src="./images/flags/de.gif" alt="german" width="18px" height="12px">&nbsp;&nbsp;German</td></tr>
				<tr><td align="left"><input type="radio" name="lang" value="turkish" /><img border="0" src="./images/flags/tr.gif" alt="turkish" width="18" height="12px">&nbsp;&nbsp;Turkish</td>
				<td align="left"><input type="radio" name="lang" value="dutch" /><img border="0" src="./images/flags/nl.gif" alt="dutch" width="18px" height="12px">&nbsp;&nbsp;Dutch</td>
				<td align="left"><input type="radio" name="lang" value="russian" /><img border="0" src="./images/flags/ru.gif" alt="russian" width="18px" height="12px">&nbsp;&nbsp;Russian</td></tr>
				<tr><td align="left"><input type="radio" name="lang" value="bulgarian" /><img border="0" src="./images/flags/bg.gif" alt="bulgarian" width="18px" height="12px">&nbsp;&nbsp;Bulgarian</td>
				<td align="left"><input type="radio" name="lang" value="french" /><img border="0" src="./images/flags/fr.gif" alt="french" width="18px" height="12px">&nbsp;&nbsp;French</td>
				<td align="left"><input type="radio" name="lang" value="italian" /><img border="0" src="./images/flags/it.gif" alt="italian" width="18px" height="12px">&nbsp;&nbsp;Italian</td></tr>
			</table></h1>
			<input type="hidden" name="navig" value="6" />
			<input type="hidden" name="validform" value="1" />
			<input name="ok" type="submit" value="OK" style="width:100px"/>
		</form>
		<br /><br />
	</div>
<?php endif ?>
