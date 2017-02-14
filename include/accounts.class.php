<?php
/**
 *  CrawlTrack class for handling user accounts.
 *  CrawlTrack. A crawler tracker for websites.
 *  License: GPL version 2 or newer. See license.txt.
 *  File: Password.class.php
 *  Author: Jacob Boerema
 */

if (!defined('IN_CRAWLT')) {
	exit('<h1>No direct access</h1>');
}

/**
 * TODO
 * - Add maximum login tries within a certain time
 * - Add minimum length of password
 * - Maybe check for certain very common entered passwords
 * - Require maybe numbers, non alphanumeric characters, minimum length of pw
 */
class ctAccounts
{
	private $db;
	
	public $rightsite;
	public $rightadmin;
	
	/**
	 * Init ctPassword class with a valid ctDb instance.
	 */
	public function __construct($db) {
		$this->db = $db;
	}
	
	/**
	 * Checks if a username already exists. Returns true if it does otherwise false.
	 */
	public function username_exists($user) {
		$sql = "SELECT * FROM crawlt_login WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('s', $user);
		$result = $stmt->execute();
		$stmt->store_result();
		if ($result && $stmt->num_rows > 0) {
			$result = true;
		} else {
			$result = false;
		}
		$stmt->close();
		return $result;
	}
	
	/**
	 * Add an admin user account.
	 */
	public function add_admin($username, $password) {
		// Admin accounts can only be added when installing or by an admin
		if (!(defined('IN_CRAWLT_INSTALL') || defined('IN_CRAWLT_ADMIN'))) {
			exit('<h1>Function does not exist</h1>');
		}
		return $this->add_user_account($username, $password, 0, 1);
	}

	/**
	 * Add an non admin user account specifying which site(s) can be viewed.
	 * Where $site==0 means all sites.
	 */
	public function add_nonadmin_user($username, $password, $site) {
		return $this->add_user_account($username, $password, $site, 0);
	}
	
	/**
	 * Add a user account.
	 */
	private function add_user_account($username, $password, $site, $admin) {
		$sql = "INSERT INTO crawlt_login (crawlt_user,admin,site,password_type,password_hash) VALUES (?,?,?,?,?)";
		$pw_type = 1; // 1 = new/safe hashing
		$pw_hash = $this->encode_password($password);
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('siiis', $username, $admin, $site, $pw_type, $pw_hash);
		$result = $stmt->execute();
		if ($result) {
			$result = $stmt->affected_rows > 0;
		}
		$stmt->close();
		return $result;
	}
	
	/**
	 * Check if the credentials are from a valid user.
	 * Version using bind_param and bind_result
	 */
	public function is_valid_login($loginuser, $loginpassword) {
		$sql = "SELECT * FROM crawlt_login WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('s', $loginuser);
		$stmt->execute();
		$stmt->store_result();
		// We need special handling in case the login table hasn't been updated yet
		$numfields = $stmt->result_metadata()->field_count;
		if ($numfields == 5) {
			// Database not yet updated
			$stmt->bind_result($dummyid, $user, $password, $admin, $site);
			$type = 3; // Dummy number so we know when we can't check or add hash
		} else {
			$stmt->bind_result($dummyid, $user, $password, $admin, $site, $type, $hash);
		}
		// TODO: Only one row is probably allowed since user should be unique?
		// However need to check with non admin users and the site code!
		// $site = 0 means is allowed to visit all sites, other number: only allowed to visit that site id
		$result = false;
		while ($row = $stmt->fetch()) {
			if ($user == $loginuser) {
				if ($type == 1) {
					// Using new safe password hash
					if (password_verify($loginpassword, $hash)) {
						$result = true;
					}
				} elseif ($type == 0) {
					// Using old md5 hashed password
					if ($password == $this->encode_password_old($loginpassword)) {
						// Password is correct. Now we need to update it
						if ($this->update_password_encoding($user, $loginpassword)) {
							$result = true;
						}
					}
				} elseif ($type == 3) {
					// Database not updated yet. Only check for correct md5 hashed password.
					if ($password == $this->encode_password_old($loginpassword)) {
						$result = true;
					}
				}
				
				if ($result) {
					$this->rightsite = $site;
					$this->rightadmin = $admin;
					$stmt->close();
					return true;
				}
			}
		}
		$stmt->close();
		return false;
	}
	
