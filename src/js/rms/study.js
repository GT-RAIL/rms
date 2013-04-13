/**
 * A collection of common Javascript functions used throughout the RMS for
 * interacting with the user study API.
 *
 * @fileOverview A collection of common Javascript functions used throughout the
 *  RMS for interacting with the user study API.
 * @name RMS Studies
 * @author Russell Toris <rctoris@wpi.edu>
 * @version April, 13 2013
 */

/**
 * Log the given message into the study_logs MySQL table. This function will
 * only work if the _EXPID global variable is defined.
 *
 * @param mesasge
 *            {string} the message to log
 */
function studyLog(message) {
  if (typeof (_EXPID) !== 'undefined') {
    // create an AJAX request
    var data = new FormData();
    data.append('expid', _EXPID);
    data.append('entry', message);
    $.ajax('../../api/user_studies/study_logs/', {
      data : data,
      cache : false,
      contentType : false,
      processData : false,
      type : 'POST',
      beforeSend: function (xhr) {
        // authenticate with the header
        xhr.setRequestHeader('RMS-Use-Session', 'true');
      }
    });
  }
}
