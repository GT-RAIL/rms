/**
 * The RMS JavaScript library provides some useful RMS functions such as validating a rosbridge connection.
 *
 * @author Russell Toris - rctoris@wpi.edu
 */

var RMS = RMS || {
  VERSION : '2.0.0'
}

/**
 * Attempt to connect to the given rosbridge server. If a connection is made, a green thumbs up is placed in the HTML of
 * the element with the given ID. If a connection is not made, a red thumbs down is placed in the HTML of the element
 * with the given ID.
 *
 * @param protocol The WebSocket protocol to use ('ws' or 'wss)
 * @param host The host to connect to.
 * @param port The port to connect to.
 * @param id The ID of the element to place the icon in.
 */
RMS.verifyRosbridge = function(protocol, host, port, id) {
  var ros = new ROSLIB.Ros();
  var ele = $('#' + id);
  ros.on('connection', function() {
    ele.html('<span class="icon green fa-thumbs-o-up"></span>');
  });
  ros.on('error', function() {
    ele.html('<span class="icon red fa-thumbs-o-down"></span>');
  });
  ros.connect(protocol + '://' + host + ':' + port);
};

/**
 * Attempt to connect to the given MJPEG server. If a connection is made, a green thumbs up is placed in the HTML of the
 * element with the given ID. If a connection is not made, a red thumbs down is placed in the HTML of the element with
 * the given ID.
 *
 * @param host The host to connect to.
 * @param port The port to connect to.
 * @param id The ID of the element to place the icon in.
 */
RMS.verifyMjpegServer = function(host, port, id) {
  var ele = $('#' + id);
  var img = new Image();
  img.onerror = function(a) {
    ele.html('<span class="icon red fa-thumbs-o-down"></span>');
  }
  ele.html('<span class="icon green fa-thumbs-o-up"></span>');
  img.src = 'http://' + host + ':' + port + '/stream?topic=/';
};