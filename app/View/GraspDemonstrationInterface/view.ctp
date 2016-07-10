<?php
/**
 * Grasp Demonstration Interface Controller
 *
 * The Grasp Demonstration Interface controller. This interface will allow for navigation and manipulation controls.
 *
 * @author		Russell Toris - rctoris@wpi.edu, Peter Mitrano - robotwizard@wpi.edu, David Kent - davidkent@wpi.edu
 * @copyright	2015 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/GraspDemonstrationInterface
 * @since		GraspDemonstrationInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
?>

<?php
//custom styling
echo $this->Html->css('GraspDemonstrationInterface');
?>

<?php
// connect to ROS
echo $this->Rms->ros($environment['Rosbridge']['uri']);

// setup the TF client
echo $this->Rms->tf(
	$environment['Tf']['frame'],
	$environment['Tf']['angular'],
	$environment['Tf']['translational'],
	$environment['Tf']['rate']
);
?>

<section class='wrapper style4'>
	<div class='content center'>
		<div class='row' id='main-content'>
			<div id='important-feedback' class='feedback-overlay hidden'>
				<h1>ERROR: ...</h1>
			</div>
			<div id='fatal-feedback' class='feedback-overlay fatal hidden'>
				<h1>FATAL ERROR: ...</h1>
			</div>
			<div class='6u'>
				<div id='viewer'>
				</div>
			</div>
			<div class='6u stream'>
				<div id='mjpeg'>
				</div>
			</div>
		</div>
		<div class='row'>
			<section class='4u'>
				<a href='#' class='button fit' id='segment'>Detect Objects</a>
				<br/>
				<a href='#' class='button fit' id='ready'>Ready Arm</a>
				<br/>
				<a href='#' class='button fit' id='grasp'>Save Grasp</a>
			</section>
			<section class='4u'>
				<strong>Demonstrate grasps for the Pear</strong>: Make sure the <strong>Pear</strong> is
				<strong>Detected</strong>, <strong>Ready</strong> the arm, move the hand into a grasping position, and
				press the <strong>Save Grasp</strong> button.
			</section>
			<section class='4u'>
				<div id='feedback'>
				</div>
				<button id='clearFeedback' class='button special'>clear</button>
			</section>
		</div>
	</div>
</section>

<script>
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
	new MJPEGCANVAS.MultiStreamViewer({
		divID: 'mjpeg',
		host: '<?php echo $environment['Mjpeg']['host']; ?>',
		port: <?php echo $environment['Mjpeg']['port']; ?>,
		width: size,
		height: size * 0.85,
		quality: <?php echo ($environment['Stream'][0]['quality']) ? $environment['Stream'][0]['quality'] : '90'; ?>,
		topics: <?php echo $streamTopics; ?>,
		labels: <?php echo $streamNames; ?>
	});

	_VIEWER = new ROS3D.Viewer({
		divID: 'viewer',
		width: size,
		height: size * 0.85,
		antialias: true,
		background: '#50817b',
		intensity: 0.660000
	});
</script>

<?php
// URDF
foreach ($environment['Urdf'] as $urdf) {
	echo $this->Rms->urdf(
		$urdf['param'],
		$urdf['Collada']['id'],
		$urdf['Resource']['url']
	);
}
?>

<script>
	var armClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: 'carl_moveit_wrapper/common_actions/arm_action',
		actionName: 'carl_moveit/ArmAction'
	});

	var graspClient = new ROSLIB.ActionClient({
		ros: _ROS,
		serverName: 'rail_grasp_collection/grasp_and_store',
		actionName: 'rail_pick_and_place_msgs/GraspAndStoreAction'
	});

	var segmentClient = new ROSLIB.Service({
		ros: _ROS,
		name: '/rail_segmentation/segment',
		serviceType: 'std_srvs/Empty'
	});
</script>

<script>
	/** add elements to the interface to allow the user to control carl*/
	function addTeleop(){
		//keyboard tele-op
		_TELEOP = new KEYBOARDTELEOP.Teleop({ros: _ROS, topic: '<?php echo $environment['Teleop'][0]['topic']; ?>'});
		<?php if ($environment['Teleop'][0]['throttle']): ?>
		_TELEOP.throttle = <?php echo $environment['Teleop'][0]['throttle']; ?>;
		<?php endif; ?>

		/** arrow keys
		 * on key up and key down send commands to drive or tilt camera
		 */
		var body = document.getElementsByTagName('body')[0];
		body.addEventListener('keydown', function (e) {
			if ([37, 38, 39, 40].indexOf(e.keyCode) > -1) {
				e.preventDefault();
			}
			handleKey(e.keyCode, true);
		}, false);
		body.addEventListener('keyup', function (e) {
			handleKey(e.keyCode, false);
		}, false);
	}

	function addInteractiveMarkers(){
		//add IMs
		<?php foreach ($environment['Im'] as $im): ?>
		new ROS3D.InteractiveMarkerClient({
			ros: _ROS,
			tfClient: _TF,
			camera: _VIEWER.camera,
			rootObject: _VIEWER.selectableObjects,
			<?php echo isset($im['Collada']['id']) ? __('loader:%d,', h($im['Collada']['id'])) : ''; ?>
			<?php echo isset($im['Resource']['url']) ? __('path:"%s",', h($im['Resource']['url'])) : ''; ?>
			topic: '<?php echo h($im['topic']); ?>'
		});
		<?php endforeach; ?>
	}

	function addButtons(){
		$('#segment').addClass('special');
		$('#ready').addClass('special');
		$('#grasp').addClass('special');
		//create the callbacks for the segment/ready/retract buttons
		$('#segment').click(function (e) {
			e.preventDefault();
			var request = new ROSLIB.ServiceRequest({});
			segmentClient.callService(request, function (result) {
			});
		});
		$('#ready').click(function (e) {
			e.preventDefault();
			var goal = new ROSLIB.Goal({
				actionClient: armClient,
				goalMessage: {
					action: 0
				}
			});
			goal.on('feedback',function(message){
				showFeedback(0,false,message.message);
			});
			goal.send();
		});
		$('#grasp').click(function (e) {
			e.preventDefault();
			var goal = new ROSLIB.Goal({
				actionClient: graspClient,
				goalMessage: {
					lift: true,
					verify: false,
					object_name: 'interfacetest'
				}
			});
			goal.on('feedback',function(message){
				showFeedback(0,false,message.message);
			});
			goal.send();
		});
	}
