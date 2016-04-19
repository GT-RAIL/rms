<?php
/**
 * Queue Chat Interface
 *
 * The Queue Chat Interface view. This interface will for testing queuing and chat.
 *
 * @author		Aaron St. Clair - astclair@gatech.edu
 * @copyright	2015 Georgia Institute of Technology
 * @link		https://github.com/WPI-RAIL/QueueChatInterface
 * @since		QueueChatInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
?>

<?php
// bootstrap
echo $this->Html->script('bootstrap.min');
echo $this->Html->css('bootstrap.min');

// jstree for collapsible tree view of HTN
echo $this->Html->script('jstree.min');
echo $this->Html->css('jstree-themes/default/style.min.css');

// vis.js for graph view of HTN 
echo $this->Html->script(array(
			'//cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.js'));
echo $this->Html->css(array(
			'//cdnjs.cloudflare.com/ajax/libs/vis/4.9.0/vis.min.css'));
echo $this->Html->css('TrainsInterface');
?>

<?php
// connect to ROS
echo $this->Rms->ros($environment['Rosbridge']['uri']);

//Init study information
echo $this->Rms->initStudy();

echo $this->Html->script(array(
			'//localhost/widgets/rosqueuejs/build/rosqueue.js'));

//$appointment = $environment['Condition'][0]['Slot'][0];
//$is_anonymous = $environment['Iface'][ $environment['Condition'][0]['iface_id'] ]['anonymous'];
?>

<!--
<pre>
<?php
//var_dump( $appointment );
?>
</pre>

<p>
User ID: <?php
echo $appointment['Appointment']['user_id'];
//echo $environment['Condition'][0]['Slot'][0]['Appointment']['user_id'];
?>
<br/>
Anonymous: <?php
//echo $is_anonymous;
?>
</p>
-->

<script>
	var size = Math.min(((window.innerWidth / 2) - 120), window.innerHeight * 0.60);

	var enabled = false;
	var rosQueue = new ROSQUEUE.Queue({
		ros: _ROS,
		studyTime: 10,
		chatEnabled: true,
		userId: <?php
			if (isset($appointment['Appointment']['user_id'])){
				echo $appointment['Appointment']['user_id'];
			}
			else {
				echo 0;
			}
		?>
	});

	/*
	 * notify user if I receive a now_active message
	 * This method is called once when you're first enabled
	 * for a method called continuously, use on 'enabled'
	 * When this is called, add all the control elements to the interface.
	 * This includes interactive markers, keyboard controls, and button controls
	 * @param message Int32 message, the id of the user to remove
	 */
	rosQueue.on('activate', function () {
		//slight pause helps with loading the webpage
		//setTimeout(tutorial, 1000);
	});

	/**
	 * update user wait time for active user
	 */
	rosQueue.on('enabled', function (message) {
		var d = new Date();
		d.setSeconds(message.sec);
		d.setMinutes(message.min);
		$('#queue-status').html('robot active!  Time Remaining ' + d.toLocaleTimeString().substring(3, 8));
	});

	/**
	 * when I receive a new time update the interface
	 * @param data objected with time in min & sec
	 */
	rosQueue.on('wait_time', function(data) {
		var d = new Date();
		d.setSeconds(data.sec);
		d.setMinutes(data.min);
		//substring removes hours and AM/PM
		$('#queue-status').html('Your waiting time is ' + d.toLocaleTimeString().substring(3, 8));
	});

	/*
	 * notify user if I receive a pop_front message
	 * @param message Int32 message, the id of the user to remove
	 */
	rosQueue.on('disabled', function () {
		enabled = false;
//		document.getElementById('segment').className = 'button fit';
//		document.getElementById('ready').className = 'button fit';
//		document.getElementById('retract').className = 'button fit';
	});

	/**
	 * when the user is dequeued, send them back to their account
	 */
	rosQueue.on('dequeue', function () {
		location.reload();
	});

	/**
	 * when I exit the webpage, kick me out
	 */
	window.onbeforeunload = function () {
		rosQueue.dequeue();
		return undefined;
	};

	/**
	 * Add me when I first visit the site
	 */
	rosQueue.enqueue();

	function addQueueStatus(){
		$('#queue-status').html('robot active!');
	}
