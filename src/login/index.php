<?php
/**
 * Main login page for RMS.
 *
 * The login form allows a user to create a PHP session
 *
 * @author     Russell Toris <rctoris@wpi.edu>
 * @copyright  2013 Russell Toris, Worcester Polytechnic Institute
 * @license    BSD -- see LICENSE file
 * @version    April, 13 2013
 * @package    login
 * @link       http://ros.org/wiki/rms
 */

// start the session
session_start();

// check if there is a user logged in
if (isset($_SESSION['userid'])) {
  header('Location: ../menu');
}

// load the include files
include_once(dirname(__FILE__).'/../inc/head.inc.php');
include_once(dirname(__FILE__).'/../inc/content.inc.php');

$pagename = 'User Login';
?>

<!DOCTYPE html>
<html>
<head>
<?php head::import_head('../')?>
<title><?php echo $title.' :: '.$pagename?></title>
<script type="text/javascript">
  /**
   * Submits the login form via AJAX to the RMS API.
   */
  function submit() {
    createModalPageLoading();

    var formData = new FormData();
    formData.append('request', 'server_session');
    // create a AJAX request
    $.ajax('../api/users/user_accounts/', {
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      type : 'POST',
      beforeSend: function (xhr) {
        // set the username and password
        var auth = base64Encode($('#username').val() + 
                ':' + $('#password').val());
        xhr.setRequestHeader('Authorization', 'Basic ' + auth);
      },
      success : function(data){
        // success
         window.location = '<?php echo isset($_GET['goto']) ? 
                                    '../'.$_GET['goto'] : '../menu'?>';
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
<?php content::create_header(null, $pagename, '../')?>
  <section class="page">
    <article>
      <h2>User Login</h2>
      <div class="line"></div>
      <table class="center">
        <tr>
          <td>
            <form action="javascript:submit();">
              <fieldset>
                <ol>
                  <li><label for="username">Username</label> <input
                    type="text" name="username" id="username" required
                    autofocus />
                  </li>
                  <li><label for="password">Password</label> <input
                    type="password" name="password" id="password"
                    required />
                  </li>
                </ol>
                <input type="submit" value="Login" />
              </fieldset>
            </form>
          </td>
        </tr>
      </table>
    </article>
    <?php content::create_footer()?>
  </section>
</body>
</html>
