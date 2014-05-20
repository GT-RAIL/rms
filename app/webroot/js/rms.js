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
    html += '</select>';
    html += '<br />';
    html += '<strong>Type:</strong> <span id="type">N/A</span>';
    html += '<br />';
    html += '<pre class="rostopic"><code id="rostopic">Awaiting data...</code></pre>';
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
      var echoEle = $('#rostopic');
      echoEle.html('Awaiting data...');

      var selected = $('#topics option:selected').text();
      topic = new ROSLIB.Topic({
        ros : ros,
        name : selected
      });
      topic.subscribe(function(message) {
          echoEle.html(RMS.prettyJson(message));
      });
      // grab the topic type
      ros.getTopicType(selected, function(type) {
        if (type === '') {
          $('#type').html('N/A');
        } else {
          $('#type').html(type);
        }
      });
    });
  });
  ros.on('error', function() {
    ele.html('<h2>Connection Failed <span class="icon red fa-thumbs-o-down"></span></h2>');
  });
  ros.on('close', function() {
    ele.html('<h2>Connection Lost <span class="icon red fa-thumbs-o-down"></span></h2>');
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

/**
 * Attempt to connect to the given MJPEG server. If a connection is made, a green thumbs up is placed in the HTML of the
 * element with the given ID. If a connection is not made, a red thumbs down is placed in the HTML of the element with
 * the given ID.
 *
 * @param host The host to connect to.
 * @param port The port to connect to.
 * @param id The ID of the element to place the icon in.
 */
RMS.generateStream = function(host, port, topic, id, options) {
  // parse the options
  options = options || {};
  var width = options.width || null;
  var height = options.height || null;
  var quality = options.quality || null;
  var invert = options.invert;

  // setup the image
  var ele = $('#' + id);
  var img = new Image();
  img.onerror = function(a) {
    ele.html('<h2>Stream Currently Unavailable <span class="icon red fa-thumbs-o-down"></span></h2>');
  }
  img.onload = function() {
    ele.html('');
    ele.append(img);
  }

  // setup the URL
  var url = 'http://' + host + ':' + port + '/stream?topic=' + topic;
  if (width) {
    url += '?width=' + width;
  }
  if (height) {
    url += '?height=' + height;
  }
  if (quality) {
    url += '?quality=' + quality;
  }
  if (invert) {
    url += '?invert=true';
  }
  img.src = url;
}

/**
 * Prettify the JSON object as formatted HTML.
 *
 * @param json The JSON object to format.
 * @return The formatted HTML.
 */
RMS.prettyJson = function(json) {
  // replacement function
  var replacer = function(match, pIndent, pKey, pVal, pEnd) {
    var key = '<span class=json-key>';
    var val = '<span class=json-value>';
    var str = '<span class=json-string>';
    var r = pIndent || '';
    if (pKey) {
      r = r + key + pKey.replace(/[": ]/g, '') + '</span>: ';
    }
    if (pVal) {
      r = r + (pVal[0] == '"' ? str : val) + pVal + '</span>';
    }
    return r + (pEnd || '');
  };

  // define what should be on a new line
  var jsonLine = /^( *)("[\w]+": )?("[^"]*"|[\w.+-]*)?([,[{])?$/mg;
  // return the HTML
  return html = JSON.stringify(json, null, 3)
    .replace(/&/g, '&amp;').replace(/\\"/g, '&quot;')
    .replace(/</g, '&lt;').replace(/>/g, '&gt;')
    .replace(jsonLine, replacer);
};
