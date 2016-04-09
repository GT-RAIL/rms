<?php
/**
 * Trains Interface
 *
 * The Trains Interface view. This interface will for testing queuing and chat.
 *
 * @author		Aaron St. Clair - astclair@gatech.edu
 * @copyright	2015 Georgia Institute of Technology
 * @link		https://github.com/WPI-RAIL/QueueChatInterface
 * @since		TrainsInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
?>

<?php
// bootstrap
echo $this->Html->script('bootstrap.min');
echo $this->Html->css('bootstrap.min');
echo $this->Html->css('CrowdManipulationInterface');

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
echo $this->Html->script('mjpegcanvas.js');
// connect to ROS
echo $this->Rms->ros($environment['Rosbridge']['uri']);

//Init study information
echo $this->Rms->initStudy();

echo $this->Html->script(array(
			'//localhost/widgets/rosqueuejs/build/rosqueue.js'));

echo $this->Rms->tf(
    $environment['Tf']['frame'],
    $environment['Tf']['angular'],
    $environment['Tf']['translational'],
    $environment['Tf']['rate']
);
//$appointment = $environment['Condition'][0]['Slot'][0];
//$is_anonymous = $environment['Iface'][ $environment['Condition'][0]['iface_id'] ]['anonymous'];
?>

<script>
$(function() {
	var size = Math.min(((window.innerWidth / 2) - 120), window.innerHeight * 0.60);
	<?php
		$streamTopics = '[';
		$streamNames = '[';
		foreach ($environment['Stream'] as $stream) {
			$streamTopics .= "'" . $stream['topic'] . "', ";
			$streamNames .= "'" . $stream['name'] . "', ";
		}
		// remove the final comma
		$streamTopics = substr($streamTopics, 0, strlen($streamTopics) - 2);
		$streamNames = substr($streamNames, 0, strlen($streamNames) - 2);
		$streamTopics .= ']';
		$streamNames .= ']';
	?>

    
    var mjpegcanvas=new MJPEGCANVAS.MultiStreamViewer({
		divID: 'mjpeg',
		host: '<?php echo $environment['Mjpeg']['host']; ?>',
		port: <?php echo $environment['Mjpeg']['port']; ?>,
		width: size,
		height: size * 0.85,
		quality: "<?php echo $environment['Stream']?(($environment['Stream'][0]['quality']) ? $environment['Stream'][0]['quality'] : '90'):''; ?>",
		topics: "<?php echo $streamTopics; ?>",
		labels: "<?php echo $streamNames; ?>",
        tfObject:_TF,
        tf:'arm_mount_plate_link'
	});
    //add a set of interactive markers
    mjpegcanvas.addTopic('/tablebot_interactive_manipulation/update_full','visualization_msgs/InteractiveMarkerInit')

    //call the roslib service to update the positions of the objects on the canvas
    var segmentation_service= new ROSLIB.Service({
                ros : _ROS,
                name : '/rail_segmentation/segment',
                serviceType : 'std_srvs/Empty'
    });
    var segmentation_interval =5000;
    setInterval(function(){
        mjpegcanvas.updateOverlay=false;
        segmentation_service.callService({},function(val){
            mjpegcanvas.updateOverlay=true;
        })
    },20000);

    // Feedback div
	var safety_feedback = new ROSLIB.Topic({
		ros: _ROS,
		name: 'carl_safety/error',
		messageType: 'carl_safety/Error'
	});
	safety_feedback.subscribe(function (message){
		showFeedback(message.severity,message.resolved, message.message);
	});

	var nav_feedback = new ROSLIB.Topic({
		ros: _ROS,
		name: 'create_parking_spots/status',
		messageType: 'carl_safety/Error'
	});
	nav_feedback.subscribe(function (message){
		showFeedback(message.severity,message.resolved,message.message);
	});

	var pickup_feedback = new ROSLIB.Topic({
		ros: _ROS,
		name: '/carl_moveit_wrapper/common_actions/pickup/feedback',
		messageType: 'carl_moveit/PickupActionFeedback'
	});
	pickup_feedback.subscribe(function (message){
		showFeedback(0,false,message.feedback.message);
	});

    $.ajax({
        type: "POST",
        url: '/Analytics/add',
        data: {
            'Analytics':{
                'os':navigator.oscpu,
                'browser':navigator.userAgent,
                'screen_size':$(window).width()+"x"+$(window).height(),
                'user_id':"<?php echo $userId?>"
            }
        },
        success: function(){
            console.log('as')
        },
    });


	/**
	 * display feedback to the user. Feedback has a string to display and a severity level (0-3).
	 * 0 - debug. will be displayed under the interface in smaller test
	 * 2 - error. will be overlayed on the interface
	 * 3 - fatal. will be overlayed on the interface in red
	 */

	function showFeedback(severity,resolved,message) {
		var feedback = document.getElementById('feedback');
		var feedbackOverlay = document.getElementById('important-feedback');
		var fatalFeedbackOverlay = document.getElementById('fatal-feedback');

		switch (severity) {
			case 2:
				if (resolved) {
					fatalFeedbackOverlay.className = 'feedback-overlay fatal hidden';
					feedbackOverlay.className = 'feedback-overlay hidden';
				}
				else {
					fatalFeedbackOverlay.className = 'feedback-overlay fatal';
					fatalFeedbackOverlay.innerHTML = message;
				}
				break;

			case 1:
				if (resolved) {
					feedbackOverlay.className = 'feedback-overlay hidden';
				}
				else {
					feedbackOverlay.className = 'feedback-overlay';
					feedbackOverlay.innerHTML = message;
				}
				break;

			case 0:
				feedback.innerHTML += message;
				feedback.innerHTML += '<br/><br/>';
				//this will keep the div scrolled to the bottom
				feedback.scrollTop = feedback.scrollHeight;
		}

	}

	$('#clearFeedback').click(function () {
		document.getElementById('feedback').innerHTML = '';
	});


});
</script>