</script>

<script>
	var enabled = false;

	/**
	 * add potentially tutorialized things to the interface
	 */
	addInteractiveMarkers();
	addButtons();
	addTeleop();
	enabled = true;

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
	pickup_feedback.subscribe(function(message){
		showFeedback(0,false,message.feedback.message);
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
</script>

<script>
	_VIEWER.camera.position.x = 1.8;
	_VIEWER.camera.position.y = 1.0;
	_VIEWER.camera.position.z = 3.0;
	_VIEWER.camera.rotation.x = -0.65;
	_VIEWER.camera.rotation.y = 0.82;
	_VIEWER.camera.rotation.z = 2.38;

	_VIEWER.addObject(
		new ROS3D.SceneNode({
			object: new ROS3D.Grid({cellSize: 0.75, size: 20, color: '#2B0000'}),
			tfClient: _TF,
			frameID: '/map'
		})
	);
</script>

<script>
	// add camera controls
	var headControl = new ROSLIB.Topic({
		ros: _ROS,
		name: 'asus_controller/tilt',
		messageType: 'std_msgs/Float64'
	});
	var frontControl = new ROSLIB.Topic({
		ros: _ROS,
		name: 'creative_controller/pan',
		messageType: 'std_msgs/Float64'
	});

	var handleKey = function (keyCode, keyDown) {
		var pan = 0;
		var tilt = 0;

// check which key was pressed
		switch (keyCode) {
			case 38:
// up
				tilt = (keyDown) ? -10 : 0;
				headControl.publish(new ROSLIB.Message({data: tilt}));
				break;
			case 40:
// down
				tilt = (keyDown) ? 10 : 0;
				headControl.publish(new ROSLIB.Message({data: tilt}));
				break;
			case 37:
// left
				pan = (keyDown) ? 10 : 0;
				frontControl.publish(new ROSLIB.Message({data: pan}));
				break;
			case 39:
// right
				pan = (keyDown) ? -10 : 0;
				frontControl.publish(new ROSLIB.Message({data: pan}));
				break;
		}
	}
</script>
