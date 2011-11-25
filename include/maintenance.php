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
// file: maintenance.php
//----------------------------------------------------------------------
//  Last update: 25/11/2011
//----------------------------------------------------------------------
// This file will manage all maintenance actions, database level (initial creation and updates)

// Tables information array, stores all tables informations and needed queries
// Special case : the insert queries can be replaced by a filename, which will be used instead.
// A fucntion can also be specified with the key 'execute_after', it'll be executed when an update is needed

// Error messages array, should be empty at the end
$tables_actions_error_messages = array();
$fields_actions_error_messages = array();
$index_actions_error_messages = array();

// Special cases for some tables
$existing_crawlt_config_table = true;
$existing_crawlt_update_attack_table = true;
$existing_crawlt_update_table = true;

// Spacial cases for fields
$existing_crawlt_site_url_field = true;

if(!isset($tables_to_check) || empty($tables_to_check))
{
	// The array wasn't defined earlier, so let's create a default one
	$tables_to_check = array(
		array(
			'table_name' => 'crawlt_crawler',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_crawler (
				id_crawler SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
				crawler_user_agent VARCHAR(255) NULL,
				crawler_name VARCHAR(45) NULL,
				crawler_url VARCHAR(255) NULL,
				crawler_info VARCHAR(255) NULL,
				crawler_ip VARCHAR(16) NULL,
				PRIMARY KEY(id_crawler),
				KEY crawler_info (crawler_info),
				KEY crawler_ip (crawler_ip),
				KEY crawler_name (crawler_name),
				KEY crawler_url (crawler_url),
				KEY crawler_user_agent (crawler_user_agent)
				)",
			'insert_query' => "crawlers.sql"
		),
		array(
			'table_name' => 'crawlt_attack',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_attack (
				id_attack int(10) unsigned NOT NULL,
				attack varchar(255) NOT NULL,
				script varchar(255) NOT NULL,
				`type` varchar(5) NOT NULL,
				PRIMARY KEY  (id_attack),
				KEY attack (attack),
				KEY script (script),
				KEY `type` (`type`)
			)",
			'insert_query' => "attacks.sql"
		),
		array(
			'table_name' => 'crawlt_login',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_login (
				id_login INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				crawlt_user VARCHAR(20) NULL,
				crawlt_password VARCHAR(45) NULL,
				admin SMALLINT UNSIGNED NULL,
				site SMALLINT UNSIGNED NULL,
				PRIMARY KEY(id_login)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_pages',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_pages (
				id_page int(10) unsigned NOT NULL auto_increment,
				url_page varchar(255) default NULL,
				KEY url_page (url_page),
				KEY id_page (id_page)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_site',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_site (
				id_site smallint(5) unsigned NOT NULL auto_increment,
				`name` varchar(45) NOT NULL default '',
				url varchar(255) default NULL,
				PRIMARY KEY  (id_site)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_visits',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_visits (
				id_visit int(8) unsigned NOT NULL auto_increment,
				crawlt_site_id_site tinyint(3) unsigned NOT NULL default '0',
				crawlt_pages_id_page mediumint(8) unsigned NOT NULL default '0',
				crawlt_crawler_id_crawler smallint(5) unsigned NOT NULL default '0',
				`date` datetime default NULL,
				crawlt_ip_used varchar(15) NOT NULL,
				crawlt_error tinyint(4) NOT NULL default '0',
				PRIMARY KEY  (id_visit),
				KEY crawlt_crawler_id_crawler (crawlt_crawler_id_crawler),
				KEY crawlt_ip_used (crawlt_ip_used),
				KEY crawlt_pages_id_page (crawlt_pages_id_page),
				KEY crawlt_site_id_site (crawlt_site_id_site),
				KEY `date` (`date`),
				KEY crawlt_error (crawlt_error)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_update',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_update (
				idcrawlt_update int(10) unsigned NOT NULL auto_increment,
				update_id int(10) unsigned default NULL,
				PRIMARY KEY  (idcrawlt_update)
			)",
			'insert_query' => "INSERT INTO crawlt_update VALUES (1,'97')",
			'execute_after' => 'update_crawlt_update'
		),
		array(
			'table_name' => 'crawlt_goodreferer',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_goodreferer (
				referer varchar(255) default NULL,
				id_site smallint(5) NOT NULL,
				KEY referer (referer),
				KEY id_site (id_site)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_config',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_config (
				id_config smallint(5) unsigned NOT NULL default '0',
				timeshift smallint(6) default NULL,
				public smallint(5) unsigned default NULL,
				mail smallint(5) unsigned default NULL,
				datelastmail smallint(5) unsigned default NULL,
				addressmail varchar(255) default NULL,
				lang varchar(20) default NULL,
				version int(10) unsigned default NULL,
				firstdayweek enum('Monday','Sunday') NOT NULL default 'Monday',
				datelastseorequest smallint(5) NOT NULL default '0',
				loop1 smallint(5) NOT NULL default '0',
				loop2 smallint(5) NOT NULL default '0',
				datelastcleaning datetime NOT NULL default '0000-00-00 00:00:00',
				rowdisplay smallint(5) NOT NULL default '0',
				orderdisplay smallint(5) NOT NULL default '0',
				typemail smallint(5) NOT NULL default '1',
				typecharset smallint(5) NOT NULL default '1',
				blockattack smallint(5) unsigned NOT NULL default '0',
				sessionid smallint(5) unsigned NOT NULL default '0',
				includeparameter smallint(5) unsigned NOT NULL default '0',
				PRIMARY KEY  (id_config)
				)",
			'insert_query' => "INSERT INTO crawlt_config (id_config, timeshift, public, mail, datelastmail, addressmail, lang, version, firstdayweek, rowdisplay, orderdisplay, typemail, typecharset, blockattack, sessionid, includeparameter) 
			VALUES ('1','0','0','0','0','','".sql_quote($crawltlang)."','332','Monday','30','0','1','1','0','0','0')"
		),
		array(
			'table_name' => 'crawlt_update_attack',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_update_attack (
				idcrawlt_update INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				update_id INTEGER UNSIGNED NULL,
				PRIMARY KEY(idcrawlt_update)
				)",
			'insert_query' => "INSERT INTO crawlt_update_attack VALUES (1,'8')",
			'execute_after' => 'update_crawlt_update_attack'
		),
		array(
			'table_name' => 'crawlt_cache',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_cache (
				cachename VARCHAR(255) NOT NULL,
				time INT NULL,
				PRIMARY KEY(cachename),
				KEY `time` (`time`)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_graph',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_graph (
				name varchar(255) NOT NULL default '',
				graph_values blob NOT NULL,
				KEY name (name)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_keyword',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_keyword (
				id_keyword int(10) unsigned NOT NULL auto_increment,
				keyword varchar(150) default NULL,
				PRIMARY KEY  (id_keyword),
				KEY id_keyword (id_keyword)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_seo_position',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_seo_position (
				`date` date default NULL,
				id_site smallint(5) NOT NULL default '0',
				linkyahoo int(10) unsigned default NULL,
				pageyahoo int(10) unsigned default NULL,
				linkmsn int(10) unsigned default NULL,
				pagemsn int(10) unsigned default NULL,
				nbrdelicious int(10) unsigned default '0',
				tagdelicious varchar(255) NOT NULL default '',
				linkexalead int(10) NOT NULL default '0',
				pageexalead int(10) NOT NULL default '0',
				linkgoogle int(10) NOT NULL default '0',
				pagegoogle int(10) NOT NULL default '0',
				KEY `date` (`date`),
				KEY id_site (id_site),
				KEY linkmsn (linkmsn),
				KEY linkyahoo (linkyahoo),
				KEY nbrdelicious (nbrdelicious),
				KEY pagemsn (pagemsn),
				KEY pageyahoo (pageyahoo),
				KEY tagdelicious (tagdelicious),
				KEY linkexalead (linkexalead),
				KEY pageexalead (pageexalead),
				KEY linkgoogle (linkgoogle),
				KEY pagegoogle (pagegoogle)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_visits_human',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_visits_human (
				id_visit int(8) unsigned NOT NULL auto_increment,
				crawlt_site_id_site tinyint(3) unsigned NOT NULL default '0',
				crawlt_keyword_id_keyword mediumint(8) unsigned NOT NULL default '0',
				crawlt_id_crawler smallint(5) unsigned NOT NULL default '0',
				`date` datetime default NULL,
				crawlt_id_page mediumint(9) NOT NULL default '0',
				crawlt_id_referer mediumint(9) NOT NULL default '0',
				crawlt_ip varchar(15) NOT NULL default '0',
				crawlt_error tinyint(3) unsigned NOT NULL default '0',
				crawlt_browser tinyint(3) unsigned NOT NULL default '0',
				PRIMARY KEY  (id_visit),
				KEY crawlt_id_crawler (crawlt_id_crawler),
				KEY crawlt_id_page (crawlt_id_page),
				KEY crawlt_keyword_id_keyword (crawlt_keyword_id_keyword),
				KEY crawlt_site_id_site (crawlt_site_id_site),
				KEY `date` (`date`),
				KEY crawlt_id_referer (crawlt_id_referer),
				KEY crawlt_ip (crawlt_ip),
				KEY crawlt_error (crawlt_error),
				KEY crawlt_browser (crawlt_browser)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_sessionid',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_sessionid (
				id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				sessionid VARCHAR(45) NULL,
				PRIMARY KEY(id)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_badreferer',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_badreferer (
				id_referer int(10) unsigned NOT NULL auto_increment,
				referer varchar(255) default NULL,
				KEY referer (referer),
				KEY id_referer (id_referer)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_download',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_download (
				id smallint(6) NOT NULL auto_increment,
				link varchar(255) NOT NULL,
				count int(11) NOT NULL,
				`date` date NOT NULL,
				idsite smallint(5) NOT NULL,
				KEY id (id,link,count)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_error',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_error (
				id smallint(5) NOT NULL auto_increment,
				idsite smallint(5) NOT NULL,
				count int(10) NOT NULL,
				`date` date NOT NULL,
				attacktype smallint(5) unsigned NOT NULL,
				KEY id (id),
				KEY id_site (idsite),
				KEY `date` (`date`)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_good_sites',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_good_sites (
				id_site INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
				host_site VARCHAR(255) NULL,
				PRIMARY KEY(id_site),
				KEY host_site (host_site)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_hits',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_hits (
				id smallint(5) NOT NULL auto_increment,
				idsite smallint(5) NOT NULL,
				count int(10) NOT NULL,
				`date` date NOT NULL,
				KEY id (id),
				KEY id_site (idsite),
				KEY `date` (`date`)
			)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_pages_attack',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_pages_attack (
				id_page int(10) unsigned NOT NULL auto_increment,
				url_page varchar(255) default NULL,
				KEY url_page (url_page),
				KEY id_page (id_page)
				)",
			'insert_query' => ''
		),
		array(
			'table_name' => 'crawlt_referer',
			'action' => 'create',
			'create_delete_query' => "CREATE TABLE crawlt_referer (
				id_referer mediumint(8) unsigned NOT NULL auto_increment,
				referer varchar(255) default NULL,
				KEY referer (referer),
				KEY id_referer (id_referer)
				)",
			'insert_query' => ''
		),
	);
}

