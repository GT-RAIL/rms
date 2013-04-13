/**
 * A collection of common Javascript functions used throughout the RMS.
 *
 * @fileOverview A collection of common Javascript functions used throughout the
 *  RMS.
 * @name RMS Common
 * @author Russell Toris <rctoris@wpi.edu>
 * @version April, 13 2013
 */

/**
 * Creates the user menu buttons via JQuery UI.
 */
function createMenuButtons() {
  $(function() {
    $('a', '.menu-main-menu').button({
      icons : {
        primary : 'ui-icon-home'
      }
    });
    $('a', '.menu-account').button({
      icons : {
        primary : 'ui-icon-gear'
      }
    });
    $('a', '.menu-admin-panel').button({
      icons : {
        primary : 'ui-icon-wrench'
      }
    });
    $('a', '.menu-study-panel').button({
      icons : {
        primary : 'ui-icon-person'
      }
    });
    $('a', '.menu-logout').button({
      icons : {
        primary : 'ui-icon-power'
      }
    });
  });
}

/**
 * Creates a modal overlay with a loading icon. Disable the screen with
 * removeModalPageLoading().
 */
function createModalPageLoading() {
  // remove any old modals that may exist
  if ($('#LOADING-DIV')) {
    $('#LOADING-DIV').remove();
  }

  $('body').append('<div id="LOADING-DIV" class="modal"></div>');
  $('body').addClass('loading');
}

/**
 * Disable the loading modal overlay.
 */
function removeModalPageLoading() {
  $('#LOADING-DIV').remove();
  $('body').removeClass('loading');
}

/**
 * Creates a displays a modal dialog with the given error message.
 *
 * @param {string}
 *            message the error message to display
 */
function createErrorDialog(message) {
  // remove any old dialogs that may exist
  if ($('#ERROR-DIALOG')) {
    $('#ERROR-DIALOG').remove();
  }

  // create an error div
  var html = '<div id="ERROR-DIALOG"><b>';
  html += '<span class="ui-icon ui-icon-alert" ';
  html += 'style="float: left; margin: 0 7px 50px 0;"></span>';
  html += message;
  html += '</b></div>';
  $('body').append(html);

  $('#ERROR-DIALOG').dialog({
    title : 'Error!',
    draggable : false,
    resizable : false,
    dialogClass : 'alert',
    modal : true,
    show : 'fade',
    hide : 'fade',
    buttons : {
      Ok : function() {
        $(this).dialog('close');
      }
    }
  });
}

/**
 * Initializes the homepage slideshow.
 */
function createSlideshow() {
  $(function() {
    // initialize the slideshow
    $('#slides').slides({
      preload : true,
      preloadImage : 'css/images/loading.gif',
      play : 5000,
      pause : 3500,
      hoverPause : true,
      animationStart : function(current) {
        $('.caption').animate({
          bottom : -35
        }, 100);
      },
      animationComplete : function(current) {
        $('.caption').animate({
          bottom : 0
        }, 200);
      },
      slidesLoaded : function() {
        $('.caption').animate({
          bottom : 0
        }, 200);
      }
    });
  });
}

/**
 * A function to check if the given rosbridge server is online
 *
 * @param {String}
 *            protocol the protocol to use
 * @param {String}
 *            host the hostname of the rosbridge server
 * @param {int}
 *            port the port of the rosbridge server
 * @param {function}
 *            callback the callback function returning if the server is online
 */
function rosonline(protocol, host, port, callback) {
  var ros = new ROSLIB.Ros({
    url : protocol + host + ':' + port
  });
  ros.on('connection', function() {
    callback(true);
  });
  ros.on('error', function() {
    callback(false);
  });
}

/**
 * Makes a logout AJAX request.
 */
function logout() {
  createModalPageLoading();

  // create a AJAX request
  var formData = new FormData();
  formData.append('request', 'destroy_session');
  $.ajax('../../api/users/user_accounts/', {
    data : formData,
    cache : false,
    contentType : false,
    processData : false,
    type : 'POST',
    beforeSend : function(xhr) {
      // authenticate with the header
      xhr.setRequestHeader('RMS-Use-Session', 'true');
    },
    success : function(data) {
      // success
      window.location = '../';
    }
  });
}

/**
 * Base 64 encode the given string.
 *
 * @param {String}
 *            string the string to base 64 encode
 * @returns {String} the base 64 encoded string
 */
function base64Encode(string) {
  var key = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=';

  // convert to utf8
  string = string.replace(/\r\n/g, '\n');
  var utf = '';
  for ( var n = 0; n < string.length; n++) {
    var c = string.charCodeAt(n);
    if (c < 128) {
      utf += String.fromCharCode(c);
    } else if ((c > 127) && (c < 2048)) {
      utf += String.fromCharCode((c >> 6) | 192);
      utf += String.fromCharCode((c & 63) | 128);
    } else {
      utf += String.fromCharCode((c >> 12) | 224);
      utf += String.fromCharCode(((c >> 6) & 63) | 128);
      utf += String.fromCharCode((c & 63) | 128);
    }
  }

  // create the output buffer
  var output = '';
  var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
  var i = 0;
  // parse and convert the string
  while (i < utf.length) {
    chr1 = utf.charCodeAt(i++);
    chr2 = utf.charCodeAt(i++);
    chr3 = utf.charCodeAt(i++);
    enc1 = chr1 >> 2;
    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
    enc4 = chr3 & 63;

    if (isNaN(chr2)) {
      enc3 = enc4 = 64;
    } else if (isNaN(chr3)) {
      enc4 = 64;
    }

    // add to the output
    output = output + key.charAt(enc1) + key.charAt(enc2) + key.charAt(enc3)
        + key.charAt(enc4);
  }

  return output;
}