<script>
	var enabled = true;
	var rosQueue = new ROSQUEUE.Queue({
		ros: _ROS,
		studyTime: 10,
		chatEnabled: true,
		userId: "<?php
			if (isset($appointment['Appointment']['user_id'])){
				echo $appointment['Appointment']['user_id'];
			}

            else if(isset($userId)){
                echo $userId;
            }
			else if(isset($_GET['userid'])){
                echo $_GET['userid'];
			}
           else{
                echo '';
            }
		?>"
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
        if(rosQueue.userId!=''){

        }

        $('.wrapper').show();
        $('.queue').hide();
        //$('.wrapper').show()
	});

	/**
	 * update user wait time for active user
	 */
	rosQueue.on('enabled', function (message) {
		var d = new Date();
		d.setSeconds(message.sec);
		d.setMinutes(message.min);
        
		$('#queue-status').html('Time Remaining ' + d.toLocaleTimeString().substring(3, 8));
        
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
        $('.wrapper').hide();
        $('.queue').show();
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
    rosQueue.updateQueueClient.advertise();

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

<!-- chat css - TODO: move to widget -->
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
<!--
	<h2>TRAINS Interface</h2>
-->
</header>

<!--hidden section at first-->
<section class=" style4 container queue" style="display: none">
    <h3>Hi, Welcome to the GT RAIL lab experiment</h3>
    <p>You are in the queue for the experiment</p>
    <div id='queue-status' ></div>
    <div id='chat-display' ></div>
</section>
<section class="wrapper style4 container">

        <!-- left side has form controls for task specification -->
        <div class="col-lg-6">
            <h3>Teach a new task</h3> 
            <form id="htn-task-frm" name="htn-task-frm" class="form-horizontal">
                <div class="alert alert-info">
                    <div class="form-group" id="teach-task-name-div">
                        <label class="col-sm-3 control-label" for="htn-action-select">Task Name</label>
                        <div class="col-sm-6">
                            <input type="text" id="htn-task-name" name="htn-task-name" class="form-control" placeholder="PackLunch" value="PackLunch"/>
                        </div>
                    </div>

                    <div class="form-group" id="teach-task-div">
                        <label class="col-sm-3 control-label" for="htn-action-select"></label>
                        <div class="col-sm-3">
                            <button id="htn-teach-task" name="htn-teach-task" class="btn btn-success">Teach</button>
                        </div>
                        <div class="col-sm-3">
                            <button id="htn-task-complete" name="htn-task-complete" class="btn btn-danger">Task Complete</button>
                        </div>
                    </div>

                </div>

                <div class="alert alert-info" id="htn-action-select-div">
                    <div class="row">
                        <p>Select an action to add to execute and add to the current task:</p>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="htn-action-select">Built-in Actions</label>
                        <div class="col-sm-6">
                            <select id="htn-action-select" name="htn-action-select" class="form-control">
                                <option disabled selected>Select an action...</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="htn-learned-action-select">Learned Actions</label>
                        <div class="col-sm-6">
                            <select id="htn-learned-action-select" name="htn-learned-action-select" class="form-control">
                                <option disabled selected>Select an action to execute</option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="alert alert-info" id="current-task-div">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label for="current-task" class="col-sm-3 control-label" style="padding-top: 7px;">Current Action:</label>
                            <div class="col-sm-9" style="text-align: left">
                              <p class="form-control-static" id="current-task"></p>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <label class="col-sm-3 control-label" style="padding-top: 7px;">Select input(s):</label>
                            <div class="col-sm-6">
                                <div id="htn-input-select-div">
                                </div>
                                <button id="htn-execute-btn" class="btn btn-primary" type="button">Execute</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="teach-subtask-div">
                        <div class="row">
                            <div class="col-sm-6">
                                <button id="htn-add-step-btn" type="button" class="btn btn-primary">Add a Step</button>
                            </div>
                            <div class="col-sm-6">
                                <button id="htn-add-step-done-btn" type="button" class="btn btn-success">Done</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group" id="cancel-div">
                    <span>
                        <button id="htn-cancel-btn" type="button" class="btn btn-warning">Cancel</button>
                    </span>
                </div>


                <div class="row text-left">
                    <h4>Task Tree</h4>
                    <div class="col-lg-12">
                        <div id="jstree_loading_div" class="alert alert-info">Loading...</div>
                    	<div id="jstree_div"></div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-6">
            <div id="mjpeg">
            </div>

            <div class="col-lg-4">
<!--
                <div id="chat-container" class="chat-container">
                    <h4 class="chat-title">Chat</h4>
                    <div id="chat-display" class="chat-display"> 
                    </div>
                    <span>
                        <textarea id="chat-text-input" class="chat-text-input" placeholder="Begin typing to chat..."  ></textarea>
                    </span>
                </div>
-->

            </div>

        </div>

        <div class="row">
            <h4>System Status</h4>
            <div id="feedback" class="col-lg-12">
		Ready
            </div>
        </div>
         <div class='row'>
            <div class='col-lg-12'>           
            <button type="button" class="btn btn-primary btn-large button special" id="finish-task-btn">Finish Task</button>
            </div>  
        </div>


        <div id="modal-test" class="row">
            <!-- Modal -->
            <div class="modal fade" id="question-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <p class="modal-title" id="myModalLabel">Question</p>
                  </div>
                  <div class="modal-body">
                        <div id="question-text"></div>
                  </div>
                  <div class="modal-footer" id="question-answer-div">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
              </div>
            </div>
        </div>

        <!-- ROS service calls -->
        <script>
            var inputs_srv = new ROSLIB.Service({
                ros : _ROS,
                name : '/web_interface/action_inputs',
                serviceType : 'heres_how_msgs/WebInterfaceActionInputs'
            });
            var actions_srv = new ROSLIB.Service({
                ros : _ROS,
                name : '/web_interface/actions',
                serviceType : 'heres_how_msgs/WebInterfaceActions'
            });
//			var htn_srv = new ROSLIB.Service({
//				ros : _ROS,
//				name : '/web_interface/htn',
//				serviceType : 'heres_how_msgs/WebInterfaceHTN'
//			});
            //
            var button_topic = new ROSLIB.Topic({
                ros: _ROS,
                name: '/web_interface/button',
                messageType: 'heres_how_msgs/WebInterfaceButton'
            });
            button_topic.advertise();

            var execute_topic = new ROSLIB.Topic({
                ros: _ROS,
                name: '/web_interface/execute_action',
                messageType: 'heres_how_msgs/WebInterfaceExecuteAction'
            });
            execute_topic.advertise();

            // Track whether the modal is open or closed
            var response_modal_open = false;

            // Copy of the current question
            var current_question = '';

            // Topic for receiving questions from heres-how
			var question_topic = new ROSLIB.Topic({
				ros : _ROS,
				name : '/web_interface/question',
				serviceType : 'heres_how_msgs/WebInterfaceQuestion'
			});

            question_topic.subscribe( function (message) {
                // if the modal is open, wait for the closed event before 
                // updating the contents and reopening
                if ( response_modal_open ) {
                    $("#question-modal").one('hidden.bs.modal', function() {
                        updateQuestionModal(message);
                    });
                } else {
                    updateQuestionModal(message);
                }
            });




            // Takes a WebInterfaceQuestion message and updates the
            // modal div to have the correct question text and buttons
            // then shows the modal
            function updateQuestionModal(message) {
                $("#question-answer-div").empty();
                $("#question-text").empty();
                current_question = '';
                $("#question-text").text(message.question);
                current_question = message.question;

                // Check for an AskForTaskName question and add a textbox for task name 
                if(message.answers.length > 0 && message.answers[0] == "AskForTaskName") {
                    $("#question-text").append('<div class="form-group">');
                    $("#question-text").append('<label class="col-sm-3 control-label" for="question-task-name">Task Name</label>');
                    $("#question-text").append('<input type="text" id="question-task-name" name="question-task-name" class="form-control" />');
                    $("#question-text").append('</div>');
                    $("#question-answer-div").append('<button type="button" class="btn btn-success" data-dismiss="modal">Rename</button>');
                    $("#question-answer-div").append('<button type="button" class="btn btn-warning" data-dismiss="modal">No Change</button>');
                    
                } else { // Otherwise, it's a multiple choice question 
                    // add buttons
                    for(var i = 0; i < message.answers.length; ++i) {
                        var answer = message.answers[i];
                        var button_color = 'btn-primary';

                        // color button for yes/no differently
                        if ( answer == 'Yes' ) {
                            button_color = 'btn-success';
                        } else if( answer == 'No' ) {
                            button_color = 'btn-warning';
                        }

                        $("#question-answer-div").append('<button type="button" class="btn ' + button_color + '" data-dismiss="modal">' + answer + '</button>');
                    }
                }

                // show modal
                $("#question-modal").modal({backdrop: 'static', show: true});
                response_modal_open = true;
            }

            // Set the modal open flag to false when hide is completed
            $("#question-modal").on('hidden.bs.modal', function() {
                response_modal_open = false;
            });


            // Topic for publishing responses to questions
            var response_topic = new ROSLIB.Topic({
                ros: _ROS,
                name: '/web_interface/question_response',
                messageType: 'heres_how_msgs/WebInterfaceQuestionResponse'
            });
            response_topic.advertise();

            // Bind to click event of all response buttons in modal dialog
            // and send response message back to heres-how
            $(document.body).on("click", "#question-answer-div button", function(event) {
                var question = current_question; 
                var response = '';

                // Check for the rename question
                if( $("#question-task-name").length > 0 ) {
                    // If "No Change" was clicked send a blank response 
                    // otherwise send the name entered in the text box
                    if( $(this).text() == "No Change" ) {
                        response = "";
                    } else {
                        response = $("#question-task-name").val();
                    }
                } else {
                    response = $(this).text();
                }
                var response_msg = new ROSLIB.Message({
                    question: question,
                    answer: response
                });
                response_topic.publish(response_msg);
            });


			var htn_topic = new ROSLIB.Topic({
				ros : _ROS,
				name : '/web_interface/htn',
				serviceType : 'std_msgs/String'
			});
            htn_topic.subscribe( function(message) {
                htn_string = message.data.replace(/'/g, '"');
                htn_tree = JSON.parse(htn_string);
                jstree_data = populate_jstree(htn_tree);
                $("#jstree_loading_div").hide();
                $("#jstree_div").show();

                // Send the htn string over to the PHP server for storage
                $.post('/Storage/store_htn', { htn: htn_string, user_id: <?php echo $userId == null ? 0 : $userId;?> }).fail(console.error);
            });

            var actions = new Array();
            // 
            function get_primitive_actions(update_func) {
                return get_actions("primitive", update_func);
            }
            function get_learned_actions(update_func) {
                return get_actions("learned", update_func);
            }
            function get_actions(action_type, update_func) {
                var actions_req = new ROSLIB.ServiceRequest({
                    Request :   action_type
                });
                actions_srv.callService(actions_req, update_func);
            }

            var inputs = new Array();
            function get_parameters(action_name) {
                var req = new ROSLIB.ServiceRequest({
                    action :   action_name
                });
                inputs_srv.callService(req, function(result) {
                    var input_num = 0;
                    for(var i = 0; i < result.inputs.length; i++) {
                        var objs = result.inputs[i].objects; 
                        // Add a new <select> element to the htn-input-select div to 
                        // allow for selecting objects of each input type
                        var newElement = '<div class="form-group"><label for="htn-input-select' + input_num + '" class="col-sm-2 control-label">' + result.inputs[i].type + '</label><select id="htn-input-select1" name="htn-input-select' + input_num + '" class="form-control">';
                        for(var j = 0; j < objs.length; j++) {
                            newElement += "<option>"+objs[j]+"</option>";
                        }
                        newElement += '</select>';
                        $("#htn-input-select-div").append(newElement);
                        input_num += 1;
                    }
                    $("#current-task").html(action_name);
                    $("#current-task-div").show();
                });
            }


//            var htn_tree = new Array(); // Global store of the HTN
//            function get_htn(post_fetch_func) {
//            	var req = new ROSLIB.ServiceRequest({
//            		Request: 'sendHTN'
//            	});
//				htn_srv.callService(req, function(result) {
//					htn_tree = JSON.parse(result.HTN.replace(/'/g, '"'));
//					post_fetch_func(htn_tree);
//				});
//			}
        </script>

        <!-- Make a tree view -->
        <script type="text/javascript">
			function create_jstree_data(htn) {
				var data = htn.map(function(node) {
					var treeNode = {
						text: node.name + ((!!node.defined) ? "" : "?"),
						icon: "glyphicon glyphicon-file",
						state: {
							selected: !!node.focus,
							opened: !!node.focus || !node.defined
						}
					}
					if (!!node.decompositions && node.decompositions.length > 0) {
						treeNode.children = create_jstree_data(node.decompositions[0].steps)
					}
					return treeNode;
				});

				return data;
			}

			// Populate the jstree nodes with the incoming data
			function populate_jstree(htn) {
				var data = create_jstree_data(htn);
                $("#jstree_div").jstree('destroy');
				$("#jstree_div").jstree({
					core: {
						data: data
					}
				});
				return data;
			}
        </script>

        <script>
        $(function(){

// htn-action-select-div: div with a select element with primitive and learned actions
// current-task-div: A label and paragraph displaying the current task being edited
// teach-task-div: Add a Step/Done buttons
// cancel-div: A cancel button

            function get_inputs_for_action(action_name) {
                // get inputs for the 
                var current_action = actions.filter(function(obj) {
                    return obj.action === action_name;
                });
                if( current_action.length > 0 ) {
                    return current_action[0].inputs;
                } else {
                    return new Array();
                }
            }

            // an array of names of taught tasks so we can easily scan for duplicates
            // without a ROS service call
            var learned_task_names = [];

            function update_actions() {
                $("#htn-action-select").empty();
                $("#htn-action-select").append('<option disabled selected>Select a built-in action:</option>');

                // Get a list of primitive actions and add options to action select
                get_primitive_actions(function(result) {
                    for(var i = 0; i < result.Actions.length; i++) {
                        $("#htn-action-select").append('<option>'+result.Actions[i].ActionType+'</option>');
                        actions.push({ 
                            action: result.Actions[i].ActionType, 
                                inputs: result.Actions[i].Inputs
                        });
                    }
                    return actions;
                });
            }

            function update_learned_actions() {
                $("#htn-learned-action-select").empty();
                $("#htn-learned-action-select").append('<option disabled selected>Select a learned action:</option>');

                // Get a list of learned actions and add options to action select
                get_learned_actions(function(result) {
                    // Remove the current task from the list so that
                    // it can't be used until after it's defined
                    var learned_tasks = [];
                    if( result.Actions != null && result.Actions.length > 0 ) {
                        for(var i = 0; i < result.Actions.length; i++) {
                            console.log("Current task: " + $("#htn-task-name").val());
                            console.log("Array item: " + result.Actions[i].ActionType);
                            if( result.Actions[i].ActionType != $("#htn-task-name").val() ) {
                                learned_tasks.push( result.Actions[i] );
                            }
                        }
                    }

                    if ( learned_tasks != null && learned_tasks.length > 0 ) {
                        for(var i = 0; i < learned_tasks.length; i++) {
                            $("#htn-learned-action-select").append('<option>'+learned_tasks[i].ActionType+'</option>');
                            actions.push({ 
                                action: learned_tasks[i].ActionType, 
                                inputs: learned_tasks[i].Inputs
                            });
                        }
                    }
                    return actions;
                });
            }


            // Initial setup
            var selected_action_name = "";

            // Load action lists
            update_actions();
            update_learned_actions();

            // Load HTN tree view
            update_htn();

            $("#htn-action-select-div").hide();
            $("#current-task-div").hide();
            $("#teach-subtask-div").hide();
            $("#cancel-div").hide();
            $("#jstree_div").hide();
            $("#htn-task-complete").hide();


            //
            // Handle interface events
            // 

            // Teach New Task button: publishes button msg with task name from text box
            $("#htn-teach-task").click(function(event) {
                event.preventDefault();

                // No blank names
                if( $("#htn-task-name").val() == "" ) {
                    alert("Please enter a name for the task you are going to teach.");
                    $("#htn-task-name").focus();
                    return;
                }

                // No duplicate names
                if( $.inArray( $("#htn-task-name").val(), learned_task_names) !== -1 ) {
                    alert("You have already taught a task with this name. Please pick a different name for this task.");
                    $("#htn-task-name").val("");
                    $("#htn-task-name").focus();
                    return;
                }

                // Send teachNewTask message to heres-how
                var task_name = $("#htn-task-name").val();
                var button_msg = new ROSLIB.Message({
                    button: "teachNewTask",
                    parameters: [task_name]
                });
                button_topic.publish(button_msg);

                // Show action selection div and disable editing name
                $("#htn-action-select-div").show();
                $("#htn-task-name").prop('disabled', true);
                $("#htn-teach-task").hide();
                $("#htn-task-complete").show();
            });

            // Task complete button: publishes button msg
            $("#htn-task-complete").click(function(event) {
                event.preventDefault();

                // add the new task name to the list of learned tasks
                learned_task_names.push( $("#htn-task-name").val() );

                // reset the top-level task name text box
                $("#htn-task-name").val("");

                // Send a message to heres-how
                var button_msg = new ROSLIB.Message({
                    button: "taskComplete"
                });
                button_topic.publish(button_msg);

                // Hide lower-level divs 
                $("#htn-action-select-div").hide();
                $("#htn-task-name").prop('disabled', false);
                $("#htn-teach-task").show();
                $("#htn-task-complete").hide();

                //update the action list and reselect the "select an action..." option
                update_actions();
                update_learned_actions();
            });

            //Finish Task Button. Resets the Java Interface
            $("#finish-task-btn").click(function(event){
                event.preventDefault();
                var button_msg = new ROSLIB.Message({
                    button: "finishTask"
                });
                button_topic.publish(button_msg);

                //TODO: Something happens on Finish
                location.reload(); 
            })

            // Execute button: publishes execute action msg with action name and array of inputs
            $("#htn-execute-btn").click(function(event) {
                event.preventDefault();
                //alert('action_name: '+selected_action_name);

                // hide input selection during execution
                $("#current-task-div").hide();

                // Get all the inputs from select elements in htn-input-select-div
                // and build an array of selected values
                var inputs = $("#htn-input-select-div").children().map(function() {
                    return $(this).find(":selected").text();
                }).get();
                var msg = new ROSLIB.Message({
                    action: selected_action_name,
                    inputs: inputs
                });
                execute_topic.publish(msg);
                //update the action list and reselect the "select an action..." option
                update_actions();
                update_learned_actions();
            });

            // Action selected from dropdown
            // Refresh inputs and show input selection div
            $("#htn-action-select,#htn-learned-action-select").on("change", function() {
                selected_action_name = $("option:selected", this).html();
                var selected_action_inputs = get_inputs_for_action(selected_action_name);

                $("#htn-input-select-div").empty();
                inputs = get_parameters(selected_action_name);
            });

            // Fetch the HTN if a button has been clicked, or an action/param has been selected.
            var jstree_data = null;

            function update_htn() {
                var button_msg = new ROSLIB.Message({
                    button: "updateHTN"
                });
                button_topic.publish(button_msg);
            }

            // Load tree on page load
            $(document).ready(function() {
                // This has a wait due to an issue with page refresh
                // unregistering and publish bug 
                // TODO: verify problem and find a better solution
                // https://github.com/RobotWebTools/rosbridge_suite/issues/138
//                window.setTimeout(update_htn, 2000);
            });


            // Reload tree after btn or selection changes
//            $(".btn, #htn-action-select, #htn-input-select").click(function() {
//            	get_htn(function(htn) {	// Anonymous function to generate the visuals from the HTN
//            		jstree_data = populate_jstree(htn);
//            	});
//            });
        });
        </script>

        <!-- Queue and Chat -->
        <script>

            var chat_topic = new ROSLIB.Topic({
                ros: _ROS,
                name: '/web_interface/chat',
                messageType: 'std_msgs/String'
            });
            chat_topic.advertise();

            chat_topic.subscribe(function (message) {
                $('#chat-display').append('<br>'+message.data);
                $("#chat-display").scrollTop($("#chat-display")[0].scrollHeight);
            });
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
                event.preventDefault();
                if(event.keyCode == 13) {
                    //rosQueue.sendChat($('#chat-text-input').val());

                    chat_topic.publish(new ROSLIB.Message({data:$('#chat-text-input').val()}));
                    $('#chat-text-input').val('');
                }
            });

            $('#chat-send').click(function() {
                //rosQueue.sendChat($('#chat-text-input').val());
                chat_topic.publish(new ROSLIB.Message({data:$('#chat-text-input').val()}));
                $('#chat-text-input').val('');
            });
        </script>
	</div>
</section>
