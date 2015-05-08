<?php
App::uses('CakeEmail', 'Network/Email');

/**
 * Study Reminder Shell
 *
 * The study reminder shell will check for all appointments within the next 30 minutes. A reminder email will then be
 * sent to any user who is subscribed to reminder emails. This is typically run in a cron job.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Console.Command
 */
class ReminderShell extends AppShell {

/**
 * The used models for the shell.
 *
 * @var array
 */
	public $uses = array('User', 'Appointment', 'Setting');

/**
 * The main function for the shell.
 *
 * @return null
 */
	public function main() {
		// load the site settings
		$setting = $this->Setting->findById(Setting::$default);

		// load the studies for the next hour for people who want emails
		$this->out('Loading studies for the next 30 minutes...');
		$appointments = $this->Appointment->find(
			'all',
			array(
				'recursive' => 3,
				'conditions' => array('Slot.start >= NOW()', 'Slot.start < DATE_ADD(NOW(), INTERVAL 30 MINUTE)')
			)
		);
		$this->out(__('    Found %d studies for the next hour.', count($appointments)));

		// find the ones who want the email
		$toEmail = array();
		foreach ($appointments as $appointment) {
			if ($appointment['User']['Subscription']['reminders']) {
				$toEmail[] = array(
					'User' => $appointment['User'],
					'Slot' => $appointment['Slot'],
					'Study' => $appointment['Slot']['Condition']['Study']
				);
			}
		}
		$this->out(__('    Found %d users who are subscribed to reminders.', count($toEmail)));

		// send out the email
		$this->out('Composing and sending email reminders...');
		foreach ($toEmail as $current) {
			$email = new CakeEmail('dynamic');
			$email->to($current['User']['email']);
			$email->subject(__('Study Reminder - %s', h($current['Study']['name'])));
			// generate the content
			$content = __('Dear %s,\n\n', h($current['User']['fname']));
			$content .= __(
				'This is an email reminder that you are signed up for the study "%s" at %s.\n\n',
				h($current['Study']['name']),
				date('g:i A T', strtotime($current['Slot']['start']))
			);
			$content .= __('--The %s Team', h($setting['Setting']['title']));
			$email->send($content);
			$this->out(__('    Email sent to %s.', h($current['User']['email'])));
		}
	}
}