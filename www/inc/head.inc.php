<?php
/*********************************************************************
 *
 * Software License Agreement (BSD License)
 *
 *  Copyright (c) 2012, Worcester Polytechnic Institute
 *  All rights reserved.
 *
 *  Redistribution and use in source and binary forms, with or without
 *  modification, are permitted provided that the following conditions
 *  are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   * Redistributions in binary form must reproduce the above
 *     copyright notice, this list of conditions and the following
 *     disclaimer in the documentation and/or other materials provided
 *     with the distribution.
 *   * Neither the name of the Worcester Polytechnic Institute nor the
 *     names of its contributors may be used to endorse or promote
 *     products derived from this software without specific prior
 *     written permission.
 *
 *  THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 *  "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 *  LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 *  FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 *  COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 *  INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 *  BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 *  LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 *  CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 *  LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 *  ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 *  POSSIBILITY OF SUCH DAMAGE.
 *
 *   Author: Russell Toris
 *  Version: October 5, 2012
 *
 *********************************************************************/
?>

<?php
/**
 * A function to echo the HTML for the HEAD section of the HTML document.
 */
function import_head() {
	import_meta();
	import_common_css();
	import_common_js();
	import_analytics();
}

/**
 * A function to echo the HTML to set the meta information.
 */
function import_meta() {?>
<meta
	http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
}

/**
 * A function to echo the HTML to import common CSS files.
 */
function import_common_css() {?>
<link
	rel="stylesheet" type="text/css" href="css/common.css" />
<link
	rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.22.custom.css" />
<?php
}

/**
 * A function to echo the HTML to import common JS files.
 */
function import_common_js() {?>
<script
	type="text/javascript" src="js/rms/common.js"></script>
<script
	type="text/javascript" src="js/jquery/jquery-1.7.2.min.js"></script>
<script
	type="text/javascript" src="js/jquery/jquery-ui-1.8.22.custom.min.js"></script>
<?php
}

/**
 * A function to echo the HTML to import ROS Javascript files.
 */
function import_ros_js() {?>
<script
	type="text/javascript" src="js/ros/ros_bundle.min.js"></script>
<?php
}

/**
 * A function to echo the HTML to import Google Analytics settings.
 */
function import_analytics() {
	global $google_tracking_id;

	// check if tracking is being used
	if(strlen($google_tracking_id) > 0) {?>
<script type="text/javascript">
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', '<?php echo $google_tracking_id?>']);
	_gaq.push(['_trackPageview']);
	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
</script>
	<?php
	}
}
?>

