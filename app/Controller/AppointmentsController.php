<?php
/**
 * Study Session Appointments Controller
 *
 * A user study session appointments contains information about the associated user (if any) and slot.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class AppointmentsController extends AppController {

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
	public $uses = array('Appointment', 'Slot');

/**
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated account creation
		parent::beforeFilter();
		$this->Auth->allow('begin');
	}

/**
 * The book action allows a user to book a user study appointment.
 *
 * @throws MethodNotAllowedException Thrown if a post request is made.
 * @return null
 */
	public function book() {
		// only work for POST requests
		if ($this->request->is(array('appointment', 'post'))) {
			// grab the slot we are interested in
			$this->Slot->recursive = 3;
			$slot = $this->Slot->findById($this->request->data['Appointment']['slot_id']);

			$appointments = $this->Appointment->find(
				'all',
				array('recursive' => 3, 'conditions' => array('Appointment.user_id' => $this->Auth->user('id')))
			);

			$next = null;
			foreach ($appointments as $appointment) {
				if ($appointment['Slot']['Condition']['Study']['id'] === $slot['Condition']['Study']['id']) {
					// pick the latest
					if (!$next || strtotime($slot['Slot']['start']) > strtotime($next['Slot']['start'])) {
						$next = $appointment;
					}
				}
			}

			// verify that we can book this appointment
			if ($next && strtotime($next['Slot']['end']) > strtotime('now')) {
				$this->Session->setFlash('Error: you already booked this study.');
			} elseif ($next && !$slot['Condition']['Study']['repeatable']) {
				$this->Session->setFlash('Error: you already completed this study.');
			} else {
				// create a new entry
				$this->Appointment->create();
				// set the current timestamp for creation and modification
				$this->Appointment->data['Appointment']['created'] = date('Y-m-d H:i:s');
				$this->Appointment->data['Appointment']['modified'] = date('Y-m-d H:i:s');
				// set the user ID
				$this->Appointment->data['Appointment']['user_id'] = $this->Auth->user('id');
				// attempt to save the entry
				if ($this->Appointment->save($this->request->data)) {
					$this->Session->setFlash('Your study appointment has been booked.');
				} else {
					$this->Session->setFlash('Error: unable to book your appointment.');
				}
			}

			return $this->redirect(array('controller' => 'users', 'action' => 'view'));
		} else {
			throw new MethodNotAllowedException();
		}
	}

/**
 * The delete action. This allows the user to delete an existing appointment that they own.
 *
 * @param int $id The ID of the entry to delete.
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @return null
 */
	public function delete($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		// verify the entry
		$appointment = $this->Appointment->findById($id);
		if (!$appointment || $appointment['Appointment']['user_id'] !== $this->Auth->user('id')) {
			$this->Session->setFlash('Error: Unable to delete your appointment.');
		} elseif ($this->Appointment->delete($id)) {
			$this->Session->setFlash('The appointment has been deleted.');
		}
		return $this->redirect(array('controller' => 'users', 'action' => 'view'));
	}

/**
 * Begin the scheduled appointment. This will verify the appointment and redirect the user to the interface.
 *
 * @param int $id The ID of the appointment.
 * @throws NotFoundException Thrown if the appointment is not found.
 * @throws ForbiddenException Thrown if the user does not have access to the appointment at this time.
 * @return null
 */
	public function begin($id = null) {
		// find the appointment
		$this->Appointment->recursive = 2;
		$appointment = $this->Appointment->findById($id);
		if (!$appointment) {
			throw new NotFoundException('Invalid appointment.');
		}

		// check for matching users
		if ($appointment['Appointment']['user_id'] !== $this->Auth->user('id')) {
			throw new ForbiddenException();
		}

		// check the time
		if (strtotime($appointment['Slot']['start']) > strtotime('now')
			|| strtotime($appointment['Slot']['end']) <= strtotime('now')) {
			throw new ForbiddenException();
		}

		// good to go -- notify the interface that we are approved
		$this->Session->write('appointment_id', $id);
		return $this->redirect(
			array(
				'controller' => 'ifaces',
				'action' => 'view',
				$appointment['Slot']['Condition']['iface_id'],
				$appointment['Slot']['Condition']['environment_id']
			)
		);
	}
}
