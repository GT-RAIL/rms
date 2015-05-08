<?php
/**
 * Email Subscriptions Controller
 *
 * Email subscriptions control what types of automated emails a user can get.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class SubscriptionsController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The admin newsletter action. This allows the admin to send a newsletter to subscribers.
 *
 * @return null
 */
	public function admin_newsletter() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// grab the email data
			$users = $this->Subscription->find(
				'all',
				array(
					'conditions' => array('Subscription.newsletter' => true),
					'fields' => array('User.email')
				)
			);
			$bcc = array();
			foreach ($users as $user) {
				$bcc[] = $user['User']['email'];
			}

			$this->sendBatchEmail($bcc, 'Newsletter', $this->request->data['Newsletter']['message']);
			$this->Session->setFlash('The newsletter has been sent.');
			return $this->redirect(array('controller' => 'content', 'action' => 'index'));
		}

		$this->set('title_for_layout', 'Send Newsletter');
	}

/**
 * The admin study announcement action. This allows the admin to send an announcement to subscribers.
 *
 * @return null
 */
	public function admin_announcement() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// grab the email data
			$users = $this->Subscription->find(
				'all',
				array(
					'conditions' => array('Subscription.studies' => true),
					'fields' => array('User.email')
				)
			);
			$bcc = array();
			foreach ($users as $user) {
				$bcc[] = $user['User']['email'];
			}

			$this->sendBatchEmail($bcc, 'New Study Available', $this->request->data['Studies']['message']);
			$this->Session->setFlash('The announcement has been sent.');
			return $this->redirect(array('controller' => 'experiment', 'action' => 'index'));
		}

		$this->set('title_for_layout', 'Send Study Announcement');
	}

/**
 * The default index simply redirects to the view action.
 *
 * @return null
 */
	public function index() {
		return $this->redirect(array('action' => 'view'));
	}

/**
 * View the subscription status for the logged in user.
 *
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
 */
	public function view() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$subscription = $this->Subscription->find('first', array('conditions' => array('Subscription.user_id' => $id)));

		if (!$subscription) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid subscription.');
		}

		// store the entry
		$this->set('subscription', $subscription);
		$this->set('title_for_layout', 'Email Subscriptions');
	}

/**
 * Toggle the newsletter field for the logged in user.
 *
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
 */
	public function newsletters() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$subscription = $this->Subscription->find('first', array('conditions' => array('Subscription.user_id' => $id)));
		if (!$subscription) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid subscription.');
		}

		// update the field
		$this->Subscription->read(null, $subscription['Subscription']['id']);
		$this->Subscription->saveField('newsletter', !$subscription['Subscription']['newsletter']);
		$this->Subscription->saveField('modified', date('Y-m-d H:i:s'));

		// return to the index
		return $this->redirect(array('action' => 'view'));
	}

/**
 * Toggle the studies field for the logged in user.
 *
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
 */
	public function studies() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$subscription = $this->Subscription->find('first', array('conditions' => array('Subscription.user_id' => $id)));
		if (!$subscription) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid subscription.');
		}

		// update the field
		$this->Subscription->read(null, $subscription['Subscription']['id']);
		$this->Subscription->saveField('studies', !$subscription['Subscription']['studies']);
		$this->Subscription->saveField('modified', date('Y-m-d H:i:s'));

		// return to the index
		return $this->redirect(array('action' => 'view'));
	}

/**
 * Toggle the reminders field for the logged in user.
 *
 * @throws NotFoundException Thrown if an entry with the logged in user ID is not found.
 * @return null
 */
	public function reminders() {
		// find the ID
		$id = $this->Auth->user('id');
		// grab the entry
		$subscription = $this->Subscription->find('first', array('conditions' => array('Subscription.user_id' => $id)));
		if (!$subscription) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid subscription.');
		}

		// update the field
		$this->Subscription->read(null, $subscription['Subscription']['id']);
		$this->Subscription->saveField('reminders', !$subscription['Subscription']['reminders']);
		$this->Subscription->saveField('modified', date('Y-m-d H:i:s'));

		// return to the index
		return $this->redirect(array('action' => 'view'));
	}
}