function update_crawlt_update()
{
	$result = mysql_query("UPDATE crawlt_update SET update_id='97' WHERE idcrawlt_update='1'");
	if($result)
	{
		if(mysql_affected_rows() == 0)
		{
			mysql_query("INSERT INTO crawlt_update VALUES (1,'97')") ;
		}
	}
}

function update_crawlt_update_attack()
{
	$result = mysql_query("UPDATE crawlt_update_attack SET update_id='8' WHERE idcrawlt_update='1'");
	if($result)
	{
		if(mysql_affected_rows() == 0)
		{
			mysql_query("INSERT INTO crawlt_update_attack VALUES (1,'8')") ;
		}
	}
}

// Missing fields used when updating
$fields_to_check = array(
	'crawlt_config' => array(
		array(
		'field_name' => 'firstdayweek',
		'add_query' => "ALTER TABLE crawlt_config ADD firstdayweek ENUM('Monday','Sunday') NOT NULL default 'Monday'"
		),
		array(
		'field_name' => 'datelastseorequest',
		'add_query' => "ALTER TABLE crawlt_config ADD datelastseorequest smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'loop1',
		'add_query' => "ALTER TABLE crawlt_config ADD loop1 smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'loop2',
		'add_query' => "ALTER TABLE crawlt_config ADD loop2 smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'datelastcleaning',
		'add_query' => "ALTER TABLE crawlt_config ADD datelastcleaning datetime NOT NULL default '0000-00-00 00:00:00'"
		),
		array(
		'field_name' => 'rowdisplay',
		'add_query' => "ALTER TABLE crawlt_config ADD rowdisplay smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'orderdisplay',
		'add_query' => "ALTER TABLE crawlt_config ADD orderdisplay smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'typemail',
		'add_query' => "ALTER TABLE crawlt_config ADD typemail smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'typecharset',
		'add_query' => "ALTER TABLE crawlt_config ADD typecharset smallint(5) NOT NULL default '1'"
		),
		array(
		'field_name' => 'blockattack',
		'add_query' => "ALTER TABLE crawlt_config ADD blockattack smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'sessionid',
		'add_query' => "ALTER TABLE crawlt_config ADD sessionid smallint(5) NOT NULL default '0'"
		),
		array(
		'field_name' => 'includeparameter',
		'add_query' => "ALTER TABLE crawlt_config ADD includeparameter smallint(5) NOT NULL default '0'"
		)
	),
	'crawlt_visits' => array(
		array(
		'field_name' => 'crawlt_ip_used',
		'add_query' => "ALTER TABLE crawlt_visits ADD crawlt_ip_used VARCHAR(16)"
		),
		array(
		'field_name' => 'crawlt_error',
		'add_query' => "ALTER TABLE crawlt_visits ADD crawlt_error tinyint(4) NOT NULL default '0'"
		)
	),
	'crawlt_site' => array(
		array(
		'field_name' => 'url',
		'add_query' => "ALTER TABLE crawlt_site ADD url VARCHAR(255)"
		)
	),
	'crawlt_seo_position' => array(
		array(
		'field_name' => 'linkexalead',
		'add_query' => "ALTER TABLE crawlt_seo_position ADD linkexalead int(10) NOT NULL default '0'"
		),
		array(
		'field_name' => 'pageexalead',
		'add_query' => "ALTER TABLE crawlt_seo_position ADD pageexalead int(10) NOT NULL default '0'"
		),
		array(
		'field_name' => 'linkgoogle',
		'add_query' => "ALTER TABLE crawlt_seo_position ADD linkgoogle int(10) NOT NULL default '0'"
		),
		array(
		'field_name' => 'pagegoogle',
		'add_query' => "ALTER TABLE crawlt_seo_position ADD pagegoogle int(10) NOT NULL default '0'"
		)
	),
	'crawlt_visits_human' => array( 
		array(
		'field_name' => 'crawlt_id_referer',
		'add_query' => "ALTER TABLE crawlt_visits_human ADD crawlt_id_referer mediumint(9) NOT NULL default '0'"
		),
		array(
		'field_name' => 'crawlt_ip',
		'add_query' => "ALTER TABLE crawlt_visits_human ADD crawlt_ip varchar(15) NOT NULL default '0'"
		),
		array(
		'field_name' => 'crawlt_error',
		'add_query' => "ALTER TABLE crawlt_visits_human ADD crawlt_error tinyint(3) unsigned NOT NULL default '0'"
		),
		array(
		'field_name' => 'crawlt_browser',
		'add_query' => "ALTER TABLE crawlt_visits_human ADD crawlt_browser tinyint(3) unsigned NOT NULL default '0'"
		)
	)
);

