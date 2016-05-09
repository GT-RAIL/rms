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
// user id
if (isset($appointment['Appointment']['user_id'])){
    $user_id = $appointment['Appointment']['user_id'];
}

else if(isset($userId)) {
    $user_id = $userId;
} else if(isset($_GET['userid'])){
    $user_id = $_GET['userid'];
}
else{
    $user_id = '';
}
?>;


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
echo $this->Html->css(array(
			'//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css'));
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
//$is_anonymous = $environment['Iface'][ $environment['Condition'][0]['iface_id'] ]['anonymous'];
$appointment = $environment['Condition'][0]['Slot'][0];
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
		quality: <?php echo $environment['Stream']?(($environment['Stream'][0]['quality']) ? $environment['Stream'][0]['quality'] : '90'):''; ?>,
		topics: <?php echo $streamTopics; ?>,
		labels: <?php echo $streamNames; ?>,
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
    var segmentation_interval = 5000;

});

var user_id =  <?php echo $user_id;?>
</script>





<section class="wrapper style4 container" id="study-page" >
<div class="text-left row col-lg-6">
    <h4>Task Tree</h4>
    <div class="col-lg-12">
        <div id="jstree_loading_div" class="alert alert-info">Loading...</div>
        <div id="jstree_div"></div>
    </div>
    <div class='col-lg-12'>
    <button id="remove-user" name="remove-user" class="btn btn-primary">Remove Currently Active User</button>
    </div>
</div>
<div class="col-lg-6">
    <div id="mjpeg">
    </div>
    <h4>System Status</h4>
    <div id="feedback" class="col-lg-12">
    Ready
    </div>
    <div id="feedback" class="col-lg-12 feedback-overlay hidden">
        ERROR:
    </div>
</div>



        <!-- ROS service calls -->
        <script>
            var button_topic = new ROSLIB.Topic({
                ros: _ROS,
                name: '/web_interface/button',
                messageType: 'heres_how_msgs/WebInterfaceButton'
            });
            button_topic.advertise();

 
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
				var data = create_jstree_data(htn.data);
                $("#jstree_div").jstree('destroy');
				$("#jstree_div").jstree({
					core: {
						data: data
					}
				});
				return data;
			}
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

            //Finish Task Button. Resets the Java Interface
            $("#remove-user").click(function(event){
                event.preventDefault();
                var button_msg = new ROSLIB.Message({
                    button: "finishTask"
                });
                button_topic.publish(button_msg);

                //TODO: Something happens on Finish
                //location.reload(); 
            });

            function update_htn() {
                var button_msg = new ROSLIB.Message({
                    button: "updateHTN"
                });
                button_topic.publish(button_msg);
            };
            

            // Load tree on page load
            $(document).ready(function() {
                // This has a wait due to an issue with page refresh
                // unregistering and publish bug 
                // TODO: verify problem and find a better solution
                // https://github.com/RobotWebTools/rosbridge_suite/issues/138
                 window.setTimeout(update_htn, 3000);
            });
            
        </script>
	</div>
</section>