</script>

<script>
	var actions_srv = new ROSLIB.Service({
		ros : _ROS,
		name : '/WebInterface4ActionsService',
		serviceType : 'heres_how_msgs/WebInterface4Actions'
	});
    var actions_req = new ROSLIB.ServiceRequest({
        Request :   'sendActions'
    });

    var actions = null;
    function update_actions() {
        actions_srv.callService(actions_req, function(result) {
            console.log('Result for actions_srv call on '
                    + actions_srv.name);
            for(var i = 0; i < result.Actions.length; i++) {
                console.log('Action '
                    + result.Actions[i].ActionType);
            }
        });

    }
</script>

<style>
.chat-container {
    width: 400px;
}
.chat-display {
    height: 100px;
    background: white;
    resize: both;
    overflow: auto;
    padding: 0;
    margin: 0;
}
.chat-from-self, .chat-from-other {
    padding: 5px;
    margin: 5px;
}
.chat-from-self {
    color: black;
    float: left;
    border-radius: 5px;
    background: #8AC007;
    clear:   both;
}
.chat-from-other {
    color: black;
    float: right;
    text-align: left;
    border-radius: 5px;
    background: skyblue;
    clear:   both;
}
.chat-text-input {
    float: left;
    width: 400px;
    background: white;
    border-top: 1px solid grey;
    padding: 0px;
    margin: 0px;
}
.chat-btn {
    float: right;
    width: 50px;
    padding: 0;
    margin: 0;
    height: 34px;
    line-height:normal !important;
}
</style>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2>TRAINS Interface</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">

        <div id="htn-task-container" class="col-lg-8">
            <form id="htn-task-frm" name="htn-task-frm" class="form-horizontal">
                <div class="form-group" id="teach-div">
                    <h3>Teach</h3> 
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" class="form-control" id="htn-new-task-text" placeholder="New Task">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" id="htn-new-task-btn" type="button">Add</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-sm-2">
                            <p>or</p>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <select id="htn-action-select" name="htn-action-select" class="form-control">
                                  <option disabled selected>An existing task</option>
                                  <option>Task 1</option>
                                  <option>Task 2</option>
                                </select>
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button">Go</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>


                <div class="form-group alert alert-info" id="current-task-div">
                        <label class="col-sm-3 control-label" style="padding-top: 7px;">Currently editing:</label>
                        <div class="col-sm-9" style="text-align: left">
                          <p class="form-control-static" id="current-task"></p>
                        </div>
                </div>

                <div class="form-group" id="teach-task-div">
                    <div class="row">
                        <div class="col-sm-6">
                            <button id="htn-add-step-btn" type="button" class="btn btn-primary">Add a Step</button>
                        </div>
                        <div class="col-sm-6">
                            <button id="htn-add-step-done-btn" type="button" class="btn btn-success">Done</button>
                        </div>
                    </div>
                </div>


                <div class="form-group" id="add-step-div">
                    <h3>Add Steps</h3> 
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="input-group">
                                <input type="text" id="htn-add-new-step-text" class="form-control" placeholder="New step">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" id="htn-add-new-step-btn" type="button">Add</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                        <div class="col-sm-2">
                            <p>or</p>
                        </div>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <select id="htn-add-existing-step-select" name="htn-action-select" class="form-control">
                                  <option disabled selected>An existing task</option>
                                  <option>Task 1</option>
                                  <option>Task 2</option>
                                </select>
                                <span class="input-group-btn">
                                    <button id="htn-add-existing-step-btn" class="btn btn-primary" type="button">Go</button>
                                </span>
                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>


                <div class="form-group" id="cancel-div">
                    <span>
                        <button id="htn-cancel-btn" type="button" class="btn btn-warning">Cancel</button>
                    </span>
                </div>


                <div class="row">
                    <div class="col-lg-12">

                        <div id="jstree_div">
                            <ul>
                            <li data-jstree='{"opened":true,"selected":true}'>RotateTires
                              <ul>
                                <li data-jstree='{"icon":"glyphicon glyphicon-file"}'>Child</li>
                                <li data-jstree='{"icon":"glyphicon glyphicon-file"}'>Child</li>
                                <li data-jstree='{"icon":"glyphicon glyphicon-file"}'>Child</li>
                              </ul>
                            </li>
                            </ul>
                        </div>

                    </div>
                </div>


            </form>
        </div>
        <div id="htn-task-container" class="col-lg-4">
            <div id="mynetwork"></div>
        </div>


        <div class="row">
            <div class="col-lg-8">
            </div>
            <div class="col-lg-4">

                <div id="chat-container" class="chat-container">
                    <h4 class="chat-title">Chat</h4>
                    <div id="chat-display" class="chat-display"> 
                    </div>
                    <span>
                        <textarea id="chat-text-input" class="chat-text-input" placeholder="Begin typing to chat..."  ></textarea>
                    </span>
                </div>

            </div>
        </div>

        <script type="text/javascript">
            $(function () { $('#jstree_div').jstree(); });
        </script>

        <script>
