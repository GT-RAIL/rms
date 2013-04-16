<?php
/**
 * Main admin panel for the RMS.
 *
 * The main admin panel allows read/write access to most of the RMS database via
 * a GUI.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 15 2013
 * @package    admin
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

// check if a user is logged in
if (!isset($_SESSION['userid'])) {
    header('Location: ../login/?goto=admin');
    return;
}

// the name of the admin page
$pagename = 'Admin Panel';

// load the include files
include_once(dirname(__FILE__).'/../api/api.inc.php');
include_once(dirname(__FILE__).'/../api/config/config.inc.php');
include_once(dirname(__FILE__).'/../api/config/logs/logs.inc.php');
include_once(dirname(__FILE__).'/../api/content/articles/articles.inc.php');
include_once(dirname(__FILE__).
        '/../api/content/content_pages/content_pages.inc.php');
include_once(dirname(__FILE__).'/../api/content/slides/slides.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/robot_environments.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/environments/environments.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/interfaces/interfaces.inc.php');
include_once(dirname(__FILE__).
        '/../api/robot_environments/widgets/widgets.inc.php');
include_once(dirname(__FILE__).
        '/../api/users/user_accounts/user_accounts.inc.php');
include_once(dirname(__FILE__).'/../inc/head.inc.php');
include_once(dirname(__FILE__).'/../inc/content.inc.php');

// grab the user info from the database
$sessionUser = user_accounts::get_user_account_by_id($_SESSION['userid']);

// now make sure this is an admin
if ($sessionUser['type'] !== 'admin') {
    $warn = $sessionUser['username'].' attempted to access the admin panel.';
    logs::write_to_log('WARNING: '.$warn);
    // send the user back to their main menu
    header('Location: /menu');
    return;
}

// grab the database version
$dbVersion = config::get_db_version();
// grab the code version
$codeVersion = config::get_init_sql_version(
    dirname(__FILE__).'/../api/config/init.sql', true
);
// find our the live version
$liveVersion = config::get_init_sql_version(
    'https://raw.github.com/WPI-RAIL/rms/stable/www/api/config/init.sql'
);
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
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/EventEmitter2/0.4.11/eventemitter2.js">
</script>
<script type="text/javascript"
    src="http://cdn.robotwebtools.org/roslibjs/r5/roslib.min.js"></script>
<script type="text/javascript">
  var script = '';

  /**
   * The start function creates all of the JQuery UI elements and button 
   * callbacks.
   */
  function start() {
    // converts a DOM name attribute to an API script path
    var nameToAPIScript = function(name) {
      if (name === 'users') {
        return '../api/users/user_accounts/';
      } else if (name === 'environments') {
        return '../api/robot_environments/environments/';
      } else if (name === 'interfaces') {
        return '../api/robot_environments/interfaces/';
      } else if (name === 'environment-interfaces') {
        return '../api/robot_environments/';
      } else if (name === 'widgets') {
        return '../api/robot_environments/widgets/';
      } else if (name.indexOf('widget-') === 0) {
        var id = name.substring(name.indexOf('-') + 1);
        return '../api/robot_environments/widgets/?widgetid=' + id;
      } else if (name === 'slides') {
        return '../api/content/slides/';
      } else if (name === 'pages') {
        return '../api/content/content_pages/';
      } else if (name === 'articles') {
        return '../api/content/articles/';
      } else if (name === 'settings' || name === 'db-update') {
        return '../api/config/';
      } else {
        return 'UNKNOWN';
      }
    };

    // create the user menu
    createMenuButtons();

    // create the tabs for the admin page
    $('#admin-tabs').tabs();

    // create the delete icon buttons
    $('.delete').button({
        icons: {primary: "ui-icon-circle-close"},
        text: false
    });

    // delete button callback
    $('.delete').click(function(e) {
      // find the button
      var b;
      if ($(e.target).is('button')) {
        b = $(e.target);
      } else {
        b = $(e.target).parent('button');
      }

      // find the type and ID
      var deleteScript = nameToAPIScript(b.attr('name'));
      var idString = b.attr('id');
      var id = idString.substring(idString.lastIndexOf('-') + 1);

      // special case -- generic delete script
      var dataToDelete = '';
      if (deleteScript.indexOf('?') > 0) {
        var widgetID = deleteScript.substring(deleteScript.indexOf('=') + 1);
        dataToDelete += 'widgetid=' + widgetID + '&';
        deleteScript = deleteScript.substring(0, deleteScript.indexOf('?'));
      }
      dataToDelete += 'id=' + id;

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
            createModalPageLoading();
            // make a delete request
            $.ajax(deleteScript, {
              data : dataToDelete,
              type : 'DELETE',
              beforeSend: function (xhr) {
                // authenticate with the header
                xhr.setRequestHeader('RMS-Use-Session', 'true');
              },
              success : function(data){
                // success -- go back to the login page for the correct redirect
                window.location.reload();
              }
            });
          }
        },
        autoOpen: false
      });
      var html = '<p><span class="ui-icon ui-icon-alert" style="float:left; ';
      html += 'margin:0 7px 50px 0;"></span>Are you sure you want to delete ';
      html += 'the selected item? This <b>cannot</b> be reversed.</p>'
      confirm.html(html);
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
      // find the button
      var b;
      if ($(e.target).is('button')) {
        b = $(e.target);
      } else {
        b = $(e.target).parent('button');
      }

      var type = b.attr('name');

      // special cases --  DB updater
      if (type === 'db-update') {
        var html = '<p>By using this form, you will update the RMS database ';
        html += 'to version <?php echo $codeVersion?>.</p>';
        html += '<form action="javascript:updateDBRequest();">';
        html += '<input type="submit" value="Update" /></form>';

        $('#editor-popup').html(html);
        $('#editor-popup').dialog('open');
      } else {
        createModalPageLoading();

        // grab the script name
        script = nameToAPIScript(type);
        // special case -- generic widget editor
        if (script.indexOf('?') > 0) {
          var id = script.substring(script.indexOf('=') + 1);
          var url = script.substring(0, script.indexOf('?'));
          url += '?request=editor&widgetid=' + id;
        } else {
          var url = script + '?request=editor';
        }

        // now check if we are getting an ID as well
        var idString = b.attr('id');
        if (idString.lastIndexOf(type + '-') === 0) {
          var id = idString.substring(idString.lastIndexOf('-') + 1);
          url += '&id=' + id;
        }

        // create an AJAX request
        $.ajax(url, {
          type : 'GET',
          beforeSend: function (xhr) {
            // authenticate with the header
            xhr.setRequestHeader('RMS-Use-Session', 'true');
          },
          success : function(data){
            $('#editor-popup').html(data.data);
            removeModalPageLoading();
            $('#editor-popup').dialog('open');
          }
        });
      }
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

    // creates the preview popup
    $('#preview-popup').dialog({
      position: ['center', 100],
      autoOpen: false,
      draggable: false,
      resizable: false,
      modal: true,
      show: 'blind',
      width: 1050,
      buttons: {
        Close: function() {
          $(this).dialog('close');
        }
      }
    });
  }

  /**
   * A function to set the HTML of the 'preview-popup'.
   */
  function preview() {
    var title = $('#title').val();
    var content = $('#content').val();
    // create the HTML
    var html = '<section class="page"><article><h2>'+$('#title').val()+'</h2>';
    html += '<div class="line"></div><div class="clear">';
    html += $('#content').val()+'</div></article></section>';
    $('#preview-popup').html(html);
    // fix image links
    $('#preview-popup').find('img').each(function(){
      if ($(this).attr('src').indexOf('img/') === 0) {
        $(this).attr('src', '../' + $(this).attr('src'));
      }
    });
    // open the dialog
    $('#preview-popup').dialog('open');
  }

  /**
   * The main submit callback for the editor forms. This will make the correct
   * AJAX call for the form.
   */
    function submit() {
     createModalPageLoading();

     // the actual function to make the request
     var makeRequest = function(url, dataToSubmit, ajaxType, onSuccess) {
       // create a AJAX request
       $.ajax(url, {
         data : dataToSubmit,
         cache : false,
         contentType : false,
         processData : false,
         type : ajaxType,
         beforeSend: function (xhr) {
           // authenticate with the header
           xhr.setRequestHeader('RMS-Use-Session', 'true');
         },
         success : function(data) {
           onSuccess(data);
         },
         error : function(data) {
           // display the error
           var response = JSON.parse(data.responseText);
           removeModalPageLoading();
           createErrorDialog(response.msg);
         }
       });
     };

     // check the password
     if ($('#password').val() !== $('#password-confirm').val()) {
       removeModalPageLoading();
       createErrorDialog('ERROR: Passwords do not match.');
     } else {
      // go through each input to build the AJAX request
      var form = $('#editor-popup').find('form');
      var ajaxType = 'POST';
      var putString = '';
      var formData = new FormData();
      // used for changing slideshow images
      var putFile = {field: '', file: null};
      form.find(':input').each(function(){
        if ($(this).attr('type') === 'file') {
          // do the file upload
          var file = $(this)[0].files[0];
          if (file) {
            hasFile = true;
            formData.append($(this).attr('name'), file);
            putFile.field = $(this).attr('name');
            putFile.file = file;
          }
        } else if ($(this).attr('type') !== 'submit' 
          && $(this).attr('name') !== 'password-confirm' 
          && $(this).attr('name') !== 'js-dummy') {
          if ($(this).attr('name') === 'id') {
            ajaxType = 'PUT';
          }

          if ($(this).attr('name') !== 'password' 
              || $(this).val() !== '<?php echo api::$passwordHolder?>') {
              if (putString.length > 1) {
              putString += '&';
            }
            // check if this is a checkbox (tiny int)
            if ($(this).attr('type') === 'checkbox') {
              if ($(this).is(':checked')) {
                putString += $(this).attr('name') + '=1';
                formData.append($(this).attr('name'), 1);
              } else {
                putString += $(this).attr('name') + '=0';
                formData.append($(this).attr('name'), 0);
              }
            } else {
              // escape the '&'
              putString += $(this).attr('name') + '=';
              putString += $(this).val().replace(/&/g, '%26');
              formData.append($(this).attr('name'), $(this).val());
            }
          }
        }
        });

      // special case -- generic widget editor
      var finalScript = script;
      if (script.indexOf('?') > 0) {
        finalScript = script.substring(0, script.indexOf('?'));
        var id = script.substring(script.indexOf('=') + 1);
        putString += '&widgetid=' + id;
        formData.append('widgetid', id);
      }

      // special case -- site settings editor
      if (finalScript === '../api/config/') {
        ajaxType = 'PUT';
      }

      // check if this is a POST or PUT
      var dataToSubmit = formData;
      if (ajaxType === 'PUT') {
        dataToSubmit = putString;

        // check if we have a file request
        if (putFile.file) {
          // make a request to upload the file first
          var uploadData = new FormData();
          uploadData.append(putFile.field, putFile.file);
          makeRequest(finalScript, uploadData, 'POST', function(data){});

          dataToSubmit += '&' + putFile.field + '=' + putFile.file.name;
        }
      }

      makeRequest(finalScript, dataToSubmit, ajaxType, 
    	      function(data){window.location.reload();});
    }
    }

    /**
   * Make an AJAX request to update the RMS database.
   */
    function updateDBRequest() {
    createModalPageLoading();

    // create a AJAX request
    var formData = new FormData();
    formData.append('request', 'update');
    $.ajax('../api/config/', {
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      type : 'POST',
      beforeSend: function (xhr) {
        // authenticate with the header
        xhr.setRequestHeader('RMS-Use-Session', 'true');
      },
      success : function(data){
        // success
        window.location.reload();
      },
      error : function(data){
        // display the error
        var response = JSON.parse(data.responseText);
        removeModalPageLoading();
        createErrorDialog(response.msg);
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
                            <li><a href="#users-tab">Manage Users</a>
                            </li>
                            <li><a href="#site-log-tab">Site Log</a>
                            </li>
                            <li><a href="#environments-tab">
                                    Manage Environments
                                </a>
                            </li>
                            <li><a href="#pages-tab">Manage Pages</a>
                            </li>
                            <li><a href="#site-tab">Site Settings</a>
                            </li>
                            <li><a href="#maintenance-tab">Site Maintenance</a>
                            </li>
                        </ul>
                        <div id="users-tab">
                            <div class="center">
                                <h3>Users</h3>
                            </div>
                            <div class="line"></div>
                            <table class="tablesorter">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>E-mail</th>
                                        <th>Role</th>
                                    </tr>
                                    <tr>
                                        <td colspan="8"><hr />
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
<?php
// populate the table
$userAccounts = user_accounts::get_user_accounts();
$numUsers = count($userAccounts);
for ($i = 0; $i < $numUsers; $i++) {
      $cur = $userAccounts[$i];
      $class = ($i % 2 == 0) ? 'even' : 'odd';?>
        <tr class="<?php echo $class?>">
            <td class="delete-cell">
                <button class="delete" name="users"
                    id="users-<?php echo $cur['userid']?>">Delete</button>
            </td>
            <td class="edit-cell">
                <button class="edit" name="users"
                    id="users-<?php echo $cur['userid']?>">Edit</button>
            </td>
            <td class="content-cell"><?php echo $cur['userid']?>
            </td>
            <td class="content-cell"><?php echo $cur['username']?>
            </td>
            <td class="content-cell"><?php echo $cur['firstname']?>
            </td>
            <td class="content-cell"><?php echo $cur['lastname']?>
            </td>
            <td class="content-cell"><?php echo $cur['email']?>
            </td>
            <td class="content-cell"><?php echo $cur['type']?>
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
                                id="add-users" name="users">Add User</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div id="site-log-tab">
                <div class="center">
                    <h3>Site Log</h3>
                </div>
                <div class="line"></div>
                <div id="log-container">
                    <table class="table-log">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Timestamp</th>
                                <th>URI</th>
                                <th>Address</th>
                                <th>Enrty</th>
                            </tr>
                            <tr>
                                <td colspan="5"><hr />
                                </td>
                            </tr>
                        </thead>
                        <tbody>
<?php
// populate the table
$logs = logs::get_logs();
$numLogs = count($logs);
for ($i = 0; $i < $numLogs; $i++) {
    $cur = $logs[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td><?php echo $cur['logid']?>
        </td>
        <td class="content-cell"><?php echo $cur['timestamp']?>
        </td>
        <td class="content-cell"><?php echo $cur['uri']?>
        </td>
        <td class="content-cell"><?php echo $cur['addr']?>
        </td>
        <td class="content-cell"><?php echo $cur['entry']?>
        </td>
    </tr>
<?php
}
?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="environments-tab">
                    <div class="center">
                        <h3>Environments</h3>
                    </div>
                    <div class="line"></div>
                    <table class="tablesorter">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>ID</th>
                                <th>Protocol</th>
                                <th>Address</th>
                                <th>Port</th>
                                <th>MJPEG Server</th>
                                <th>MJPEG Port</th>
                                <th>Status</th>
                            </tr>
                            <tr>
                                <td colspan="9"><hr />
                                </td>
                            </tr>
                        </thead>
                        <tbody>
<?php
// populate the table
$environments = environments::get_environments();
$numEnvironments = count($environments);
for ($i = 0; $i < $numEnvironments; $i++) {
    $cur = $environments[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell">
            <button class="delete"
                name="environments"
                id="environments-<?php echo $cur['envid']?>">Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit" name="environments"
                id="environments-<?php echo $cur['envid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['envid']?>
        </td>
        <td class="content-cell"><?php echo $cur['protocol']?>
        </td>
        <td class="content-cell"><?php echo $cur['envaddr']?>
        </td>
        <td class="content-cell"><?php echo $cur['port']?>
        </td>
        <td class="content-cell"><?php echo $cur['mjpeg']?>
        </td>
        <td class="content-cell"><?php echo $cur['mjpegport']?>
        </td>
<?php
    if ($cur['enabled']) {// check if the environment is enabled?
        echo '<script type="text/javascript">
              rosonline(\''.$cur['protocol'].'\', \''.$cur['envaddr'].'\', '.
                  $cur['port'].', function(isonline) {
              if (isonline) {
                  $(\'#envstatus-'.$cur['envid'].'\').html(\'ONLINE\');
              } else {
                  $(\'#envstatus-'.$cur['envid'].'\').html(\'OFFLINE\');
              }
              });
              </script>';?>
        <td class="content-cell"><div
                id="envstatus-<?php echo $cur['envid']?>">Acquiring
                connection...</div>
        </td>
<?php
    } else {?>
        <td class="content-cell"><div
                id="envstatus-<?php echo $cur['envid']?>">DISABLED</div>
        </td>
<?php
    }
 ?>
    </tr>
<?php
}
?>
            </tbody>
            <tr>
                <td colspan="9"><hr />
                </td>
            </tr>
            <tr>
                <td colspan="8"></td>
                <td class="add-cell">
                    <button class="create-new"
                        id="add-environments"
                        name="environments">Add Environment</button>
                </td>
            </tr>
        </table>
        <br /> <br />
        <div class="center">
            <h3>Interface</h3>
        </div>
        <div class="line"></div>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Directory</th>
                </tr>
                <tr>
                    <td colspan="5"><hr />
                    </td>
                </tr>
            </thead>
            <tbody>
<?php
// populate the table
$interfaces = interfaces::get_interfaces();
$numInterfaces = count($interfaces);
for ($i = 0; $i < $numInterfaces; $i++) {
    $cur = $interfaces[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell"><button
                class="delete" name="interfaces"
                id="interfaces-<?php echo $cur['intid']?>">Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit" name="interfaces"
                id="interfaces-<?php echo $cur['intid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['intid']?>
        </td>
        <td class="content-cell"><?php echo $cur['name']?>
        </td>
        <td class="content-cell"><?php echo $cur['location']?>
        </td>
    </tr>
<?php
}
?>
            </tbody>
            <tr>
                <td colspan="5"><hr />
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="add-cell">
                    <button class="create-new"
                        id="add-interface" name="interfaces">
                        Add Interface</button>
                </td>
            </tr>
        </table>
        <br /> <br />
        <div class="center">
            <h3>Environment-Interface Pairings</h3>
        </div>
        <div class="line"></div>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>ID</th>
                    <th>Environment ID</th>
                    <th>Interface ID</th>
                </tr>
                <tr>
                    <td colspan="5"><hr />
                    </td>
                </tr>
            </thead>
            <tbody>
<?php
// populate the table
$pairs = robot_environments::get_environment_interface_pairs();
$numPairs = count($pairs);
for ($i = 0; $i < $numPairs; $i++) {
    $cur = $pairs[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';
    // grab the interface and environment variables
    $env = environments::get_environment_by_id($cur['envid']);
    $int = interfaces::get_interface_by_id($cur['intid']);
?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell"><button
                class="delete"
                name="environment-interfaces"
                id="environment-interfaces-<?php echo $cur['pairid']?>">
                Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit"
                name="environment-interfaces"
                id="environment-interfaces-<?php echo $cur['pairid']?>">
                Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['pairid']?>
        </td>
        <td class="content-cell"><?php echo $env['envid'].': '.$env['envaddr']?>
        </td>
        <td class="content-cell"><?php echo $int['intid'].': '.$int['name'].
            ' -- api/robot_environments/interfaces/'.$int['location']?>
        </td>
    </tr>
    <?php
}
?>
            </tbody>
            <tr>
                <td colspan="5"><hr />
                </td>
            </tr>
            <tr>
                <td colspan="4"></td>
                <td class="add-cell">
                    <button class="create-new"
                        id="add-environment-interfaces"
                        name="environment-interfaces">Add
                        Pairing</button>
                </td>
            </tr>
        </table>
        <br /> <br />
        <div class="center">
            <h3>Widgets</h3>
        </div>
        <div class="line"></div>
        <table class="tablesorter">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>SQL Table</th>
                </tr>
                <tr>
                    <td colspan="5"><hr />
                    </td>
                </tr>
            </thead>
            <tbody>
<?php
// populate the table
$widgets = widgets::get_widgets();
$numWidgets = count($widgets);
for ($i = 0; $i < $numWidgets; $i++) {
    $cur = $widgets[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell"><button
                class="delete" name="widgets"
                id="widgets-<?php echo $cur['widgetid']?>">Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit" name="widgets"
                id="widgets-<?php echo $cur['widgetid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['widgetid']?>
        </td>
        <td class="content-cell"><a
            href="#widget<?php echo $cur['widgetid']?>">
            <?php echo $cur['name']?>
        </a>
        </td>
        <td class="content-cell"><?php echo $cur['table']?>
        </td>
    </tr>
<?php
}
?>
        </tbody>
        <tr>
            <td colspan="5"><hr />
            </td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="add-cell">
                <button class="create-new"
                    id="add-widget" name="widgets">Add
                    Widget</button>
            </td>
        </tr>
    </table>
<?php
// individual widget tables
foreach ($widgets as $w) {?>
    <br /> <br />
    <div class="center">
        <h3 id="widget<?php echo $w['widgetid']?>">
            <?php echo $w['name']?>
        </h3>
    </div>
    <div class="line"></div>
    <table class="tablesorter">
        <thead>
    <?php
    // build an array of the column names
    $attributes = widgets::get_widget_table_columns_by_id($w['widgetid']);
    $numAtt = count($attributes);?>
    <tr>
        <th></th>
        <th></th>
    <?php
    foreach ($attributes as $label) {
        if ($label === 'id') {
        echo '<th>ID</th>';
        } else if ($label === 'envid') {
        echo '<th>Environment</th>';
        } else {
        echo '<th>'.$label.'</th>';
        }
    }?>
        </tr>
        <tr>
            <td colspan="<?php echo ($numAtt+2)?>"><hr />
            </td>
        </tr>
    </thead>
    <tbody>
    <?php
    // populate the table
    $instances = widgets::get_widget_instances_by_widgetid($w['widgetid']);
    $numInstances = count($instances);
    for ($i = 0; $i < $numInstances; $i++) {
        $cur = $instances[$i];
        $class = ($i % 2 == 0) ? 'even' : 'odd';?>
                        <tr class="<?php echo $class?>">
                            <td class="delete-cell"><button
                                    class="delete"
                                    name="widget-<?php echo $w['widgetid']?>"
                                    id="widget-<?php echo $w['widgetid'].'-'.
                                    $cur['id']?>">Delete</button>
                            </td>
                            <td class="edit-cell"><button
                                    class="edit"
                                    name="widget-<?php echo $w['widgetid']?>"
                                    id="widget-<?php echo $w['widgetid'].'-'.
                                    $cur['id']?>">Edit</button>
                            </td>
        <?php
        foreach ($attributes as $label) {
            if ($label === 'envid') {
            $env = environments::get_environment_by_id($cur[$label]);
            echo '<td class="content-cell">'.$env['envid'].': '.$env['envaddr'].
                '</td>';
            } else {
                echo '<td class="content-cell">'.$cur[$label].'</td>';
            }
        }
?>
    </tr>
    <?php
    }
?>
            </tbody>
            <tr>
                <td colspan="<?php echo ($numAtt+2)?>">
                    <hr />
                </td>
            </tr>
            <tr>
                <td colspan="<?php echo ($numAtt+1)?>"></td>
                <td class="add-cell">
                    <button class="create-new"
                        id="add-widget-<?php echo $w['widgetid']?>"
                        name="widget-<?php echo $w['widgetid']?>">
                        Add <?php echo $w['name']?>
                    </button>
                </td>
            </tr>
        </table>
<?php
}
?>
        </div>
        <div id="pages-tab">
            <div id="slideshow">
                <div class="center">
                    <h3>Homepage Slideshow</h3>
                </div>
                <div class="line"></div>
                <table class="tablesorter">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Caption</th>
                            <th>Index</th>
                        </tr>
                        <tr>
                            <td colspan="6"><hr />
                            </td>
                        </tr>
                    </thead>
                    <tbody>
<?php
// populate the table
$slides = slides::get_slides();
$numSlides = count($slides);
for ($i = 0; $i < $numSlides; $i++) {
    $cur = $slides[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell"><button
                class="delete" name="slides"
                id="slides-<?php echo $cur['slideid']?>">Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit" name="slides"
                id="slides-<?php echo $cur['slideid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['slideid']?>
        </td>
        <td class="content-cell"><?php echo $cur['img']?>
        </td>
        <td class="content-cell"><?php echo $cur['caption']?>
        </td>
        <td class="content-cell"><?php echo $cur['index']?>
        </td>
    </tr>
<?php
}
?>
                </tbody>
                <tr>
                    <td colspan="6"><hr />
                    </td>
                </tr>
                <tr>
                    <td colspan="5"></td>
                    <td class="add-cell">
                        <button class="create-new"
                            id="add-slides" name="slides">Add
                            Slide</button>
                    </td>
                </tr>
            </table>
        </div>
        <div id="pages">
            <br /> <br />
            <div class="center">
                <h3>Content Pages</h3>
            </div>
            <div class="line"></div>
            <table class="tablesorter">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Menu Name</th>
                        <th>Menu Index</th>
                        <th>Javascript File</th>
                    </tr>
                    <tr>
                        <td colspan="7"><hr />
                        </td>
                    </tr>
                </thead>
                <tbody>
<?php
// populate the table
$pages = content_pages::get_content_pages();
$numPages = count($pages);
for ($i = 0; $i < $numPages; $i++) {
    $cur = $pages[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell"><button
                class="delete" name="pages"
                id="pages-<?php echo $cur['pageid']?>">Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit" name="pages"
                id="pages-<?php echo $cur['pageid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['pageid']?>
        </td>
        <td class="content-cell"><?php echo $cur['title']?>
        </td>
        <td class="content-cell"><?php echo $cur['menu']?>
        </td>
        <td class="content-cell"><?php echo $cur['index']?>
        </td>
 <?php
    if ($cur['js']) {
        echo '<td class="content-cell">'.$cur['js'].'</td>';
    } else {
        echo '<td class="content-cell">---</td>';
    }?>
    </tr>
<?php
}?>
                </tbody>
                <tr>
                    <td colspan="7"><hr />
                    </td>
                </tr>
                <tr>
                    <td colspan="6"></td>
                    <td class="add-cell">
                        <button class="create-new"
                            id="add-pages" name="pages">Add
                            Page</button>
                    </td>
                </tr>
            </table>
        </div>
        <div id="articles">
            <br /> <br />
            <div class="center">
                <h3>Content Page Articles</h3>
            </div>
            <div class="line"></div>
            <table class="tablesorter">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Page</th>
                        <th>Index</th>
                    </tr>
                    <tr>
                        <td colspan="7"><hr />
                        </td>
                    </tr>
                </thead>
                <tbody>
<?php
// populate the table
$articles = articles::get_articles();
$numArticles = count($articles);
for ($i = 0; $i < $numArticles; $i++) {
    $cur = $articles[$i];
    $class = ($i % 2 == 0) ? 'even' : 'odd';?>
    <tr class="<?php echo $class?>">
        <td class="delete-cell"><button
                class="delete"
                name="articles"
                id="articles-<?php echo $cur['artid']?>">Delete</button>
        </td>
        <td class="edit-cell"><button
                class="edit" name="articles"
                id="articles-<?php echo $cur['artid']?>">Edit</button>
        </td>
        <td class="content-cell"><?php echo $cur['artid']?>
        </td>
        <td class="content-cell"><?php echo $cur['title']?>
        </td>
        <?php
        // check if we should trim the string to fit in the table
    if (strlen(htmlentities($cur['content'])) > 30) {
        echo '<td class="content-cell">'.
            substr(htmlentities($cur['content']), 0, 30).'...</td>';
    } else {
        echo '<td class="content-cell">'.htmlentities($cur['content']).'</td>';
    }
      // grab the page name from the database
      $res = content_pages::get_content_page_by_id($cur['pageid']);?>
        <td class="content-cell"><?php echo $cur['pageid'].': '.$res['title']?>
        </td>
        <td class="content-cell"><?php echo $cur['index']?>
        </td>
    </tr>
<?php
}?>
                                    </tbody>
                                    <tr>
                                        <td colspan="7"><hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td class="add-cell">
                                            <button class="create-new"
                                                id="add-articles"
                                                name="articles">
                                             Add Article</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="site-tab">
                            <div id="site">
                                <div class="center">
                                    <h3>Site Settings</h3>
                                </div>
                                <div class=line></div>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td width="33%" rowspan="7"></td>
                                            <td class="setting-label">Database
                                                Host:</td>
                                            <td><?php echo $dbhost?>
                                            </td>
                                            <td width="33%" rowspan="7"></td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">Database
                                                Name:</td>
                                            <td><?php echo $dbname?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">Database
                                                Username:</td>
                                            <td><?php echo $dbuser?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class=setting-label>Database
                                                Password:</td>
                                            <td>
<?php echo api::$passwordHolder?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">
                                                Site Name:
                                            </td>
                                            <td><?php echo $title?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">Google
                                                Analytics Tracking ID:</td>
                                            <td>
<?php echo (isset($googleTrackingID)) ? $googleTrackingID : '---'?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">Copyright
                                                Message:</td>
                                            <td>
<?php echo substr($copyright, 6)?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tr>
                                        <td colspan="4"><hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="add-cell">
                                            <button class="create-new"
                                                id="add-site" name="settings">
                                                Edit Settings</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div id="maintenance-tab">
                            <div id="site-status">
                                <div class="center">
                                    <h3>Site Status</h3>
                                </div>
                                <div class=line></div>
<?php $disable = ($dbVersion < $codeVersion) 
    ? '' : 'disabled="disabled"' // check if an update is needed?>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td width="33%" rowspan="2"></td>
                                            <td class="setting-label">Database
                                                Version:</td>
                                            <td><?php echo $dbVersion?>
                                            </td>
                                            <td width="33%" rowspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">Code
                                                Version:</td>
                                            <td><?php echo $codeVersion?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4"><hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="33%" rowspan="1"></td>
                                            <td class="setting-label">Released
                                                Version:</td>
                                            <td><?php echo $liveVersion?>
                                            </td>
                                            <td width="33%" rowspan="1"></td>
                                        </tr>
                                    </tbody>
                                    <tr>
                                        <td colspan="4"><hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3"></td>
                                        <td class="add-cell">
                                            <button class="create-new"
                                                id="update-db" name="db-update"
                                                <?php echo $disable?>>Run
                                                Database Update</button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="privileges">
                                <div class="center">
                                    <h3>Directory Privileges</h3>
                                </div>
                                <div class=line></div>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td width="33%" rowspan="2"></td>
                                            <td class="setting-label">inc:</td>
                                            <td>
<?php echo is_writable(dirname(__FILE__).'/../inc') 
    ? 'Writable' : '<b>UN-WRITABLE</b>'?>
                                            </td>
                                            <td width="33%" rowspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="setting-label">
                                                img/slides:</td>
                                            <td>
<?php echo is_writable(dirname(__FILE__).'/../img/slides') 
    ? 'Writable' : '<b>UN-WRITABLE</b>'?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tr>
                                        <td colspan="4"><hr />
                                        </td>
                                    </tr>
                                </table>
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
    <div id="preview-popup" class="preview-popup" title="Content Preview"></div>

</html>
