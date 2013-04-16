<?php
/**
 * Main study admin panel for the RMS.
 *
 * The main study admin panel allows read/write access to study SQL tables via a
 * GUI.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    study
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

// check if a user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: ../login/?goto=study');
    return;
}

// the name of the study page
$pagename = 'Study Panel';

// load the include files
include_once(dirname(__FILE__).'/../api/config/logs/logs.inc.php');
include_once(dirname(__FILE__).
        '/../api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).
        '/../api/user_studies/conditions/conditions.inc.php');
include_once(dirname(__FILE__).
        '/../api/user_studies/experiments/experiments.inc.php');
include_once(dirname(__FILE__).'/../api/user_studies/studies/studies.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/environments/environments.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/interfaces/interfaces.inc.php');
include_once(dirname(__FILE__).'/../inc/head.inc.php');
include_once(dirname(__FILE__).'/../inc/content.inc.php');

// grab the user info from the database
$sessionUser = user_accounts::get_user_account_by_id($_SESSION['userid']);

// now make sure this is an admin
if ($sessionUser['type'] !== 'admin') {
    $msg = $sessionUser['username'].' attempted to access the study panel.';
    logs::write_to_log('WARNING: '.$msg);
    // send the user back to their main menu
    header('Location: /menu');
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
<?php head::import_head('../')?>
<title><?php echo $title.' :: '.$pagename?>
</title>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/tablesorter/2.0.5b/tablesorter.min.js">
</script>
<script type="text/javascript">
  /**
   * The start function creates all of the JQuery UI elements and button 
   * callbacks.
   */
  function start() {
    // create the user menu
    createMenuButtons();

    // create the tabs for the study page
    $('#admin-tabs').tabs();

    // create the delete icon buttons
    $('.delete').button({
        icons: {primary: "ui-icon-circle-close"},
        text: false
    });

    // delete button callback
    $('.delete').click(function(e) {
      // create a confirm dialog
      var confirm = $('#confirm-delete-popup').dialog({
        position: ['center',100],
        draggable: false,
        resizable: false,
        modal: true,
        show: 'blind',
        width: 300,
        buttons: {
          No: function() {
            $(this).dialog('close');
          },
          Yes: function() {
            $(this).dialog('close');
          }
        },
        autoOpen: false
      });
      confirm.html('<center><h2>Coming Soon</h2></center>');
      // load the popup
      $('#confirm-delete-popup').dialog('open');
    });

    // create the add icon buttons
    $('.create-new').button({icons: {primary:'ui-icon-plus'}});

    // create the edit icon buttons
    $('.edit').button({
      icons: {primary: 'ui-icon-pencil'},
      text: false
    });

    // creates a popup used to add/edit an entry
    $('#editor-popup').dialog({
      position: ['center', 100],
      autoOpen: false,
      draggable: false,
      resizable: false,
      modal: true,
      show: 'blind',
      width: 700,
      buttons: {
        Cancel: function() {
          $(this).dialog('close');
        }
      }
    });

    // editor button callbacks
    $('.create-new').click(function(e) {createEditor(e);});
    $('.edit').click(function(e) {createEditor(e);});

    // a function to make the correct AJAX call to display an editor
    var createEditor = function(e) {
      $('#editor-popup').html('<center><h2>Coming Soon</h2></center>');
      $('#editor-popup').dialog('open');
    };

    // make the tables sortable
    $('.tablesorter').tablesorter({
      widgets: ['zebra'],
      headers: {
        // disable the first two columns (delete/edit)
        0:{sorter: false},
        1:{sorter: false}
      }
    });
  }
</script>
</head>
<body onload="start()">
    <?php content::create_header($sessionUser, $pagename, '../')?>
    <section class="page">
        <section>
            <div class="line"></div>
            <article>
                <div class="admin-tabs-container">
                    <div id="admin-tabs" class="admin-tabs">
                        <ul>
                            <li><a href="#studies-tab">Manage Studies</a>
                            </li>
                            <li>
                                <a href="#experiments-tab">
                                    Manage Experiments</a>
                            </li>
                            <li><a href="#browse-logs-tab">Browse Study Logs</a>
                            </li>
                        </ul>
                        <div id="studies-tab">
                            <div id="studies">
                                <div class="center">
                                    <h3>Studies</h3>
                                </div>
                                <div class="line"></div>
                                <table class="tablesorter">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Description</th>
                                            <th>Start</th>
                                            <th>End</th>
                                        </tr>
                                        <tr>
                                            <td colspan="7"><hr /></td>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
