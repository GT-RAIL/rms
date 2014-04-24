<?php
class PagesController extends AppController {
	public $helpers = array('Html', 'Form');

	public $components = array(
		'Session',
		'Auth' => array(
			'authorize' => 'Controller'
		)
	);

	public function beforeFilter() {
		// only allow unauthenticated viewing of a single page
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

	public function admin_index() {
		// grab all the entries
		$this->set('pages', $this->Page->find('all', array('order' => array('Page.index' => 'ASC'))));
	}

	public function admin_add() {
		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Page->create();
			// place at the end
			$numPages = $this->Page->find('count');
			$this->Page->data['Page']['index'] = $numPages;
			// set the current timestamp for creation and modification
			$this->Page->data['Page']['created'] = date('Y-m-d H:i:s');
			$this->Page->data['Page']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash(__('Your page has been saved.'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Unable to add your page.'));
		}
	}

	public function admin_incrementIndex($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if (!$id) {
			// no ID provided
			throw new NotFoundException(__('Invalid page'));
		}

		$target = $this->Page->findById($id);
		if (!$target) {
			// no valid entry found for the given ID
			throw new NotFoundException(__('Invalid page'));
		}
		$index = $target['Page']['index'];

		// grab all pages
		$pages = $this->Page->find('all', array('order' => array('Page.index' => 'ASC')));

		// make sure we can actually increment
		if($index + 1 < count($pages)) {
			// place the target at the end temporarily
			$target['Page']['index'] = count($pages) + 1;
			$this->Page->save($target);

			// move the next entry down
			$page = $pages[$index + 1];
			$page['Page']['index'] = $index;
			$page['Page']['modified'] = date('Y-m-d H:i:s');
			$this->Page->save($page);

			// and finally place the target in the correct spot
			$target['Page']['index'] = $index + 1;
			$target['Page']['modified'] = date('Y-m-d H:i:s');
			$this->Page->save($target);
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
	}

	public function admin_decrementIndex($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if (!$id) {
			// no ID provided
			throw new NotFoundException(__('Invalid page'));
		}

		$target = $this->Page->findById($id);
		if (!$target) {
			// no valid entry found for the given ID
			throw new NotFoundException(__('Invalid page'));
		}
		$index = $target['Page']['index'];

		// grab all pages
		$pages = $this->Page->find('all', array('order' => array('Page.index' => 'ASC')));

		// make sure we can actually decrement
		if($index > 0) {
			// place the target at the end temporarily
			$target['Page']['index'] = count($pages)+1;
			$this->Page->save($target);

			// move the previous entry up
			$page = $pages[$index - 1];
			$page['Page']['index'] = $index;
			$page['Page']['modified'] = date('Y-m-d H:i:s');
			$this->Page->save($page);

			// and finally place the target in the correct spot
			$target['Page']['index'] = $index - 1;
			$target['Page']['modified'] = date('Y-m-d H:i:s');
			$this->Page->save($target);
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
	}

	public function view($id = null) {
		// get the homepage
		$home = $this->Page->find('first', array('order' => array('Page.index' => 'ASC')));

		// check if an ID was given -- if not, use the first one
		$page = (!$id) ? $home : $this->Page->findById($id);

		if (!$page) {
			// no valid entry found for the given ID
			throw new NotFoundException(__('Invalid page'));
		}

		// store the entry
		$this->set('page', $page);
		$this->set('home', $home['Page']['id'] === $page['Page']['id']);
		$this->set('title_for_layout', $page['Page']['title']);
	}

	public function admin_edit($id = null) {
		if (!$id) {
			// no ID provided
			throw new NotFoundException(__('Invalid page'));
		}

		$page = $this->Page->findById($id);
		if (!$page) {
			// no valid entry found for the given ID
			throw new NotFoundException(__('Invalid page'));
		}

		// only work for PUT requests
		if ($this->request->is(array('role', 'put'))) {
			// set the ID
			$this->Page->id = $id;
			// set the current timestamp for modification
			$this->Page->data['Page']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash(__('Your page has been updated.'));
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash(__('Unable to update your page.'));
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $page;
		}
	}

	public function admin_delete($id) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		// attempt to delete the entry
		if ($this->Page->delete($id)) {
			$this->Session->setFlash(
				__('The role with id: %s has been deleted.', h($id))
			);
			return $this->redirect(array('action' => 'index'));
		}
	}
}