<?php
/**
 * Main Interface Controller
 *
 * All interfaces should extend the interface controller. This provides useful functions such as checking if a user is
 * authorized to view the given interface.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
abstract class InterfaceController extends AppController {

/**
 * The used models for the controller.
 *
 * @var array
 */
	public $uses = array('Environment', 'Appointment');

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * Check for a valid interface request. This means that the user (if any) is authorized to view the interface.
 *
 * @throws NotFoundException Thrown if invalid arguments are passed.
 * @throws MethodNotAllowedException Thrown if a non-view request is made.
 * @throws ForbiddenException Thrown if a user is new authorized for the interface at the given time.
 * @return null
 */
	public function beforeFilter() {
		parent::beforeFilter();
		// allow anyone to view an interface (interface authorization will check this better)
		$this->Auth->allow('view');

		// check the request
		if ($this->request['action'] === 'view' && isset($this->request['pass'][0])) {
			// grab the environment we need
			$this->Environment->recursive = 3;
			$environment = $this->Environment->findById($this->request['pass'][0]);

			// check the environment
			if (!$environment) {
				// no valid entry found for the given environment ID
				throw new NotFoundException('Invalid environment.');
			}
			// set the URI for ease of use
			if (isset($environment['Rosbridge']['id'])) {
				$environment['Rosbridge']['uri'] = __(
					'%s://%s:%d',
					$environment['Rosbridge']['Protocol']['name'],
					$environment['Rosbridge']['host'],
					$environment['Rosbridge']['port']
				);
			}
			$this->set('environment', $environment);

			// check the interface
			$iface = null;
			foreach ($environment['Iface'] as $interface) {
				$class = __('%sInterface', str_replace(' ', '', ucwords(h($interface['name']))));
				if ($class === $this->request['controller']) {
					$iface = $interface;
				}
			}
			if (!$iface) {
				// no valid entry found for the given interface ID
				throw new NotFoundException('Invalid interface.');
			}
			$this->set('iface', array('Interface' => $iface));

			// check if this is a user study
			if ($this->Session->read('appointment_id') > 0) {
				// validate the appointment
				$this->Appointment->recursive = 2;
				$appointment = $this->Appointment->findById($this->Session->read('appointment_id'));
				if ($appointment && $appointment['Appointment']['user_id'] === $this->Auth->user('id')
					&& $appointment['Slot']['Condition']['iface_id'] === $iface['id']
					&& $appointment['Slot']['Condition']['environment_id'] === $environment['Environment']['id']
					&& strtotime($appointment['Slot']['start']) <= strtotime('now')
					&& strtotime($appointment['Slot']['end']) > strtotime('now')) {
					// valid study
					$this->set('appointment', $appointment);
					return;
				}
				// invalid study, don't try again
				$this->Session->delete('appointment_id');
			}

			// no study -- check if we are authorized
			if (($iface['unrestricted'] && ($iface['anonymous'] || $this->viewVars['loggedIn']))
				|| $this->viewVars['admin']) {
				// anyone can access
				return;
			}

			// this means we don't have access
			throw new ForbiddenException();
		} else {
			// invalid request
			throw new MethodNotAllowedException();
		}
	}

/**
 * Default view function. This method can be overwritten for custom controller functionality.
 *
 * @return null
 */
	public function view() {
	}
}
