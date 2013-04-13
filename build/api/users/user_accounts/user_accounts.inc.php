<?php
/**
 * User accounts include static functions for the RMS API.
 *
 * Allows read and write access to user accounts via PHP static function calls.
 * Used throughout RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.users.user_accounts
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');
include_once(dirname(__FILE__).'/../../api.inc.php');

/**
 * A static class to contain the user_accounts.inc.php static functions.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 12 2013
 * @package    api.users.user_accounts
 */
class user_accounts
{
    /**
     * Generate a random 16 character string for salting passwords.
     *
     * @return string The 16 character salt
     */
    static function generate_salt()
    {
        // valid salt characters
        $validSalt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.
            'abcdefghijklmnopqrstuvwxyz'.
            '0123456789';
    
        $salt = '';
        $valid = strlen($validSalt);
    
        // seed the random number generator
        mt_srand((double)microtime()*1000000);
    
        // grab 16 random characters for our salt
        for ($i = 0; $i < 16; $i++) {
            $salt .= $validSalt[mt_rand(0, $valid-1)];
        }
    
        return $salt;
    }
    
    /**
     * Check if the given array has all of the necessary fields to create a new
     * user account.
     *
     * @param array $array The array to check
     * @return boolean If the given array has all of the necessary fields to
     *     create a new user account
     */
    static function valid_user_account_fields($array)
    {
        return isset($array['username']) && isset($array['password'])
                && isset($array['firstname']) && isset($array['lastname'])
                && isset($array['email']) && isset($array['type'])
                && (count($array) === 6);
    }
    
    /**
     * Get the enum types for user accounts in an array.
     *
     * @return array An array containing the enum types for users
     */
    static function get_user_account_types()
    {
        return api::get_enum_types('user_accounts', 'type');
    }
    
    /**
     * Get an array of all the user accounts in the system, or null if none
     * exist.
     *
     * @return array|null An array of all the user accounts in the system, or
     *     null if none exist
     */
    static function get_user_accounts()
    {
        global $db;
    
        // grab the users and push them into an array
        $result = array();
        $query = mysqli_query($db, "SELECT * FROM `user_accounts`");
        while ($cur = mysqli_fetch_assoc($query)) {
            $result[] = $cur;
        }
    
        return (count($result) === 0) ? null : $result;
    }
    
    /**
     * Get the user array for the user with the given ID, or null if none exist.
     *
     * @param integer $id The user ID number
     * @return array|null An array of the user's SQL entry or null if none exist
     */
    static function get_user_account_by_id($id)
    {
        global $db;
    
        $str = "SELECT * FROM `user_accounts` WHERE `userid`='%d'";
        $sql = sprintf($str, api::cleanse($id));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }
    
    /**
     * Get the user array for the user with the given username, or null if none
     * exist.
     *
     * @param string $username The username
     * @return array|null An array of the user's SQL entry or null if none exist
     */
    static function get_user_account_by_username($username)
    {
        global $db;

        $str = "SELECT * FROM `user_accounts` WHERE `username`='%s'";
        $sql = sprintf($str, api::cleanse($username));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }
    
    /**
     * Get the user array for the user with the given email, or null if none
     * exist.
     *
     * @param string $email The email
     * @return array|null An array of the user's SQL entry or null if none exist
     */
    static function get_user_account_by_email($email)
    {
        global $db;
        
        $str = "SELECT * FROM `user_accounts` WHERE `email`='%s'";
        $sql = sprintf($str, api::cleanse($email));
        return mysqli_fetch_assoc(mysqli_query($db, $sql));
    }
    
    /**
     * Create a user account with the given information. Any errors are 
     * returned.
     *
     * @param string $username The username
     * @param string $password The raw password
     * @param string $firstname The firstname of the user
     * @param string $lastname The lastname of the user
     * @param string $email The email address of the user
     * @param string $type The type of user
     * @return string|null An error message or null if the create was sucessful
     */
    static function create_user_account($username, $password,
            $firstname, $lastname, $email, $type)
    {
        global $db;
    
        // make sure the user does not exist
        if (user_accounts::get_user_account_by_username($username)) {
            return 'ERROR: User "'.$username.'" already exists';
        } else if (user_accounts::get_user_account_by_email($email)) {
            return 'ERROR: Email address "'.$email.'" already exists';
        }
    
        // generate a salt string
        $salt = api::cleanse(user_accounts::generate_salt());
        // now insert into the database
        $sql = sprintf(
            "INSERT INTO `user_accounts`
                (`username`, `password`, `salt`, `firstname`, `lastname`, 
                 `email`, `type`)
             VALUES
                ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
            api::cleanse($username), sha1(api::cleanse($password).$salt), $salt,
            api::cleanse($firstname), api::cleanse($lastname),
            api::cleanse($email), api::cleanse($type)
        );
        mysqli_query($db, $sql);
    
        // no error
        return null;
    }
    