// Indexes added when updating
$indexes_to_check = array(
	'crawlt_cache' => array(
		array(
		'index_name' => 'time',
		'add_query' => "CREATE INDEX `time` ON `crawlt_cache` (`time`)"
		)
	),
	'crawlt_visits' => array(
		array(
		'index_name' => 'crawlt_crawler_id_crawler',
		'add_query' => "CREATE INDEX `crawlt_crawler_id_crawler` ON `crawlt_visits` (`crawlt_crawler_id_crawler`)"
		),
		array(
		'index_name' => 'crawlt_ip_used',
		'add_query' => "CREATE INDEX `crawlt_ip_used` ON `crawlt_visits` (`crawlt_ip_used`)"
		),
		array(
		'index_name' => 'crawlt_pages_id_page',
		'add_query' => "CREATE INDEX `crawlt_pages_id_page` ON `crawlt_visits` (`crawlt_pages_id_page`)"
		),
		array(
		'index_name' => 'crawlt_site_id_site',
		'add_query' => "CREATE INDEX `crawlt_site_id_site` ON `crawlt_visits` (`crawlt_site_id_site`)"
		),
		array(
		'index_name' => 'crawlt_ip_used',
		'add_query' => "CREATE INDEX `crawlt_ip_used` ON `crawlt_visits` (`crawlt_ip_used`)"
		),
		array(
		'index_name' => 'date',
		'add_query' => "CREATE INDEX `date` ON `crawlt_visits` (`date`)"
		),
		array(
		'index_name' => 'crawlt_error',
		'add_query' => "CREATE INDEX `crawlt_error` ON `crawlt_visits` (`crawlt_error`)"
		)
	),
	'crawlt_keyword' => array(
		array(
		'index_name' => 'keyword',
		'add_query' => "CREATE INDEX `keyword` ON `crawlt_keyword` (`keyword`)"
		)
	),
	'crawlt_seo_position' => array(
		array(
		'index_name' => 'date',
		'add_query' => "CREATE INDEX `date` ON `crawlt_seo_position` (`date`)"
		),
		array(
		'index_name' => 'id_site',
		'add_query' => "CREATE INDEX `id_site` ON `crawlt_seo_position` (`id_site`)"
		),
		array(
		'index_name' => 'linkmsn',
		'add_query' => "CREATE INDEX `linkmsn` ON `crawlt_seo_position` (`linkmsn`)"
		),
		array(
		'index_name' => 'linkyahoo',
		'add_query' => "CREATE INDEX `linkyahoo` ON `crawlt_seo_position` (`linkyahoo`)"
		),
		array(
		'index_name' => 'nbrdelicious',
		'add_query' => "CREATE INDEX `nbrdelicious` ON `crawlt_seo_position` (`nbrdelicious`)"
		),
		array(
		'index_name' => 'pagemsn',
		'add_query' => "CREATE INDEX `pagemsn` ON `crawlt_seo_position` (`pagemsn`)"
		),
		array(
		'index_name' => 'pageyahoo',
		'add_query' => "CREATE INDEX `pageyahoo` ON `crawlt_seo_position` (`pageyahoo`)"
		),
		array(
		'index_name' => 'tagdelicious',
		'add_query' => "CREATE INDEX `tagdelicious` ON `crawlt_seo_position` (`tagdelicious`)"
		),
		array(
		'index_name' => 'linkexalead',
		'add_query' => "CREATE INDEX `linkexalead` ON `crawlt_seo_position` (`linkexalead`)"
		),
		array(
		'index_name' => 'pageexalead',
		'add_query' => "CREATE INDEX `pageexalead` ON `crawlt_seo_position` (`pageexalead`)"
		),
		array(
		'index_name' => 'linkgoogle',
		'add_query' => "CREATE INDEX `linkgoogle` ON `crawlt_seo_position` (`linkgoogle`)"
		),
		array(
		'index_name' => 'pagegoogle',
		'add_query' => "CREATE INDEX `pagegoogle` ON `crawlt_seo_position` (`pagegoogle`)"
		)
	),
	'crawlt_visits_human' => array(
		array(
		'index_name' => 'date',
		'add_query' => "CREATE INDEX `date` ON `crawlt_visits_human` (`date`)"
		),
		array(
		'index_name' => 'crawlt_id_crawler',
		'add_query' => "CREATE INDEX `crawlt_id_crawler` ON `crawlt_visits_human` (`crawlt_id_crawler`)"
		),
		array(
		'index_name' => 'crawlt_id_page',
		'add_query' => "CREATE INDEX `crawlt_id_page` ON `crawlt_visits_human` (`crawlt_id_page`)"
		),
		array(
		'index_name' => 'crawlt_keyword_id_keyword',
		'add_query' => "CREATE INDEX `crawlt_keyword_id_keyword` ON `crawlt_visits_human` (`crawlt_keyword_id_keyword`)"
		),
		array(
		'index_name' => 'crawlt_site_id_site',
		'add_query' => "CREATE INDEX `crawlt_site_id_site` ON `crawlt_visits_human` (`crawlt_site_id_site`)"
		),
		array(
		'index_name' => 'crawlt_id_referer',
		'add_query' => "CREATE INDEX `crawlt_id_referer` ON `crawlt_visits_human` (`crawlt_id_referer`)"
		),
		array(
		'index_name' => 'crawlt_ip',
		'add_query' => "CREATE INDEX `crawlt_ip` ON `crawlt_visits_human` (`crawlt_ip`)"
		),
		array(
		'index_name' => 'crawlt_error',
		'add_query' => "CREATE INDEX `crawlt_error` ON `crawlt_visits_human` (`crawlt_error`)"
		),
		array(
		'index_name' => 'crawlt_browser',
		'add_query' => "CREATE INDEX `crawlt_browser` ON `crawlt_visits_human` (`crawlt_browser`)"
		)
	),
	'crawlt_good_sites' => array(
		array(
		'index_name' => 'host_site',
		'add_query' => "CREATE INDEX `host_site` ON `crawlt_good_sites` (`host_site`)"
		)
	),
	'crawlt_attack' => array(
		array(
		'index_name' => 'attack',
		'add_query' => "CREATE INDEX `attack` ON `crawlt_attack` (`attack`)"
		),
		array(
		'index_name' => 'script',
		'add_query' => "CREATE INDEX `script` ON `crawlt_attack` (`script`)"
		),
		array(
		'index_name' => 'type',
		'add_query' => "CREATE INDEX `type` ON `crawlt_attack` (`type`)"
		)
	),
	'crawlt_crawler' => array(
		array(
		'index_name' => 'crawler_info',
		'add_query' => "CREATE INDEX `crawler_info` ON `crawlt_crawler` (`crawler_info`)"
		),
		array(
		'index_name' => 'crawler_ip',
		'add_query' => "CREATE INDEX `crawler_ip` ON `crawlt_crawler` (`crawler_ip`)"
		),
		array(
		'index_name' => 'crawler_name',
		'add_query' => "CREATE INDEX `crawler_name` ON `crawlt_crawler` (`crawler_name`)"
		),
		array(
		'index_name' => 'crawler_url',
		'add_query' => "CREATE INDEX `crawler_url` ON `crawlt_crawler` (`crawler_url`)"
		),
		array(
		'index_name' => 'crawler_user_agent',
		'add_query' => "CREATE INDEX `crawler_user_agent` ON `crawlt_crawler` (`crawler_user_agent`)"
		)
	),
	'crawlt_pages' => array(
		array(
		'index_name' => 'url_page',
		'add_query' => "CREATE INDEX `url_page` ON `crawlt_pages` (`url_page`)"
		),
		array(
		'index_name' => 'id_page',
		'add_query' => "CREATE INDEX `id_page` ON `crawlt_pages` (`id_page`)"
		)
	)
);

