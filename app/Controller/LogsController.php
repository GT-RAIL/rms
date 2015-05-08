<?php
/**
 * Study Logs Controller
 *
 * Study logs contain log information from a study appointment.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class LogsController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Paginator', 'Time');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Paginator', 'Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('Log', 'Study');

/**
 * Define pagination criteria.
 *
 * @var array
 */
	public $paginate = array('limit' => 15, 'order' => array('Log.created' => 'DESC'), 'recursive' => 1);

/**
 * Define the actions which can be used by any user, authorized or not.
 * 
 * @return null
 */
	public function beforeFilter() {
		// allow anyone to view an interface (interface authorization will check this better)
		parent::beforeFilter();
		$this->Auth->allow('add');
	}

/**
 * The admin index action lists information about all logs. Further view options can be found from this action.
 *
 * @return null
 */
	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		// grab all the fetched entries
		$logs = $this->Paginator->paginate('Log');
		foreach ($logs as &$log) {
			$this->Appointment->recursive = -1;
			$appointment = $this->Appointment->findById($log['Log']['appointment_id']);
			$slot = $this->Appointment->Slot->findById($appointment['Appointment']['slot_id']);
			$study = $this->Appointment->Slot->Condition->Study->findById($slot['Condition']['study_id']);
			$log['Study'] = $study['Study'];
		}
		$this->set('logs', $logs);
		// grab the experiments
		$this->set('studies', $this->Study->find('list'));
	}

/**
 * The log function allows any valid study session to log data.
 *
 * @throws MethodNotAllowedException Thrown if a POST request is not made.
 * @throws ForbiddenException Thrown if there is no valid study.
 * @return null
 */
	public function add() {
		if ($this->request->is('post')) {
			// verify we are allowed to log
			if ($this->Session->read('appointment_id') > 0) {
				// create the entry
				$this->Log->create();
				$logData = array(
					'Log' => array(
						'appointment_id' => $this->Session->read('appointment_id'),
						'type_id' => h($this->request->data('type')),
						'label' => h($this->request->data('label')),
						'entry' => h($this->request->data('entry')),
						'created' => date('Y-m-d H:i:s'),
						'modified' => date('Y-m-d H:i:s')
					)
				);
				if ($this->Log->save($logData)) {
					// success, empty response
					$this->layout = false;
				} else {
					// invalid data sent
					throw new ForbiddenException();
				}
			} else {
				// no study
				throw new ForbiddenException();
			}
		} else {
			throw new MethodNotAllowedException();
		}
	}
}