    /**
     * Update a user account with the given information inside of the array. The
     * array should be indexed by the SQL column names. The ID field must be
     * contained inside of the array with the index 'id'. Any errors are
     * returned.
     *
     * @param array $fields the fields to update including the user ID number
     * @return string|null an error message or null if the update was sucessful
     */
    static function update_user_account($fields)
    {
        global $db;
    
        if (!isset($fields['id'])) {
            return 'ERROR: ID field missing in update';
        }
    
        // build the SQL string
        $sql = "";
        $numFields = 0;
        // check for the user
        if (!($user = user_accounts::get_user_account_by_id($fields['id']))) {
            return 'ERROR: User ID '.$fields['id'].' does not exist';
        }
    
        // check if we are changing the id
        $idToSet = $user['userid'];
        if (isset($fields['userid'])) {
            $numFields++;
            $exists = user_accounts::get_user_account_by_id($fields['userid']);
            if ($fields['userid'] !== $user['userid'] && $exists) {
                return 'ERROR: User ID '.$fields['userid'].' already exists';
            } else {
                $idToSet = $fields['userid'];
            }
        }
        $sql .= sprintf(" `userid`='%d'", api::cleanse($idToSet));
    
        // check for each update
        if (isset($fields['username'])) {
            $numFields++;
            if ($fields['username'] !== $user['username'] 
                    && user_accounts::get_user_account_by_username(
                        $fields['username']
                    )
            ) {
                return 'ERROR: User "'.$fields['username'].'" already exists';
            }
            $sql .= sprintf(
                ", `username`='%s'", 
                api::cleanse($fields['username'])
            );
        }
        if (isset($fields['email'])) {
            $numFields++;
            $ex = user_accounts::get_user_account_by_email($fields['email']);
            if ($fields['email'] !== $user['email'] && $ex) {
                return 'ERROR: Email address "'.$fields['email'].
                    '" already exists';
            }
            $sql .= sprintf(", `email`='%s'", api::cleanse($fields['email']));
        }
        if (isset($fields['firstname'])) {
            $numFields++;
            $sql .= sprintf(
                ", `firstname`='%s'", api::cleanse($fields['firstname'])
            );
        }
        if (isset($fields['lastname'])) {
            $numFields++;
            $sql .= sprintf(
                ", `lastname`='%s'", api::cleanse($fields['lastname'])
            );
        }
        if (isset($fields['password'])) {
            $numFields++;
            $sql .= sprintf(
                ", `password`='%s'", 
                sha1(api::cleanse($fields['password']).$user['salt'])
            );
        }
        if (isset($fields['type'])) {
            $numFields++;
            $sql .= sprintf(", `type`='%s'", api::cleanse($fields['type']));
        }
    
        // check to see if there were too many fields or if we do not need to
        if ($numFields !== (count($fields) - 1)) {
            return 'ERROR: Too many fields given.';
        } else if ($numFields === 0) {
            // nothing to update
            return null;
        }
    
        // we can now run the update
        $str = "UPDATE `user_accounts` SET ".$sql." WHERE `userid`='%d'";
        $sql = sprintf($str, api::cleanse($fields['id']));
        mysqli_query($db, $sql);
    
        // no error
        return null;
    }
    
    /**
     * Delete the user array for the user with the given ID. Any errors are
     * returned.
     *
     * @param integer $id The user ID number
     * @return string|null an error message or null if the delete was sucessful
     */
    static function delete_user_account_by_id($id)
    {
        global $db;
    
        // see if the account exists
        if (user_accounts::get_user_account_by_id($id)) {
            // delete it
            $str = "DELETE FROM `user_accounts` WHERE `userid`='%d'";
            $sql = sprintf($str, api::cleanse($id));
            mysqli_query($db, $sql);
            // no error
            return null;
        } else {
            return 'ERROR: User ID '.$id.' does not exist';
        }
    }
    
