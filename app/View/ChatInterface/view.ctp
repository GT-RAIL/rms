<?php echo $this->Rms->ros($environment['Rosbridge']['uri']);?>

<script>
	var topic = new ROSLIB.Topic({
		ros : _ROS,
		name : '/echo',
		messageType : 'std_msgs/String'
	});
</script>

<header class="special container">
	<span class="icon fa-cloud"></span>
	<h2>My Interface</h2>
</header>

<section class="wrapper style4 container">
	<div class="content center">
		<input id="message" value="Hello!"/>
		<br />
		<button id="send" class="button">Send Message</button>
		<script>
			$('#send').click(function() {
				var msg = new ROSLIB.Message({
					data : $("#message").val()
				});
				topic.publish(msg);
			});
		</script>
	</div>
</section>
