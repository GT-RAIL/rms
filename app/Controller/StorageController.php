<?php
/**
 * The Controller to handle storing the data for the experiment in the DB
 *
 * @author		Russell Toris - rctoris@wpi.edu, Peter Mitrano - robotwizard@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/CrowdManipulationInterface
 * @since		CrowdManipulationInterface v 0.0.1
 * @version		0.0.1
 * @package		app.Controller
 */
class StorageController extends AppController {

	public $uses = array('HtnStore');

	public $components = array('RequestHandler');

/**
 * Store the HTN
 *
 * @throws BadRequestException
 * @return null
 */
	public function store_htn() {
		if ($this->request->is('ajax') && $this->request->is('post')) {
			$this->HtnStore->create();
			$this->log(json_encode($this->request->data), "debug");
			if (!$this->HtnStore->save($this->request->data)) {
				throw new BadRequestException(__("Bad Request"));
			}
		}
	}
}
