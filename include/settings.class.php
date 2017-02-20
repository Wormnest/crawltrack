<?php
/**
 *  CrawlTrack class for handling settings.
 *  CrawlTrack. A crawler tracker for websites.
 *  License: GPL version 2 or newer. See license.txt.
 *  File: Password.class.php
 *  Author: Jacob Boerema
 */

/**
 * Class for handling settings.
 * TODO:
 * - Before making changes we should check if the logged in user is admin in most cases.
 */
class ctSettings
{
	private $db;

	public $secret_key;
	public $oldversion = false;	///< $oldversion is true if the version we are updating from is < 150
	
	// Database Config table variables
	public $version;		///< CrawlTrack version according to the database.
	public $language;		///< Selected interface language.
	public $timediff; 		///< Positive or negative int giving diff in hours between server time and "user" time.
	public $ispublic;		///< Public access to statistics or not.
	public $sendmail;		///< Whether to send statistics by mail or not.
	public $lastmail;		///< Date of last sent mail.
	public $email;			///< Email address to send mail to.
	public $firstdayweek;	///< What day is considered the first day of the week: sunday or monday.
	public $datecleaning;	///< Date of last cleaning of human visits table to reduce size by deleting double entries. See cleaning-crawler-entry.php.
	public $displayrows;	///< How many rows to display.
	public $displayorder;	///< How the displayed rows shouls be ordered by default.
	public $htmlmail;		///< Whether to send html mail (1) or plain text (0).
	public $useutf8;		///< Wheter to use utf-8 charset (1) or iso 8859-1 codepage (0).
	public $blockattacks;	///< Whether to block attacks (1) or only register them (0).
	public $removesessionid;	///< Remove session id or not before storing visited pages.
	public $includeurlparams;	///< Whether to include url parameters or not when storing visited pages.
	
	// POST/GET variables
	public $navig;
	public $period;
	public $siteid;
	public $crawler;
	public $graphpos;
	public $displayall;
	public $crawler2;		// Is this used?
	public $logitself;
	public $checklink;
	public $searchtype;
	public $validsite;		// Admin/Install
	public $validform;		// Admin
	public $nocookie;		// Set in login.php, checked in index.php.
	public $validlogin;		// TODO: Ceck if this is really needed here.
	public $sitename;		// TODO: Check if this one can be handled locally in Admin
	public $siteurl;		// TODO: Check if this one can be handled locally in Admin

	/**
	 * Init ctSettings class with either a valid ctDb instance or null (in case we are installing CrawlTrack)
	 */
	public function __construct($db) {
		$this->db = $db;
		
		if ($db) {
			// Get a few settings from db class.
			$this->secret_key = $db->secret_key;
			$this->oldversion = $db->oldversion;
			if ($this->oldversion) {
				$this->language = $db->oldlang;
				$this->init_config_settings();
			} else {
				// Read settings from config table
				$this->get_config_settings();
			}
		} else {
			$this->init_config_settings();
		}
		$this->get_POST_and_GET_vars();
	}
	
	private function get_config_settings() {
		$sql = "SELECT * FROM crawlt_config";
		$stmt = $this->db->connexion->prepare($sql);
		//$stmt->bind_param('s', $loginuser);
		$stmt->execute();
		$results = ctDb::fetch_assoc_stmt($stmt);
		if (!empty($results)) {
			// Since there should be only row we just handle the first row
			$row = $results[0];
			$this->timediff = $row['timeshift'];
			$this->ispublic = $row['public'];
			$this->sendemail = $row['mail'];
			$this->lastmail = $row['datelastmail'];
			$this->email = $row['addressmail'];
			$this->language = $row['lang'];
			$this->version = $row['version'];
			
			// Settings not available in all versions.
			if ($this->version > 160) {
				$this->firstdayweek = $row['firstdayweek'];
			}
			if ($this->version > 171) {
				$this->datecleaning = $row['datelastcleaning'];
			}
			if ($this->version > 210) {
				$this->displayrows = $row['rowdisplay'];
				$this->displayorder = $row['orderdisplay'];
			} else {
				$this->displayrows = 30;
				$this->displayorder = 0;
			}
			if ($this->version > 220) {
				$this->htmlmail = $row['typemail'];
				$this->useutf8 = $row['typecharset'];
			} else {
				$this->htmlmail = 1;
				$this->useutf8 = 1;
			}
			if ($this->version > 281) {
				$this->blockattacks = $row['blockattack'];
				$this->removesessionid = $row['sessionid'];
				$this->includeurlparams = $row['includeparameter'];
			} else {
				$this->blockattacks = 0;
				$this->removesessionid = 0;
				$cthis->includeurlparams = 1;
			}
		} else {
			// Unexpected: No rows found in config!
			die("Configuration settings not found in database!");
		}
	}
	
