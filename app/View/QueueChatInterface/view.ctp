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
// connect to ROS
echo $this->Rms->ros($environment['Rosbridge']['uri']);

//Init study information
echo $this->Rms->initStudy();

echo $this->Html->script(array(
			'//localhost/widgets/rosqueuejs/build/rosqueue.js'));

//$appointment = $environment['Condition'][0]['Slot'][0];
//$is_anonymous = $environment['Iface'][ $environment['Condition'][0]['iface_id'] ]['anonymous'];
?>

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
	var topic = new ROSLIB.Topic({
		ros : _ROS,
		name : '/echo',
		messageType : 'std_msgs/String'
	});
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
	<h2>Queue Chat Interface</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
        <div id="chat-container" class="chat-container">
            <div id="chat-display" class="chat-display"> 
            </div>
            <span>
                <textarea id="chat-text-input" class="chat-text-input" placeholder="Begin typing to chat..."  ></textarea>
            </span>
        </div>
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