	/**
	 * Check if the credentials are from a valid user.
	 * Old version needing quoting
	 * TESTING VERSION USING OLD PASSWORD HANDLING. DONT USE!!
	 */
	public function is_valid_login0($loginuser, $loginpassword) {
		$sql = "SELECT * FROM crawlt_login WHERE crawlt_user='" . $this->db->sql_quote($loginuser) . "'";
		$result = $this->db->connexion->query($sql) or die("MySQL query error");
		$encoded_pw_old = $this->encode_password_old($loginpassword);
		// TODO: Only one row is probably allowed since user should be unique?
		// However need to check with non admin users and the site code!
		// $site = 0 means is allowed to visit all sites, other number: only allowed to visit that site id
		while ($row = $result->fetch_object()) {
			$user = $row->crawlt_user;
			$password = $row->crawlt_password;
			if ($user == $loginuser && $password == $encoded_pw_old) {
				$this->rightsite = $row->site;
				$this->rightadmin = $row->admin;
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Check if the credentials are from a valid user.
	 * Version using bind_param and automatic fetching of all results using fetch_results
	 * TESTING VERSION USING OLD PASSWORD HANDLING. DONT USE!!
	 */
	public function is_valid_login2($loginuser, $loginpassword) {
		$encoded_pw_old = $this->encode_password_old($loginpassword);
		$sql = "SELECT * FROM crawlt_login WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('s', $loginuser);
		$stmt->execute();
		$results = ctDb::fetch_results($stmt);
		// TODO: Only one row is probably allowed since user should be unique?
		// However need to check with non admin users and the site code!
		// $site = 0 means is allowed to visit all sites, other number: only allowed to visit that site id
		foreach ($results as $res) {
			$password = $res['crawlt_password'];
			$user = $res['crawlt_user'];
			if ($user == $loginuser && $password == $encoded_pw_old) {
				$this->rightsite = $res['crawlt_site'];
				$this->rightadmin = $res['crawlt_admin'];
				$stmt->close();
				return true;
			}
		}
		$stmt->close();
		return false;
	}
	
	/**
	 * Check if the credentials are from a valid user.
	 * Version using bind_param and automatic fetching of all results using fetch_assoc_stmt
	 * TESTING VERSION USING OLD PASSWORD HANDLING. DONT USE!!
	 */
	public function is_valid_login3($loginuser, $loginpassword) {
		$encoded_pw_old = $this->encode_password_old($loginpassword);
		$sql = "SELECT * FROM crawlt_login WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('s', $loginuser);
		$stmt->execute();
		$results = ctDb::fetch_assoc_stmt($stmt);
		// TODO: Only one row is probably allowed since user should be unique?
		// However need to check with non admin users and the site code!
		// $site = 0 means is allowed to visit all sites, other number: only allowed to visit that site id
		foreach ($results as $res) {
			$password = $res['crawlt_password'];
			$user = $res['crawlt_user'];
			if ($user == $loginuser && $password == $encoded_pw_old) {
				$this->rightsite = $res['crawlt_site'];
				$this->rightadmin = $res['crawlt_admin'];
				$stmt->close();
				return true;
			}
		}
		$stmt->close();
		return false;
	}
	
	/**
	 * Get a list of all non admin usernames.
	 */
	public function get_all_nonadmin_users() {
		$users = array();
		$sql = "SELECT crawlt_user FROM crawlt_login WHERE admin=0";
		$stmt = $this->db->connexion->prepare($sql);
		$result = $stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($user);
		while ($row = $stmt->fetch()) {
			$users[] = $user;
		}
		$stmt->close();
		return $users;
	}
	
	/**
	 * Delete a user.
	 * TODO: Should we add an extra check here to make sure it's not an admin account?
	 */
	public function delete_user_account($user) {
		// Only Admins can delete an account
		if (!defined('IN_CRAWLT_ADMIN')) {
			exit('<h1>Function does not exist</h1>');
		}
		$sql = "DELETE FROM crawlt_login WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('s', $user);
		$result = $stmt->execute();
		if ($result) {
			$result = $stmt->affected_rows > 0;
		}
		$stmt->close();
		return $result;
	}
	
	/**
	 * Change password.
	 */
	public function change_password($user, $newpassword) {
		// At this point account will always be already type 1 (using new password hash)
		// because before changing password we will always check current password with user first
		$encoded_pw = $this->encode_password($newpassword);
		$sql = "UPDATE crawlt_login SET password_hash=? WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$stmt->bind_param('ss', $encoded_pw, $user);
		$result = $stmt->execute();
		if ($result) {
			$result = $stmt->affected_rows > 0;
		}
		$stmt->close();
		return $result;
	}
	
	/**
	 * Compatibility function to update an old deprecated password encoding to using a new hash.
	 */
	private function update_password_encoding($user, $password) {
		$sql = "UPDATE crawlt_login SET crawlt_password=NULL, password_type='1', password_hash=? WHERE crawlt_user=?";
		$stmt = $this->db->connexion->prepare($sql);
		$hash = $this->encode_password($password);
		$stmt->bind_param('ss', $hash, $user);
		$result = $stmt->execute();
		if ($result) {
			$result = $stmt->affected_rows > 0;
		}
		$stmt->close();
		return $result;
	}
	
	/**
	 * Encode a password with the recommended password hashing function.
	 */
	private function encode_password($pw) {
		// password functions built in starting php 5.5, before that use compat library
		if (version_compare(PHP_VERSION, '5.5.0', '<')) {
			$compatfile = dirname(__FILE__) . '/../vendor/ircmaxell/password-compat/lib/password.php';
			require_once($compatfile);
		}
		return password_hash($pw, PASSWORD_DEFAULT);
	}
	
	/**
	 * Old deprecated password encoding. Don't use!
	 */
	private function encode_password_old($pw) {
		return md5($pw);
	}
}

?>