// Get the tables list
$tables_list_sql = "SHOW TABLES ";
$tables = mysql_query($tables_list_sql, $connexion) or exit("MySQL query error"); 

$tables_names = array();
while (list($tablename)=mysql_fetch_array($tables)) 
{
	$tables_names[] = strtolower($tablename);
}

// Okay, now as we have the reference data, we can start working
if($maintenance_mode == 'install') {
	// This is a new install, just create the tables and their content
	foreach($tables_to_check as $table_to_check) {
		if($table_to_check['action'] == 'create') {
			// Action is to create the table
			if(!in_array($table_to_check['table_name'], $tables_names)) {
				if($table_to_check['table_name'] == 'crawlt_config')
					$existing_crawlt_config_table = false;
				if($table_to_check['table_name'] == 'crawlt_update_attack')
					$existing_crawlt_update_attack_table = false;
				if($table_to_check['table_name'] == 'crawlt_update')
					$existing_crawlt_update_table = false;
				// The table isn't in the existing tables list, create it
				$result_create = mysql_query($table_to_check['create_delete_query'], $connexion);
				if (!$result_create) {
					// Query failed, add the error message
					$tables_actions_error_messages[] = mysql_error();
				}
				// Add data in the table if needed
				if (!empty($table_to_check['insert_query'])) {
					// Check if the insert query is a filename or a standard query
					if (strpos($table_to_check['insert_query'], 'INSERT') !== false) {
						$result_insert = mysql_query($table_to_check['insert_query'], $connexion);
					} else {
						// use the SQL file in data directory
						$result_insert = mysql_query(file_get_contents(dirname(__FILE__) . '/data/' . $table_to_check['insert_query']), $connexion);
					}
					if (!$result_insert) {
						// Query failed, add the error message
						$tables_actions_error_messages[] = mysql_error();
					}
				}
			}
		}
		if (isset($table_to_check['execute_after']) && !empty($table_to_check['execute_after'])) {
			call_user_func($table_to_check['execute_after']);
		}
	}
} else {
	// This is an update
	// Cycle through all tables that needs creation/deletion
	foreach ($tables_to_check as $table_to_check) {
		if ($tables_to_touch == 'all' || (is_array($tables_to_touch) && in_array($table_to_check['table_name'], $tables_to_touch))) {
			// Either all the tables are to be modified or the current table is in the restricted tables array
			if ($table_to_check['action'] == 'create') {
				// Action is to create the table
				if (!in_array($table_to_check['table_name'], $tables_names)) {
					if($table_to_check['table_name'] == 'crawlt_config')
						$existing_crawlt_config_table = false;
					if($table_to_check['table_name'] == 'crawlt_update_attack')
						$existing_crawlt_update_attack_table = false;
					if($table_to_check['table_name'] == 'crawlt_update')
						$existing_crawlt_update_table = false;
					// The table isn't in the existing tables list, create it
					$result_create = mysql_query($table_to_check['create_delete_query'], $connexion);
					if (!$result_create) {
						// Query failed, add the error message
						$tables_actions_error_messages[] = mysql_error();
					}
					// Add data in the table if needed
					if(!empty($table_to_check['insert_query'])) {
						$result_insert = mysql_query($table_to_check['insert_query'], $connexion);
						if (!$result_insert) {
							// Query failed, add the error message
							$tables_actions_error_messages[] = mysql_error();
						}
					}
				}
			} else {
				// Action is to delete (drop) the table
				$result_delete = mysql_query($table_to_check['create_delete_query'], $connexion);
				if (!$result_delete) {
					// Query failed, add the error message
					$tables_actions_error_messages[] = mysql_error();
				}
			}
			if(isset($table_to_check['execute_after']) && !empty($table_to_check['execute_after'])) {
				call_user_func($table_to_check['execute_after']);
			}
		}
	}
	
	// Cycle through all the fields that needs creation
	foreach ($fields_to_check as $table_name => $fields) {
		if ($tables_to_touch == 'all' || (is_array($tables_to_touch) && in_array($table_name, $tables_to_touch))) {
			// Either all the tables are to be modified or the current table is in the restricted tables array
			$table_info_res = mysql_query("SHOW COLUMNS FROM " . $table_name . "");
			$table_field_names = array();
			while ($table_info = mysql_fetch_assoc($table_info_res)) {
				$table_field_names[] = $table_info['Field'];
			}
			foreach ($fields as $a_field) {
				if (!in_array($a_field['field_name'], $table_field_names)) {
					// Special case
					if($table_name == 'crawlt_site' && $a_field['field_name'] == 'url')
						$existing_crawlt_site_url_field = false;
					// The field isn't in the existing tables list, create it
					$result_update = mysql_query($a_field['add_query'], $connexion);
					if (!$result_update) {
						// Query failed, add the error message
						$fields_actions_error_messages[] = mysql_error();
					}
				}
			}
		}
	}
	
	// Cycle through all the indexes that needs creation
	foreach ($indexes_to_check as $table_name => $indexes) {
		if ($tables_to_touch == 'all' || (is_array($tables_to_touch) && in_array($table_name, $tables_to_touch))) {
			// Either all the tables are to be modified or the current table is in the restricted tables array
			$table_info_res = mysql_query("SHOW INDEX FROM " . $table_name . "");
			$table_field_names = array();
			while ($table_info = mysql_fetch_assoc($table_info_res)) {
				$table_index_names[] = $table_info['Column_name'];
			}
			foreach ($indexes as $an_index) {
				if (!in_array($an_index['index_name'], $table_index_names)) {
					// The field isn't in the existing tables list, create it
					$result_update = mysql_query($an_index['add_query'], $connexion);
					if (!$result_update) {
						// Query failed, add the error message
						$index_actions_error_messages[] = mysql_error();
					}
				}
			}
		}
	}
}
?>
