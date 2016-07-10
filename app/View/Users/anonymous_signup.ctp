<?php
/**
 * Sign Up View
 *
 * The sign up page will allow a new user to create an account.
 *
 * @author		Carl Saldanha - csaldanha3@gatech.edu
 * @copyright	2016 Georgia Institute Of Technology
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Users
 */
?>
<script   src="https://code.jquery.com/jquery-1.12.2.min.js"   integrity="sha256-lZFHibXzMHo3GGeehn1hudTAP3Sc0uKXBXAzHX1sjtk="   crossorigin="anonymous"></script>
<header class="special container">
	<span class="icon fa-users"></span>
	<h2>Sign Up</h2>
</header>

<section class="wrapper style4 container">
	<div class="content">
		<section>
			<header>
				<h3>Please Fill Out the Following Form</h3>
				<p>This is the sign-up page for the TRAINS study. If you would like to participate, please sign up. </p>
			</header>
			<?php echo $this->Form->create('User'); ?>
			<div class="row">
				<section class="6u">
					<?php
					echo $this->Form->input('username');
					echo $this->Form->input('password');

					?>
					<?php 
					if(isset($_GET['campaign']))
						echo $this->Form->hidden('campaign',array('value'=>$_GET['campaign']))
					?>
				</section>
			</div>
			<div class="row">
				<section class="6u">
					<p><input type='checkbox' id='continue'  value="continue"/>Would you like to continue to hear about new studies from us?</p>
					<div id='email'>
						<?php echo $this->Form->input('email',array('id'=>'emailinput','value'=>'anonymous@doesnotwork.com'));?>
					</div>
			</div>
			<div class="row">
				<section class="12u">
					<?php echo $this->Form->end(array('label' => 'Sign Up', 'class' => 'button special')); ?>
				</section>
			</div>
			<br />
			<div class="row center">
				<section class="3u">
					Have an account?
					<br />
					Click <?php echo $this->Html->link('here', array('action' => 'login')); ?> to login.
				</section>
			</div>
		</section>
	</div>
</section>

<script type="text/javascript">
$(document).ready(function() {
	$("#email").hide();
	$('#continue').click(function(){
		$("#email").toggle()
		if(!$("#emailinput").is(":visible")){
			$("#emailinput").val("anonymous@doesnotwork.com")
		}
		else{
			$("#emailinput").val("")
		}
	 })
});
</script>