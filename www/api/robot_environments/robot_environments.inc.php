<?php
/**
 * Robot environment include functions for the RMS API.
 *
 * Allows read and write access to robot environments. Used throughout RMS and within the RMS API.
 * Useful functions include adding pairings between interfaces and environments.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    December, 12 2012
 * @package    api.robot_environments
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../inc/config.inc.php');
include_once(dirname(__FILE__).'/environments/environments.inc.php');
include_once(dirname(__FILE__).'/interfaces/interfaces.inc.php');
include_once(dirname(__FILE__).'/widgets/widgets.inc.php');
include_once(dirname(__FILE__).'/robot_environment.php');
include_once(dirname(__FILE__).'/../../inc/content.inc.php');
include_once(dirname(__FILE__).'/../../inc/head.inc.php');

/**
 * Check if the given array has all of the necessary fields to create an environment-interface pair.
 *
 * @param array $array The array to check
 * @return boolean If the given array has all of the necessary fields to create a new nvironment interface pair
 */
function valid_environment_interface_pair_fields($array) {
  return isset($array['envid']) && isset($array['intid']) && (count($array) === 2);
}

/**
 * Get an array of all the environment-interface pair entries, or null if none exist.
 *
 * @return array|null An array of all the environment-interface pair entries, or null if none exist
 */
