/**
 * A collection of common Javascript functions for dealing with user studies.
 * 
 * @fileOverview A collection of common Javascript functions for dealing with user studies.
 * @name RMS Study
 * @author Russell Toris <rctoris@wpi.edu>
 * @version December, 5 2012
 */

/**
 * Log the given message into the study_log MySQL table. This function will only work if the _EXPID
 * global variable is defined.
 * 
 * @param {String}
 *            message the message to log
 */
function studyLog(message) {
  if (typeof (_EXPID) !== 'undefined') {
    $.get('form/study_log.php?expid=' + _EXPID + '&entry=' + message);
  }
}