    /**
     * Check for valid authorization. If the RMS-Use-Session HTTP header is set
     * to true, then session information will be used for authorization. If this
     * is not the case, then the HTTP Authentication header is used to
     * authenticate.
     *
     * @return array|null The authenticated user or null if none exist
     */
    static function authenticate()
    {
        global $db;
    
        // check if we are using the session information
        $headers = apache_request_headers();
        foreach ($headers as $h => $value) {
            if ($h === 'RMS-Use-Session' && $value === 'true') {
                // use session information to authenticate
                session_start();
                return (isset($_SESSION['userid'])) 
                    ? user_accounts::get_user_account_by_id($_SESSION['userid'])
                    : null;
            }
        }
    
        // check the auth header
        if (isset($_SERVER['PHP_AUTH_USER']) 
                && isset($_SERVER['PHP_AUTH_PW'])) {
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
    
            // grab the username
            $str = "SELECT * FROM `user_accounts` WHERE `username`='%s'";
            $sql = sprintf($str, api::cleanse($username));
    
            // check if a result was found
            $query = mysqli_query($db, $sql);
            if (mysqli_num_rows($query) == 1) {
                $user = mysqli_fetch_array($query);
                // check the password
                return ($user['password'] === sha1($password.$user['salt'])) 
                    ? $user
                    : null;
            }
        }
    
        // no valid information found
        return null;
    }
    
    /**
     * Get the HTML for an editor used to create or edit the given user account
     * entry. If this is not an edit, null can be given as the ID. An invalid ID
     * is the same as giving a null ID.
     *
     * @param integer|null $id the ID of the user account to edit, or null if a
     *     new entry is being made
     * @return string A string containing the HTML of the editor
     */
    static function get_user_account_editor($id = null)
    {
        // see if a user exists with the given id
        $cur = user_accounts::get_user_account_by_id($id);
    
        if ($cur) {
            $password = api::$passwordHolder;
            $username = $cur['username'];
            $firstname = $cur['firstname'];
            $lastname = $cur['lastname'];
            $email = $cur['email'];
            $type = $cur['type'];
        } else {
            $password = '';
            $username = '';
            $firstname = '';
            $lastname = '';
            $email = '';
            $type = '';
        }
    
        $result = '
<p>Complete the following form to create or edit a user.</p>
    <form action="javascript:submit();"><fieldset>
    <ol>';
    
        // only show the ID for edits
        $result .=  ($cur) ? 
            '<li><label for="id">User ID</label><input type="text" name="id"
                     id="id" value="'.$cur['userid'].'" readonly="readonly" />
             </li>' : '';
    
        $result .= '
<li>
    <label for="username">Username</label>
    <input type="text" name="username" id="username" value="'.$username.'"
     placeholder="Username" required />
</li>
<li>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" value="'.$password.'"
        placeholder="Password" required />
    <label for="password-confirm">Confirm Password</label>
    <input type="password" name="password-confirm" id="password-confirm"
        value="'.$password.'" placeholder="Confirm Password" required />
</li>
<li>
    <label for="firstname">First Name</label>
    <input type="text" name="firstname" id="firstname" value="'.$firstname.'"
         placeholder="First Name" required />
</li>
<li>
    <label for="lastname">Last Name</label>
    <input type="text" name="lastname" id="lastname" value="'.$lastname.'"
        placeholder="Last Name" required />
</li>
<li>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="'.$email.'"
        placeholder="user@example.com" required />
</li>
<li>
    <label for="type">Type</label>
    <select name="type" id="type" required>';
    
        // grab the types of users
        $types = user_accounts::get_user_account_types();
        foreach ($types as $curtype) {
            // check if this type is the same
            if ($type === $curtype) {
                $result .= '<option value="'.$curtype.'" selected="selected">'.
                    $curtype.'</option>';
            } else {
                $result .= '<option value="'.$curtype.'">'.$curtype.'</option>';
            }
        }
    
        $result .= '      </select>
                </li></ol>
                <input type="submit" value="Submit" />
                </fieldset>
                </form>';
    
        return $result;
    }
}
