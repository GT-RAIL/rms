<?php
/**
 * Admin Export Study View
 *
 * The export study view allows an admin to export all log data from a study.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.View.Studies
 */

echo "Log ID,Appointment ID,User ID,Study ID,Condition ID,Type,Label,Entry,Timestamp\n";
foreach ($logs as $log) {
	echo h($log['Log']['id']);
	echo ",";
	echo h($log['Appointment']['id']);
	echo ",";
	echo h($log['Appointment']['User']['id']);
	echo ",";
	echo h($log['Appointment']['Slot']['Condition']['Study']['id']);
	echo ",";
	echo h($log['Appointment']['Slot']['Condition']['id']);
	echo ",";
	echo h($log['Type']['name']);
	echo ",";
	echo '"' . h($log['Log']['label']) . '"';
	echo ",";
	echo '"' . h($log['Log']['entry']) . '"';
	echo ",";
	echo h($log['Log']['created']);
	echo "\n";
}