function get_environment_interface_pairs() {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `environment_interface_pairs`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the environment-interface pair array for the pair with the given ID, or null if none exist.
 *
 * @param integer $id The pair's ID number
 * @return array|null An array of the pair's SQL entry or null if none exist
 */
function get_environment_interface_pair_by_id($id) {
  global $db;

  $sql = sprintf("SELECT * FROM `environment_interface_pairs` WHERE `pairid`='%d'", cleanse($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Get an array of all the environment-interface pair entries for a given environment, or null if none exist.
 *
 * @param integer $envid The environment ID number
 * @return array|null An array of all the environment-interface pair entries, or null if none exist
 */
function get_environment_interface_pairs_by_envid($envid) {
  global $db;

  // grab the entries and push them into an array
  $result = array();
  $sql = sprintf( "SELECT * FROM `environment_interface_pairs` WHERE `envid`='%d'"
  , cleanse($envid));
  $query = mysqli_query($db, $sql);
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the environment-interface pair array for the pair with the given IDs, or null if none exist.
 *
 * @param integer $envid The environment ID number
 * @param integer $intid The interface ID number
 * @return array|null An array of the pair's SQL entry or null if none exist
 */
function get_environment_interface_pair_by_envid_and_intid($envid, $intid) {
  global $db;

  $sql = sprintf("SELECT * FROM `environment_interface_pairs` WHERE (`envid`='%d') AND (`intid`='%d')",
  cleanse($envid), cleanse($intid));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Create an environment-interface pair with the given information. Any errors are returned.
 *
 * @param integer $envid The environment ID number
 * @param integer $intid The interface ID number
 * @return string|null An error message or null if the create was sucessful
 */
function create_environment_interface_pair($envid, $intid) {
  global $db;

  // make sure the pair does not already exist
  if(get_environment_interface_pair_by_envid_and_intid($envid, $intid)) {
    return 'ERROR: Environment-interface pair '.$envid.'-'.$intid.' already exists';
  }

  // insert into the database
  $sql = sprintf("INSERT INTO `environment_interface_pairs` (`envid`, `intid`) VALUES ('%d', '%d')",
  cleanse($envid), cleanse($intid));
  mysqli_query($db, $sql);

  // no error
  return null;
}

/**
 * Update an environment-interface pair with the given information inside of the array. The array should be indexed
 * by the SQL column names. The ID field must be contained inside of the array with the index 'id'.
 * Any errors are returned.
 *
 * @param array $fields the fields to update including the pair ID number
 * @return string|null an error message or null if the update was sucessful
 */
function update_environment_interface_pair($fields) {
  global $db;

  if(!isset($fields['id'])) {
    return 'ERROR: ID field missing in update';
  }

  // build the SQL string
  $sql = "";
  $num_fields = 0;
  // check for the pair
  if(!($pair = get_environment_interface_pair_by_id($fields['id']))) {
    return 'ERROR: Environment-interface pair ID '.$id.' does not exist';
  }

  // check if we are changing the id
  $id_to_set = $pair['pairid'];
  if(isset($fields['pairid'])) {
    $num_fields++;
    if($fields['pairid'] !== $pair['pairid'] && get_environment_interface_pair_by_id($fields['pairid'])) {
      return 'ERROR: Environment-interface pair ID '.$fields['pairid'].' already exists';
    } else {
      $id_to_set = $fields['pairid'];
    }
  }
  $sql .= sprintf(" `pairid`='%d'", cleanse($id_to_set));

  // check for each update
  if(isset($fields['envid'])) {
    $num_fields++;
    $sql .= sprintf(", `envid`='%d'", cleanse($fields['envid']));
  }
  if(isset($fields['intid'])) {
    $num_fields++;
    $sql .= sprintf(", `intid`='%d'", cleanse($fields['intid']));
  }

  // check to make sure the pair does not exist already
  if(isset($fields['envid']) && isset($fields['intid'])
  && ($fields['envid'] !== $pair['envid'] || $fields['intid'] !== $pair['intid'])
  && get_environment_interface_pair_by_envid_and_intid($fields['envid'], $fields['intid'])) {
    return 'ERROR: Environment-interface pair '.$envid.'-'.$intid.' already exists';
  }

  // check to see if there were too many fields or if we do not need to update
  if($num_fields !== (count($fields) - 1)) {
    return 'ERROR: Too many fields given.';
  } else if ($num_fields === 0) {
    // nothing to update
    return null;
  }

  // we can now run the update
  $sql = sprintf("UPDATE `environment_interface_pairs` SET ".$sql." WHERE `pairid`='%d'"
  , cleanse($fields['id']));
  mysqli_query($db, $sql);

  // no error
  return null;
}

/**
 * Delete the environment-interface pair array for the pair with the given ID. Any errors are returned.
 *
 * @param integer $id The environment-interface pair ID number
 * @return string|null an error message or null if the delete was sucessful
 */
function delete_environment_interface_pair_by_id($id) {
  global $db;

  // see if the pair exists
  if(get_environment_interface_pair_by_id($id)) {
    // delete it
    $sql = sprintf("DELETE FROM `environment_interface_pairs` WHERE `pairid`='%d'", cleanse($id));
    mysqli_query($db, $sql);
    // no error
    return null;
  } else {
    return 'ERROR: Environment-interface pair ID '.$id.' does not exist';
  }
}

/**
 * Get the HTML for an editor used to create or edit the given environment-interface pair entry. If this is not an
 * edit, null can be given as the ID. An invalid ID is the same as giving a null ID.
 *
 * @param integer|null $id the ID of the environment-interface to edit, or null if a new entry is being made
 * @return string A string containing the HTML of the editor
 */
function get_environment_interface_pair_editor_html($id) {
  // see if a pair exists with the given id
  $cur = get_environment_interface_pair_by_id($id);

  if($cur) {
    $envid = $cur['envid'];
    $intid = $cur['intid'];
  } else {
    $envid = '';
    $intid = '';
  }

  $result = '<p>Complete the following form to create or edit an environment-interface pair.</p>
             <form action="javascript:submit();"><fieldset>
               <ol>';

  // only show the ID for edits
  $result .=  ($cur) ? '<li><label for="id">Pair ID</label><input type="text" name="id"
                             id="id" value="'.$cur['pairid'].'" readonly="readonly" /></li>' : '';

  $result .= '<li>
              <label for="envid">Environment</label>
              <select name="envid" id="envid" required>';
  // grab the environments
  $environments = get_environments();
  foreach ($environments as $cur) {
    // check if this environment is the same
    if($envid === $cur['envid']) {
      $result .= '<option value="'.$cur['envid'].'" selected="selected">'.$cur['envid'].": ".$cur['envaddr']." -- ".$cur['type']." :: ".$cur['notes'].'</option>';
    } else {
      $result .= '<option value="'.$cur['envid'].'">'.$cur['envid'].": ".$cur['envaddr']." -- ".$cur['type']." :: ".$cur['notes'].'</option>';
    }
  }
  $result .= '  </select>
              </li>
              <li>
              <label for="intid">Interface</label>
              <select name="intid" id="intid" required>';
  // grab the interfaces
  $interfaces = get_interfaces();
  foreach ($interfaces as $cur) {
    // check if this environment is the same
    if($intid === $cur['intid']) {
      $result .= '<option value="'.$cur['intid'].'" selected="selected">'.$cur['intid'].': '.$cur['name'].' -- api/robot_environments/interfaces/'.$cur['location'].'</option>';
    } else {
      $result .= '<option value="'.$cur['intid'].'">'.$cur['intid'].': '.$cur['name'].' -- api/robot_environments/interfaces/'.$cur['location'].'</option>';
    }
  }
  $result .= '       </select>
                   </li>
                 </ol>
                 <input type="submit" value="Submit" />
               </fieldset>
             </form>';

  return $result;
}

/**
 * Create an error page with the given message. This will echo the full HTML for the page.
 *
 * @param string $error The error message to display on the page
 * @param array $user The user account SQL entry used to create the user menu
 */
function create_error_page($error, $user) {
  global $title;
  $pagename = 'Invalid Connection';
  $prot = (isset($_SERVER['HTTPS'])) ? 'https://' : 'http://';
  $path = $prot.$_SERVER['HTTP_HOST'].'/';

  echo '
<!DOCTYPE html>
<html>
  <head>';
  import_head($path);
  echo '
    <script type="text/javascript">createMenuButtons();</script>
    <title>'.$title.' :: '.$pagename.'</title>
  </head>
  <body>';
  create_header($user, $pagename, $path);
  echo'
    <section id="page"><article>
        <div class="center">
          <br /> <br /> <br /> <br /> <br />
          <h2>ERROR: '.$error.'</h2>
          <br /> <br /> <br /> <br /> <br />
        </div></article>';
  create_footer($path);
  echo '
    </section>
  </body>
</html>
';
}

/**
 * Genereate the interface for the given environment. This will echo the entire HTML for the page.
 *
 * @param integer $userid The user ID of the user that is currently logged in
 * @param integer $envid The environment ID to generate an interface for
 * @param integer $intid The interface ID
 */
function generate_environment_interface($userid, $envid, $intid) {
  // grab what we need
  if(!$interface = get_interface_by_id($intid)) {
    create_error_page('Invalid interface number provided.', get_user_account_by_id($userid));
  } else if(!get_environment_by_id($envid)) {
    create_error_page('Invalid environment number provided.', get_user_account_by_id($userid));
  } else if(!get_environment_interface_pair_by_envid_and_intid($envid, $intid)) {
    create_error_page('Invalid pairing between the interface and the environment.', get_user_account_by_id($userid));
  } else if(!file_exists(dirname(__FILE__).'/interfaces/'.$interface['location'].'/index.php')) {
    create_error_page(dirname(__FILE__).'/interfaces/'.$interface['location'].'/index.php does not exist!', get_user_account_by_id($userid));
  } else {
    // include the file
    include_once(dirname(__FILE__).'/interfaces/'.$interface['location'].'/index.php');

    // now check for the correct function
    if(!function_exists('generate')) {
      create_error_page('Interface script does not implement the required function.', $user);
    } else {
      // good to go, lets create the interface!
      generate(new robot_environment($userid, $envid, $intid));
    }
  }
}
?>
