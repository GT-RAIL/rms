<?php
/**
 * Javascript include functions for the RMS API.
 *
 * Allows read and write access to Javascript URLs and files via PHP function calls. Used throughout
 * RMS and within the RMS API.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2012 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    November, 8 2012
 * @package    api.config.javascript_files
 * @link       http://ros.org/wiki/rms
 */

include_once(dirname(__FILE__).'/../../../inc/config.inc.php');

/**
 * Get an array of all Javascript file entires in the database or null if none exist.
 *
 * @return array|null The array of Javascript file entries or null if none exist.
 */
function get_javascript_files() {
  global $db;

  // grab the javascript entries and push them into an array
  $result = array();
  $query = mysqli_query($db, "SELECT * FROM `javascript_files`");
  while($cur = mysqli_fetch_assoc($query)) {
    $result[] = $cur;
  }

  return (count($result) === 0) ? null : $result;
}

/**
 * Get the Javascript file array for the entry with the given ID, or null if none exist.
 *
 * @param integer $id The Javascript file ID number
 * @return array|null An array of the Javascript file's SQL entry or null if none exist
 */
function get_javascript_file_by_id($id) {
  global $db;

  // grab the article
  $sql = sprintf("SELECT * FROM `javascript_files` WHERE fileid='%d'", $db->real_escape_string($id));
  return mysqli_fetch_assoc(mysqli_query($db, $sql));
}

/**
 * Download the given Javascript file entry.
 *
 * @param array $js The Javascript file SQL entry to download
 * @return string|boolean A string containing an error message or false if there were no errors
 */
function download_javascript_file($js) {
  // download the file using cURL
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $js['url']);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($curl);
  $type = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
  curl_close ($curl);

  if(!$data || strstr($type, 'text/html')) {
    return 'Could not download Javascript file "'.$js['url'].'".';
  }

  // save the file locally
  $path = dirname(__FILE__).'/../../../'.$js['path'];
  if(!($file = @fopen($path, "w+")) || !fputs($file, $data) || !fclose($file)) {
    return 'Could not write file "'.$js['path'].'". Check folder permisions and try again.';
  }

  // no error
  return false;
}

/**
 * Download all Javascript file entries.
 *
 * @return string|boolean A string containing an error message or false if there were no errors
 */
function download_javascript_files() {

  //return;
  // grab all of the files
  if(!($files = get_javascript_files())) {
    return 'No Javascript files found.';
  }

  // delete each of them
  foreach ($files as $cur) {
    if($error = download_javascript_file($cur)) {
      return $error;
    }
  }

  // no errors
  return false;
}

/**
 * Delete the given Javascript file from the server.
 *
 * @param array $js The Javascript file SQL entry to delete from the server
 * @return string|boolean A string containing an error message or false if there were no errors
 */
function delete_local_javascript_file($js) {
  $path = dirname(__FILE__).'/../../../'.$js['path'];

  if(file_exists($path) && !unlink($path)) {
    return 'Could not delete Javascript file "'.$js['path'].'".';
  } else {
    // no errors
    return false;
  }
}

/**
 * Delete all local Javascript files from the server.
 *
 * @return string|boolean A string containing an error message or false if there were no errors
 */
function delete_local_javascript_files() {
  // grab all of the files
  if(!($files = get_javascript_files())) {
    return 'No Javascript files found.';
  }

  // delete each of them
  foreach ($files as $cur) {
    if($error = delete_local_javascript_file($cur)) {
      return $error;
    }
  }

  // no errors
  return false;
}
?>