// populate the table
$studies = studies::get_studies();
$numStudies = count($studies);
for ($i = 0; $i < $numStudies; $i++) {
    $cur = $studies[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell">
            <button class="delete"
                name="studies"
                id="studies-<?php echo $cur['studyid']?>">Delete</button>
        </td>
        <td class="edit-cell">
            <button class="edit" name="studies"
                id="studies-<?php echo $cur['studyid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['studyid']?>
        </td>
        <td class="content-cell"><?php echo $cur['name']?>
        </td>
        <td class="content-cell"><?php echo $cur['description']?>
        </td>
        <td class="content-cell"><?php echo $cur['start']?>
        </td>
        <td class="content-cell"><?php echo $cur['end']?>
        </td>
    </tr>
<?php
}
?>
            </tbody>
            <tr>
                <td colspan="7"><hr /></td>
            </tr>
            <tr>
                <td colspan="6"></td>
                <td class="add-cell">
                    <button class="create-new" id="add-studies" name="studies">
                        Add Study</button>
                </td>
            </tr>
        </table>
    </div>
    <div id="conditions">
        <div class="center">
            <h3>Conditions</h3>
        </div>
        <div class="line"></div>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>ID</th>
                    <th>Study</th>
                    <th>Name</th>
                    <th>Interface</th>
                </tr>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
            </thead>
            <tbody>
<?php
// populate the table
$conditions = conditions::get_conditions();
$numConditions = count($conditions);
for ($i = 0; $i < $numConditions; $i++) {
    $cur = $conditions[$i];
    $study = studies::get_study_by_id($cur['studyid']);
    $interface = interfaces::get_interface_by_id($cur['intid']);
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell">
            <button class="delete"
                name="conditions"
                id="conditions-<?php echo $cur['condid']?>">Delete</button>
        </td>
        <td class="edit-cell">
            <button class="edit"
                name="conditions"
                id="conditions-<?php echo $cur['condid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['condid']?>
        </td>
        <td class="content-cell">
            <?php echo $cur['studyid'].': '.$study['name']?>
        </td>
        <td class="content-cell">
            <?php echo $cur['name']?>
        </td>
        <td class="content-cell">
            <?php echo $cur['intid'].': '.$interface['name']?>
        </td>
    </tr>
<?php
}
?>
                </tbody>
                <tr>
                    <td colspan="6"><hr /></td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td class="add-cell">
                        <button class="create-new" id="add-conditions"
                            name="conditions">Add Condition</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div id="experiments-tab">
        <div class="center">
            <h3>Experiments</h3>
        </div>
        <div class="line"></div>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>ID</th>
                    <th>User</th>
                    <th>Condition</th>
                    <th>Environment</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
                <tr>
                    <td colspan="8"><hr /></td>
                </tr>
            </thead>
            <tbody>
<?php
// populate the table
$experiments = experiments::get_experiments();
$numExperiments = count($experiments);
for ($i = 0; $i < $numExperiments; $i++) {
    $cur = $experiments[$i];
    $user = user_accounts::get_user_account_by_id($cur['userid']);
    $condition = conditions::get_condition_by_id($cur['condid']);
    $study = studies::get_study_by_id($condition['studyid']);
    $env = environments::get_environment_by_id($cur['envid']);
    $id = 'experiments-'.$cur['expid'];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
                                <tr class="<?php echo $class?>">
                                    <td class="delete-cell">
                                        <button class="delete"
                                            name="experiments"
                                            id="<?php echo $id?>">Delete
                                            </button>
                                    </td>
                                    <td class="edit-cell">
                                        <button class="edit"
                                            name="experiments"
                                            id="<?php echo $id?>">Edit</button>
                                    </td>
                                    <td class="content-cell">
                                        <?php echo $cur['expid']?>
                                    </td>
                                    <td class="content-cell">
                                        <?php echo $cur['userid'].': '.
                                             $user['firstname'].' '.
                                             $user['lastname'].
                                             ' ('.$user['username'].')'?>
                                    </td>
                                    <td class="content-cell">
                                        <?php echo $cur['condid'].': '.
                                            $condition['name'].' ('.
                                            $study['name'].')'?>
                                    </td>
                                    <td class="content-cell">
                                        <?php echo $cur['envid'].': '.
                                            $env['envaddr']?>
                                    </td>
                                    <td class="content-cell">
                                        <?php echo $cur['start']?>
                                    </td>
                                    <td class="content-cell">
                                        <?php echo $cur['end']?>
                                    </td>
                                </tr>
<?php
}
?>
                            </tbody>
                            <tr>
                                <td colspan="8"><hr />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="7"></td>
                                <td class="add-cell">
                                    <button class="create-new"
                                        id="add-experiments"
                                        name="experiments">Add Experiment
                                        </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="browse-logs-tab">
                        <div class="center">
                            <h3>Experiment Logs</h3>
                        </div>
                        <div class="line"></div>
                        <div class="center">
                            <h2>Coming Soon</h2>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>
    <?php content::create_footer()?>
    </section>
    <div id="confirm-delete-popup" title="Delete?"></div>
    <div id="editor-popup" title="Editor"></div>
</html>