// teach-div: The teach button with a text box for new tasks and a dropdown for existing
// current-task-div: A label and paragraph displaying the current task being edited
// teach-task-div: Add a Step/Done buttons
// add-step-div:  Add steps with text box for new step and dropdown for existing actions
// cancel-div: A cancel button

            $("#teach-div").show();
            $("#current-task-div").hide();
            $("#teach-task-div").hide();
            $("#add-step-div").hide();
            $("#cancel-div").hide();

            $('#htn-new-task-btn').click(function() {
                // alert("Adding new task "+$("#htn-new-task-text").val());
                $("#current-task").html($("#htn-new-task-text").val());
                $("#teach-div").hide();
                $("#current-task-div").show();
                $("#teach-task-div").show();
                $("#htn-new-task-text").val("");
            });

            $('#htn-add-step-btn').click(function() {
                $("#teach-div").hide();
                $("#current-task-div").show();
                $("#teach-task-div").show();
                $("#add-step-div").show();
            });

            $('#htn-add-step-done-btn').click(function() {
                $("#teach-div").show();
                $("#current-task-div").hide();
                $("#teach-task-div").hide();
                $("#add-step-div").hide();
            });
        </script>


        <script type="text/javascript">
          // create an array with nodes
          var nodes = new vis.DataSet([
            {id: 1, label: 'RotateTires'},
            {id: 2, label: 'RemoveTires'},
            {id: 3, label: 'Rotate'},
            {id: 4, label: 'ScrewHubs'}
          ]);

          // create an array with edges
          var edges = new vis.DataSet([
            {from: 1, to: 2},
            {from: 1, to: 3},
            {from: 1, to: 4}
          ]);

          // create a network
          var container = document.getElementById('mynetwork');
          var data = {
            nodes: nodes,
            edges: edges
          };
          var options = {
              interaction: {
                  dragNodes: false,
                  dragView: false,
                  zoomView: false
              },
              layout: {
                  hierarchical: {
                      direction: "UD"
                  }
              }
          };
          var network = new vis.Network(container, data, options);
        </script>

        <script>
            // Detect resizing of the text box or display div and resize the other to 
            // matching width

            var chat_resizable_elements = $('#chat-text-input, #chat-display');
    
            // set init (default) state   
            chat_resizable_elements.data('x', chat_resizable_elements.width());
            chat_resizable_elements.data('y', chat_resizable_elements.height()); 

            // Bind to mouseup and check for resize
            chat_resizable_elements.mouseup(function(){
                if (  $(this).width()  != $(this).data('x') 
                    || $(this).height() != $(this).data('y') )
                {
                    chat_resizable_elements.not(this).width( $(this).width() );
                }
                // set new height/width
                $(this).data('x', $(this).width());
                $(this).data('y', $(this).height());
            });


            $('#chat-text-input').keyup(function(event) {
                if(event.keyCode == 13) {
                    rosQueue.sendChat($('#chat-text-input').val());
                    $('#chat-text-input').val('');
                }
            });

            $('#chat-send').click(function() {
                rosQueue.sendChat($('#chat-text-input').val());
                $('#chat-text-input').val('');
            });
        </script>
	</div>
</section>
