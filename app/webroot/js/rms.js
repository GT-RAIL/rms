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
  ros.on('close', function() {
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

RMS.generateRosbridgeDiagnosticPanel = function(protocol, host, port, id) {
  var ros = new ROSLIB.Ros();
  var ele = $('#' + id);
  var topic = null;
  ros.on('connection', function() {
    var html = '<h2>rosbridge online <span class="icon green fa-thumbs-o-up"></span></h2>';
    html += '<div class="row">';
    html += '<section class="6u">';
    html += '<strong>Active Nodes<br /></strong>';
    html += '<textarea rows="8" cols="50" id="nodes" readonly style="resize: none;">Loading...</textarea>';
    html += '</section>';
    html += '<section class="6u">';
    html += '<strong>ROS Parameters<br /></strong>';
    html += '<select class="button" id="parameters">';
    html += '<option>Loading...</option>';
    html += '</select>';
    html += '<br />';
    html += '<strong>Value:</strong> <span id="param">N/A</span>';
    html += '<br /><br />';
    html += '<strong>ROS Services<br /></strong>';
    html += '<textarea rows="3" cols="50" id="services" readonly style="resize: none;">Loading...</textarea>';
    html += '</section>';
    html += '</div>';
    html += '<div class="row">';
    html += '<section class="12u">';
    html += '<strong>ROS Topics<br /></strong>';
    html += '<select class="button" id="topics">';
    html += '<option>Loading...</option>';
    html += '<br />';
    html += '<textarea rows="5" cols="50" id="echo" readonly style="resize: none;">Awaiting data...</textarea>';
    html += '</select>';
    html += '</section>';
    html += '</div>';
    ele.html(html);

    // bindings
    $('#parameters').change(function() {
      var selected = $('#parameters option:selected').text();
      var param = new ROSLIB.Param({
        ros : ros,
        name : selected
      });
      param.get(function(value) {
        if (value === null) {
          $('#param').html('N/A');
        } else {
          $('#param').html(value);
        }
      });
    });

    $('#topics').change(function() {
      // check for old topic
      if (topic !== null) {
        topic.unsubscribe();
      }
      var echoEle = $('#echo');
      echoEle.val('Awaiting data...');

      var selected = $('#topics option:selected').text();
      topic = new ROSLIB.Topic({
        ros : ros,
        name : selected
      });
      topic.subscribe(function(message) {
          var newLine = true;
          if (echoEle.val() === 'Awaiting data...') {
            echoEle.val('');
            newLine = false;
          }
          if (newLine) {
            echoEle.val(echoEle.val() + '\n');
          }
          echoEle.val(echoEle.val() + '> ' + JSON.stringify(message));
          echoEle.scrollTop(
            echoEle[0].scrollHeight - echoEle.height()
          );
      });
    });
  });
  ros.on('error', function() {
    ele.html('<h2>Connection failed! <span class="icon red fa-thumbs-o-down"></span></h2>');
  });
  ros.on('close', function() {
    ele.html('<h2>Connection lost! <span class="icon red fa-thumbs-o-down"></span></h2>');
  });
  ros.connect(protocol + '://' + host + ':' + port);

  // get the node list
  var getNodes = function() {
    ros.getNodes(function(nodes) {
      $('#nodes').val(nodes.toString().replace(/\,/g,'\n'));
    });
    // rosbridge gets annoyed if you slam it with service calls
    setTimeout(getParams, 100);
  };

  // get the parameters
  var getParams = function() {
    ros.getParams(function(params) {
      var topicEle = $('#parameters');
      topicEle.find('option').remove().end();
      topicEle.append($('<option/>', {
        value : null,
        text : 'Select to view...'
      }));
      params.forEach(function(param) {
        topicEle.append($('<option/>', {
          value : param,
          text : param
        }));
      });
    });
    setTimeout(getServices, 100);
  }

  // get the services
  var getServices = function() {
    ros.getServices(function(services) {
      $('#services').val(services.toString().replace(/\,/g,'\n'));
    });
    setTimeout(getTopics, 100);
  };

  // get the topics
  var getTopics = function() {
    ros.getTopics(function(topics) {
      var paramEle = $('#topics');
      paramEle.find('option').remove().end();
      paramEle.append($('<option/>', {
        value : null,
        text : 'Select to view...'
      }));
      topics.forEach(function(topic) {
        paramEle.append($('<option/>', {
          value : topic,
          text : topic
        }));
      });
    });
  };

  // calls in order
  getNodes();
};