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
 * @version		2.0.0
 * @package		app.Controller
 */
abstract class InterfaceController extends AppController {

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
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		// allow anyone to view an interface (interface authorization will check this better)
		$this->Auth->allow('view');

		// check the request
		if ($this->request['action'] === 'view' && isset($this->request['pass'][0])) {
			// grab the environment we need
			$this->loadModel('Environment');
			$this->Environment->recursive = 2;
			$environment = $this->Environment->findById($this->request['pass'][0]);

			// check the environment
			if (!$environment) {
				// no valid entry found for the given environment ID
				throw new NotFoundException('Invalid environment.');
			}
			// set the URI for ease of use
			$environment['Rosbridge']['uri'] = __(
				'%s://%s:%d',
				$environment['Rosbridge']['Protocol']['name'],
				$environment['Rosbridge']['host'],
				$environment['Rosbridge']['port']
			);
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

			// now check if we are authorized
			if ($iface['anonymous']) {
				// anyone can access
				return;
			} else {
				if($iface['unrestricted']) {
					if ($this->viewVars['loggedIn']) {
						// no scheduled session required
						return;
					}
				} else {
					// TODO: check the study session
				}
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
	 */
	public function view() {
	}
}
