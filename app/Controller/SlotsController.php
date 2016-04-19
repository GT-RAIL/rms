<?php
/**
 * Study Session Slots Controller
 *
 * A user study session slot contains information about the associated start/end time and associated
 * condition/environment.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class SlotsController extends AppController {

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
 * Define pagination criteria.
 *
 * @var array
 */
	public $paginate = array('limit' => 25, 'order' => array('Slot.start' => 'DESC'), 'recursive' => 2);

/**
 * The admin index action lists information about all appointments. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		$this->Paginator->settings = $this->paginate;
		// grab all the fetched entries
		$this->set('slots', $this->Paginator->paginate('Slot'));
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the conditions
		$this->__setConditionsList();

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Slot->create();
			// set the current timestamp for creation and modification
			$this->Slot->data['Slot']['created'] = date('Y-m-d H:i:s');
			$this->Slot->data['Slot']['modified'] = date('Y-m-d H:i:s');
			// determine the end time based on session length
			$this->__setEndTime();
			if (!$this->__verifyTimeSlot()) {
				$this->Session->setFlash(
					'Slot time overlaps existing slot and parallel slots are disabled for this study.'
				);
			} else {
				// attempt to save the entry
				if ($this->Slot->save($this->request->data)) {
					$this->Session->setFlash('The slot has been saved.');
					return $this->redirect(array('action' => 'index'));
				}
				$this->Session->setFlash('Unable to add the slot.');
			}
		}

		$this->set('title_for_layout', 'Add Study Session Slot');
	}

/**
 * The admin edit action. This allows the admin to edit an existing entry.
 *
 * @param int $id The ID of the entry to edit.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function admin_edit($id = null) {
		// load the conditions
		$this->__setConditionsList();

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid slot.');
		}

		$slot = $this->Slot->findById($id);
		if (!$slot) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid slot.');
		}

		// only work for PUT requests
		if ($this->request->is(array('slot', 'put'))) {
			// set the ID
			$this->Slot->id = $id;
			// set the current timestamp for modification
			$this->Slot->data['Slot']['modified'] = date('Y-m-d H:i:s');
			// determine the end time based on session length
			$this->__setEndTime();
			if (!$this->__verifyTimeSlot()) {
				$this->Session->setFlash(
					'Slot time overlaps existing slot and parallel slots are disabled for this study.'
				);
			} else {
				// attempt to save the entry
				if ($this->Slot->save($this->request->data)) {
					$this->Session->setFlash('The slot has been updated.');
					return $this->redirect(array('action' => 'index'));
				}
				$this->Session->setFlash('Unable to update the slot.');
			}
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $slot;
		}

		$this->set('title_for_layout', __('Edit Condition - %s', $slot['Condition']['name']));
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
		if ($this->Slot->delete($id)) {
			$this->Session->setFlash('The slot has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * Set the conditions list by adding the study name to the entry.
 *
 * @return null
 */
	private function __setConditionsList() {
		$conditions = $this->Slot->Condition->find('all');
		$conditionsList = array();
		foreach ($conditions as $condition) {
			$conditionsList[$condition['Condition']['id']] = __(
				'%s / %s', h($condition['Study']['name']), h($condition['Condition']['name'])
			);
		}
		$this->set('conditions', $conditionsList);
	}

/**
 * Set the end time based on the start time in a request.
 *
 * @return null
 */
	private function __setEndTime() {
		$condition = $this->Slot->Condition->findById($this->request->data['Slot']['condition_id']);
		// length in minutes
		$length = $condition['Study']['length'];
		$start = new DateTime(__(
			'%d-%d-%d %d:%s %s',
			h($this->request->data['Slot']['start']['year']),
			h($this->request->data['Slot']['start']['month']),
			h($this->request->data['Slot']['start']['day']),
			h($this->request->data['Slot']['start']['hour']),
			h($this->request->data['Slot']['start']['min']),
			h($this->request->data['Slot']['start']['meridian'])
		));
		$startDate = strtotime($start->format('Y-m-d H:i:s'));
		$endDate = $startDate + ($length * 60);
		$this->Slot->data['Slot']['end'] = date('Y-m-d H:i:s', $endDate);
		$this->request->data['Slot']['start'] = date('Y-m-d H:i:s', $startDate);
	}

/**
 * Verify that time slots do not overlap unless parallel sessions are allowed.
 *
 * @return bool If the time slot is valid.
 */
	private function __verifyTimeSlot() {
		$condition = $this->Slot->Condition->findById($this->request->data['Slot']['condition_id']);
		$start = $this->request->data['Slot']['start'];
		$end = $this->Slot->data['Slot']['end'];

		// check for parallel slots
		if (!$condition['Study']['parallel']) {
			// check for overlapping slots
			$count = $this->Slot->find(
				'count',
				array(
					'conditions' => array(
						'Slot.id !=' => (isset($this->request->data['Slot']['id']))
								? $this->request->data['Slot']['id'] : -1,
						'Condition.study_id' => $condition['Study']['id'],
						'Slot.end >' => $start,
						'OR' => array(array('Slot.start <=' => $start), array('Slot.start <' => $end))
					),
					'limit' => 1
				)
			);
			return $count === 0;
		}

		// all checks were okay
		return true;
	}
}
