<?php
/**
 * User Studies Controller
 *
 * A user study contains information about the name of the study, access controls, and start/end dates.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class StudiesController extends AppController {

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
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('Study', 'Slot', 'Appointment', 'Condition', 'Log');

/**
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated account creation
		parent::beforeFilter();
		$this->Auth->allow('otf');
	}

/**
 * The admin index action lists information about all studies. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries
		$this->set('studies', $this->Study->find('all'));
		$this->set('title_for_layout', 'User Studies');
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Study->create();
			// set the current timestamp for creation and modification
			$this->Study->data['Study']['created'] = date('Y-m-d H:i:s');
			$this->Study->data['Study']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Study->save($this->request->data)) {
				$this->Session->setFlash('The study has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the study.');
		}

		$this->set('title_for_layout', 'Add Study');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid study.');
		}

		$study = $this->Study->findById($id);
		if (!$study) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid study.');
		}

		// only work for PUT requests
		if ($this->request->is(array('study', 'put'))) {
			// set the ID
			$this->Study->id = $id;
			// set the current timestamp for modification
			$this->Study->data['Study']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Study->save($this->request->data)) {
				$this->Session->setFlash('The study has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the study.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $study;
		}

		$this->set('title_for_layout', __('Edit Study - %s', $study['Study']['name']));
	}

/**
 * The admin delete action. This allows the admin to delete an existing entry.
 *
 * @param int $id The ID of the entry to delete.
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @return null
 */
	public function admin_delete($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		// attempt to delete the entry
		if ($this->Study->delete($id)) {
			$this->Session->setFlash('The study has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * Create a study appointment on-the-fly. This will validate that on-the-fly studies are enabled and the user is
 * allowed to participate at this time.
 *
 * @param int $id The study ID to create an appointment for.
 * @throws NotFoundException Thrown if an invalid study is given.
 * @throws ForbiddenException Thrown if the user is not allowed to access the study.
 * @return null
 */
	public function otf($id = null) {
		// check for the study
		$study = $this->Study->findById($id);
		if (!$study) {
			throw new NotFoundException('Invalid study.');
		}

		// check for OTF
		if (!$study['Study']['otf']) {
			throw new ForbiddenException('On-the-Fly Study is Disabled');
		}

		// check if we are logged in or anonymous studies are allowed
		if (!$this->Auth->user('id') && !$study['Study']['anonymous']) {
			throw new ForbiddenException('Anonymous Study is Disabled');
		}

		// check the length for "infinite" time
		$endTime = ($study['Study']['length'] > 0) ? strtotime('now') + $study['Study']['length'] * 60 : 2147483647;

		// check if we are free to complete the study
		if (!$study['Study']['parallel']) {
			$slots = $this->Slot->find('all', array('recursive' => 2));
			foreach ($slots as $slot) {
				if ($slot['Condition']['Study']['id'] === $id && strtotime($slot['Slot']['end']) > strtotime('now')) {
					if (strtotime($slot['Slot']['start']) <= strtotime('now')) {
						throw new ForbiddenException('Study Session Already in Progress');
					} elseif (strtotime($slot['Slot']['start']) <= $endTime) {
						throw new ForbiddenException('Not Enough Free Time Before Next Scheduled Session');
					}
				}
			}
		}

		// check if we are allowed to participate multiple times
		if ($this->Auth->user('id') && !$study['Study']['repeatable']) {
			$appointments = $this->Appointment->find(
				'count',
				array(
					'conditions' => array('Appointment.user_id' => $this->Auth->user('id')),
					'limit' => 1
				)
			);
			if ($appointments > 0) {
				throw new ForbiddenException('You Have Already Signed Up for the Study');
			}
		}

		// randomly pick a condition with the least amount of slots
		$conditions = $this->Condition->find('all', array('recursive' => 3, 'conditions' => array('Study.id' => $id)));
		$toPick = array();
		foreach ($conditions as $condition) {
			if (count($toPick) === 0 || count($toPick[0]['Slot']) === count($condition['Slot'])) {
				$toPick[] = $condition;
			} elseif (count($toPick[0]['Slot']) > count($condition['Slot'])) {
				unset($toPick);
				$toPick = array();
				$toPick[] = $condition;
			}
		}
		$conditionID = $toPick[rand(0, count($toPick) - 1)]['Condition']['id'];

		// good to go! create the slot and appointment
		$this->Slot->create();
		// set the slot information
		$slotData = array(
			'Slot' => array(
				'start' => date('Y-m-d H:i:s'),
				'end' => date('Y-m-d H:i:s', $endTime),
				'condition_id' => $conditionID,
				'created' => date('Y-m-d H:i:s'),
				'modified' => date('Y-m-d H:i:s')
			)
		);

		if ($this->Slot->save($slotData)) {
			$appointmentData = array(
				'Appointment' => array(
					'slot_id' => $this->Slot->id,
					'user_id' => $this->Auth->user('id'),
					'created' => date('Y-m-d H:i:s'),
					'modified' => date('Y-m-d H:i:s')
				)
			);
			if ($this->Appointment->save($appointmentData)) {
				// begin!
				return $this->redirect(
					array('controller' => 'appointments', 'action' => 'begin', $this->Appointment->id)
				);
			}
		}

		// oops -- that didn't work :(
		$this->Session->setFlash('Unable to create appointment.');
		return ($this->Auth->user('id')) ?
			$this->redirect(array('controller' => 'users', 'action' => 'view')) : $this->redirect('/');
	}

/**
 * The admin export action. This allows the admin to export the study log data.
 *
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @throws MethodNotAllowedException Thrown if a POST request is not made.
 * @return null
 */
	public function admin_export() {
		// only work for PUT requests
		if ($this->request->is(array('study', 'post'))) {
			// grab the study
			$id = $this->request->data['Study']['study_id'];
			$this->Study->recursive = 3;
			$study = $this->Study->findById($id);
			if (!$this->Study->findById($id)) {
				// no valid entry found for the given ID
				throw new NotFoundException('Invalid study.');
			}

			// find the logs
			$list = array();
			foreach ($study['Condition'] as $condition) {
				foreach ($condition['Slot'] as $slot) {
					if ($slot['Appointment']) {
						$list[] = $slot['Appointment']['id'];
					}
				}
			}
			$logs = $this->Log->find(
				'all', array('recursive' => 4, 'conditions' => array('Appointment.id' => $list))
			);

			// find the slots
			$this->set('logs', $logs);

			// no layout
			$this->layout = false;
			$this->response->type('csv');
		} else {
			throw new MethodNotAllowedException();
		}
	}
}