	private function init_config_settings() {
		$this->ispublic = 0;
		$this->useutf8 = 1;
		$this->timediff = 0;
	}
	
	/**
	 * When variables are not set try to get them (after sanitizing) from POST and/or GET variables.
	 * Taken and adapted from post.php.
	 */
	private function get_POST_and_GET_vars() {
		if (!isset($this->language)) {
			if (isset($_POST['lang'])) {
				$this->language = htmlspecialchars($_POST['lang']);
			} else {
				$this->language = 'english';
			}
		}
		/* Only used in adminlang.php. We will handle it there.
		if (isset($_POST['newlang'])) {
			$crawltnewlang = htmlspecialchars($_POST['newlang']);
		} else {
			$crawltnewlang = 'english';
		} */
		/* Variables used for installing only. Should be set and cleaned there.
		if (isset($_POST['idmysql'])) {
			$idmysql = $_POST['idmysql'];
		} else {
			$idmysql = '';
		}
		if (isset($_POST['passwordmysql'])) {
			$passwordmysql = $_POST['passwordmysql'];
		} else {
			$passwordmysql = '';
		}
		if (isset($_POST['hostmysql'])) {
			$hostmysql = $_POST['hostmysql'];
		} else {
			$hostmysql = 'localhost';
		}
		if (isset($_POST['basemysql'])) {
			$basemysql = $_POST['basemysql'];
		} else {
			$basemysql = '';
		}*/
		if (isset($_POST['order'])) {
			$this->displayorder = (int)$_POST['order'];
		} else {
			if (isset($_GET['order'])) {
				$this->displayorder = (int)$_GET['order'];
			}
		}
		if (isset($_POST['rowdisplay'])) {
			$this->displayrows = (int)$_POST['rowdisplay'];
		} else {
			if (isset($_GET['rowdisplay'])) {
				$this->displayrows = (int)$_GET['rowdisplay'];
			}
		}
		if (isset($_POST['typemail'])) {
			$this->htmlmail = (int)$_POST['typemail'];
		} else {
			if (isset($_GET['typemail'])) {
				$this->htmlmail = (int)$_GET['typemail'];
			}
		}
		if (isset($_POST['charset'])) {
			$this->useutf8 = (int)$_POST['charset'];
		} else {
			if (isset($_GET['charset'])) {
				$this->useutf8 = (int)$_GET['charset'];
			}
		}
		if (isset($_POST['blockattack'])) {
			$this->crawltblockattacks = (int)$_POST['blockattack'];
		} else {
			if (isset($_GET['blockattack'])) {
				$this->crawltblockattacks = (int)$_GET['blockattack'];
			}
		}
		if (isset($_POST['sessionid'])) {
			$this->removesessionid = (int)$_POST['sessionid'];
		} else {
			if (isset($_GET['sessionid'])) {
				$this->removesessionid = (int)$_GET['sessionid'];
			}
		}
		
		// These ones are only used in admin.php to select the type of sessionid
		/*
		if (isset($_POST['sessionid1'])) {
			$this->crawltsessionid1 = (int)$_POST['sessionid1'];
		} else {
			if (isset($_GET['sessionid1'])) {
				$this->crawltsessionid1 = (int)$_GET['sessionid1'];
			} else {
				$this->crawltsessionid1 = 0;
			}
		}
		if (isset($_POST['sessionid2'])) {
			$this->crawltsessionid2 = (int)$_POST['sessionid2'];
		} else {
			if (isset($_GET['sessionid2'])) {
				$this->crawltsessionid2 = (int)$_GET['sessionid2'];
			} else {
				$this->crawltsessionid2 = 0;
			}
		}
		if (isset($_POST['sessionid3'])) {
			$this->crawltsessionid3 = (int)$_POST['sessionid3'];
		} else {
			if (isset($_GET['sessionid3'])) {
				$this->crawltsessionid3 = (int)$_GET['sessionid3'];
			} else {
				$this->crawltsessionid3 = 0;
			}
		}
		if (isset($_POST['sessionid4'])) {
			$this->crawltsessionid4 = (int)$_POST['sessionid4'];
		} else {
			if (isset($_GET['sessionid4'])) {
				$this->crawltsessionid4 = (int)$_GET['sessionid4'];
			} else {
				$this->crawltsessionid4 = 0;
			}
		}
		if (isset($_POST['sessionid5'])) {
			$this->crawltsessionid5 = (int)$_POST['sessionid5'];
		} else {
			if (isset($_GET['sessionid5'])) {
				$this->crawltsessionid5 = (int)$_GET['sessionid5'];
			} else {
				$this->crawltsessionid5 = 0;
			}
		}
		if (isset($_POST['sessionid6'])) {
			$this->crawltsessionid6 = (int)$_POST['sessionid6'];
		} else {
			if (isset($_GET['sessionid6'])) {
				$this->crawltsessionid6 = (int)$_GET['sessionid6'];
			} else {
				$this->crawltsessionid6 = 0;
			}
		}
		if (isset($_POST['sessionid7'])) {
			$this->crawltsessionid7 = (int)$_POST['sessionid7'];
		} else {
			if (isset($_GET['sessionid7'])) {
				$this->crawltsessionid7 = (int)$_GET['sessionid7'];
			} else {
				$this->crawltsessionid7 = 0;
			}
		}
		if (isset($_POST['sessionid8'])) {
			$this->crawltsessionid8 = (int)$_POST['sessionid8'];
		} else {
			if (isset($_GET['sessionid8'])) {
				$this->crawltsessionid8 = (int)$_GET['sessionid8'];
			} else {
				$this->crawltsessionid8 = 0;
			}
		} */
		if (isset($_POST['includeparameter'])) {
			$this->includeurlparams = (int)$_POST['includeparameter'];
		} else {
			if (isset($_GET['includeparameter'])) {
				$this->includeurlparams = (int)$_GET['includeparameter'];
			}
		}
		/* Only used in install and admin and/or install, handle there.
		if (isset($_POST['login'])) {
			$this->login = $_POST['login'];
		} else {
			$this->login = '';
		}
		if (isset($_POST['password1'])) {
			$this->password1 = $_POST['password1'];
		} else {
			$this->password1 = '';
		}
		if (isset($_POST['password2'])) {
			$this->password2 = $_POST['password2'];
		} else {
			$this->password2 = '';
		}
		if (isset($_POST['password3'])) {
			$this->password3 = $_POST['password3'];
		} else {
			$this->password3 = '';
		}
		if (isset($_POST['logintype'])) {
			$this->logintype = (int)$_POST['logintype'];
		} else {
			$this->logintype = 0;
		}
		if (isset($_POST['crawlername2'])) {
			$this->crawlername2 = htmlspecialchars($_POST['crawlername2']);
		} else {
			$this->crawlername2 = '';
		}
		if (isset($_POST['crawlerua2'])) {
			$this->crawlerua2 = htmlspecialchars($_POST['crawlerua2']);
		} else {
			$this->crawlerua2 = '';
		}
		if (isset($_POST['crawleruser2'])) {
			$this->crawleruser2 = htmlspecialchars($_POST['crawleruser2']);
		} else {
			$this->crawleruser2 = '';
		}
		if (isset($_POST['crawlerurl2'])) {
			$this->crawlerurl2 = htmlspecialchars($_POST['crawlerurl2']);
		} else {
			$this->crawlerurl2 = '';
		}
		if (isset($_POST['crawlerip2'])) {
			$this->crawlerip2 = htmlspecialchars($_POST['crawlerip2']);
		} else {
			$this->crawlerip2 = '';
		}*/
		/* Not used anywhere as a POST variable
		if (isset($_POST['logochoice'])) {
			$this->logochoice = (int)$_POST['logochoice'];
		} else {
			$this->logochoice = 0;
		}*/
		
		//case  can use also hypertext link
		if (isset($_POST['validsite'])) {
			$this->validsite = (int)$_POST['validsite'];
		} else {
			if (isset($_GET['validsite'])) {
				$this->validsite = (int)$_GET['validsite'];
			} else {
				$this->validsite = 0;
			}
		}
		// TODO: Check if this one can be handled locally in Admin
		if (isset($_POST['sitename'])) {
			$this->sitename = htmlspecialchars($_POST['sitename']);
		} else {
			if (isset($_GET['sitename'])) {
				$this->sitename = htmlspecialchars($_GET['sitename']);
			} else {
				$this->sitename = '';
			}
		}
		// TODO: Check if this one can be handled locally in Admin
		if (isset($_POST['siteurl'])) {
			$this->siteurl = htmlspecialchars($_POST['siteurl']);
		} else {
			if (isset($_GET['siteurl'])) {
				$this->siteurl = htmlspecialchars($_GET['siteurl']);
			} else {
				$this->siteurl = '';
			}
		}
		// TODO: Only used in admingoodreferer.php
		/*
		if (isset($_POST['urlreferer'])) {
			$this->urlreferer = htmlspecialchars($_POST['urlreferer']);
		} else {
			if (isset($_GET['urlreferer'])) {
				$this->urlreferer = htmlspecialchars($_GET['urlreferer']);
			} else {
				$this->urlreferer = '';
			}
		} */
		if (isset($_POST['validform'])) {
			$this->validform = (int)$_POST['validform'];
		} else {
			if (isset($_GET['validform'])) {
				$this->validform = (int)$_GET['validform'];
			} else {
				$this->validform = 0;
			}
		}
		if (isset($_POST['validlogin'])) {
			$this->validlogin = (int)$_POST['validlogin'];
		} else {
			if (isset($_GET['validlogin'])) {
				$this->validlogin = (int)$_GET['validlogin'];
			} else {
				$this->validlogin = 0;
			}
		}
		if (isset($_POST['period'])) {
			$this->period = (int)$_POST['period'];
		} else {
			if (isset($_GET['period'])) {
				$this->period = (int)$_GET['period'];
			} else {
				$this->period = 0;
			}
		}
		if (isset($_POST['navig'])) {
			$this->navig = (int)$_POST['navig'];
		} else {
			if (isset($_GET['navig'])) {
				$this->navig = (int)$_GET['navig'];
			} else {
				$this->navig = 0;
			}
		}

		//modified according Stef proposal
		if (isset($_POST['site'])) {
			$this->siteid = (int)$_POST['site'];
		} elseif (isset($_GET['site'])) {
			$this->siteid = (int)$_GET['site'];
		} else {
			$this->siteid = 1;
		}

		//case summary
		if ($this->siteid == 0) {
			$this->navig = 23;
			$this->siteid = 1;
		}
		if (isset($_POST['crawler'])) {
			$this->crawler = htmlspecialchars($_POST['crawler']);
			$this->crawler2 = htmlspecialchars($_POST['crawler']);
		} else {
			if (isset($_GET['crawler'])) {
				$this->crawler = htmlspecialchars(stripslashes($_GET['crawler']));
				$this->crawler2 = htmlspecialchars($_GET['crawler']);
			} else {
				$this->crawler = 0;
				$this->crawler2 = 0;
			}
		}

		if (isset($_POST['search'])) {
			$this->searchtype = htmlspecialchars($_POST['search']);
		} else {
			if (isset($_GET['search'])) {
				$this->searchtype = htmlspecialchars($_GET['search']);
			} else {
				$this->searchtype = 0;
			}
		}
		if (isset($_GET['displayall']) && ($_GET['displayall'] == 'no' || $_GET['displayall'] == 'yes')) {
			$this->displayall = $_GET['displayall'];
		} else {
			$this->displayall = 'no';
		}
		if (!isset($this->firstdayweek)) {
			if (isset($_GET['firstdayweek']) && ($_GET['firstdayweek'] == 'Monday' || $_GET['firstdayweek'] == 'Sunday')) {
				$this->firstdayweek = $_GET['firstdayweek'];
			} else {
				$this->firstdayweek = 'Monday';
			}
		}
		if (isset($_POST['graphpos'])) {
			$this->graphpos = (int)$_POST['graphpos'];
		} else {
			if (isset($_GET['graphpos'])) {
				$this->graphpos = (int)$_GET['graphpos'];
			} else {
				$this->graphpos = 0;
			}
		}
		if (isset($_POST['logitself'])) {
			$this->logitself = (int)$_POST['logitself'];
		} else {
			if (isset($_GET['logitself'])) {
				$this->logitself = (int)$_GET['logitself'];
			} else {
				$this->logitself = 0;
			}
		}
		if (isset($_GET['checklink'])) {
			$this->checklink = (int)$_GET['checklink'];
		} else {
			$this->checklink = 0;
		}
		/* TODO: Only used in admin.php; should be handled there.
		if (isset($_POST['novisitor'])) {
			$this->novisitor = (int)$_POST['novisitor'];
		} else {
			$this->novisitor = 0;
		}*/
		if (isset($_GET['nocookie'])) {
			$this->nocookie = (int)$_GET['nocookie'];
		} else {
			$this->nocookie = 0;
		}
	}

}

?>