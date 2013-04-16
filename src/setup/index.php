<?php
/**
 * Main setup form for RMS.
 *
 * The setup form can only be run if the 'inc/config.inc.php' file is missing.
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    setup
 * @link       http://ros.org/wiki/rms
 */

// check if the setup has already finished
if (file_exists(dirname(__FILE__).'/../inc/config.inc.php')) {
    header('Location: ../');
    return;
}

include_once(dirname(__FILE__).'/../inc/head.inc.php');
?>
<!DOCTYPE html>
<html>
<head>
<?php head::import_head('../')?>
<title>Robot Management System Setup</title>
<script type="text/javascript">
  /**
   * Submits the setup form via AJAX to the RMS API.
   */
  function submit() {
    createModalPageLoading();

    var formData = new FormData($('form')[0]);
    // create a AJAX request
    $.ajax('../api/config/', {
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      type : 'POST',
      success : function(data){
        // success!
        window.location = '../';
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
<body>
    <header class="clear">
        <div class="center">
            <h1>Robot Management System Setup</h1>
        </div>
    </header>
    <section class="page">
        <div class="line"></div>
        <article>
            <div class="center">
                <form action="javascript:submit();" method="POST"
                    enctype="multipart/form-data">
                    <fieldset>
                        <br />
                        <h3>MySQL Database Information</h3>
                        <ol>
                            <li><label for="host">Database Host</label> <input
                                type="text" name="host" id="host"
                                placeholder="e.g., localhost" required />
                            </li>
                            <li><label for="db">Database Name</label> <input
                                type="text" name="db" id="db"
                                placeholder="e.g., my_remote_lab" required />
                            </li>
                            <li><label for="dbuser">Database Username</label>
                            <input type="text" name="dbuser" id="dbuser"
                                placeholder="username" required />
                            </li>
                            <li><label for="dbpass">Database Password</label>
                            <input type="password" name="dbpass" id="dbpass"
                                placeholder="password" required />
                            </li>
                        </ol>
                    </fieldset>
                    <fieldset>
                        <br />
                        <h3>Site Information</h3>
                        <ol>
                            <li><label for="site-name">Site Name</label> <input
                                type="text" name="site-name" id="site-name"
                                placeholder="e.g., My Awesome Title" required />
                            </li>
                            <li><label for="google">Google Analytics Tracking ID
                                    (optional)</label>
                                <input type="text"
                                    name="google" id="google"
                                    placeholder="(optional)" />
                            </li>
                            <li><label for="copyright">Copyright Message</label>
                                <input type="text" name="copyright"
                                id="copyright"
                                placeholder="e.g., 2012 My Robot College"
                                required />
                            </li>
                        </ol>
                    </fieldset>
                    <fieldset>
                        <br />
                        <h3>SQL Database Restoration (optional)</h3>
                        <ol>
                            <li>
                                <label for="sqlfile">
                                    SQL Backup File (optional)
                                 </label>
                                <input type="file" name="sqlfile" 
                                    id="sqlfile" />
                            </li>
                        </ol>
                    </fieldset>
                    <input type="submit" value="Create" />
                </form>
            </div>
        </article>
    </section>
</body>
</html>
