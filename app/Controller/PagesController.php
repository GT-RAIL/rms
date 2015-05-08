<?php
/**
 * Content Pages Controller
 *
 * Content pages contain information about the given RMS site. Each content page has a number of articles associated
 * with it. Menu items for these pages are automatically generated. The content page with the first index will be
 * defined as the homepage.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Controller
 */
class PagesController extends AppController {

/**
 * The used helpers for the controller.
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Time');

/**
 * The used components for the controller.
 *
 * @var array
 */
	public $components = array('Session', 'Auth' => array('authorize' => 'Controller'));

/**
 * Define the actions which can be used by any user, authorized or not.
 *
 * @return null
 */
	public function beforeFilter() {
		// only allow unauthenticated viewing of a single page
		parent::beforeFilter();
		$this->Auth->allow('view');
	}

/**
 * The admin index action lists information about all pages. This allows the admin to add, edit, or delete entries.
 *
 * @return null
 */
	public function admin_index() {
		// grab all the entries
		$this->set('pages', $this->Page->find('all', array('order' => array('Page.index' => 'ASC'))));
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
			$this->Page->create();
			// place at the end
			$numPages = $this->Page->find('count');
			$this->Page->data['Page']['index'] = $numPages;
			// set the current timestamp for creation and modification
			$this->Page->data['Page']['created'] = date('Y-m-d H:i:s');
			$this->Page->data['Page']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash('The page has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the page.');
		}

		$this->set('title_for_layout', 'Add Page');
	}

/**
 * Increment the index of the given page ID. This assumes the database is in a consistent state and that all indexes
 * are sequential. This essentially swaps the entry after the given index with the target entry.
 *
 * @param int $id The entry ID to increment the index of.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @return null
 */
	public function admin_incrementIndex($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid page.');
		}

		$target = $this->Page->findById($id);
		if (!$target) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid page.');
		}
		$index = $target['Page']['index'];

		// grab all pages
		$pages = $this->Page->find('all', array('order' => array('Page.index' => 'ASC')));

		// make sure we can actually increment
		if ($index + 1 < count($pages)) {
			// place the target at the end temporarily
			$target['Page']['index'] = count($pages);
			$this->Page->save($target);

			// move the next entry down
			$page = $pages[$index + 1];
			$page['Page']['index'] = $index;
			$this->Page->save($page);

			// and finally place the target in the correct spot
			$target['Page']['index'] = $index + 1;
			$target['Page']['modified'] = date('Y-m-d H:i:s');
			$this->Page->save($target);
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
	}

/**
 * Decrement the index of the given page ID. This assumes the database is in a consistent state and that all indexes
 * are sequential. This essentially swaps the entry before the given index with the target entry.
 *
 * @param int $id The entry ID to decrement the index of.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @throws MethodNotAllowedException Thrown if a GET request is made.
 * @return null
 */
	public function admin_decrementIndex($id = null) {
		// do not allow GET requests
		if ($this->request->is('get')) {
			throw new MethodNotAllowedException();
		}

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid page.');
		}

		$target = $this->Page->findById($id);
		if (!$target) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid page.');
		}
		$index = $target['Page']['index'];

		// make sure we can actually decrement
		if ($index > 0) {
			// grab all pages
			$pages = $this->Page->find('all', array('order' => array('Page.index' => 'ASC')));

			// place the target at the end temporarily
			$target['Page']['index'] = count($pages);
			$this->Page->save($target);

			// move the previous entry up
			$page = $pages[$index - 1];
			$page['Page']['index'] = $index;
			$this->Page->save($page);

			// and finally place the target in the correct spot
			$target['Page']['index'] = $index - 1;
			$target['Page']['modified'] = date('Y-m-d H:i:s');
			$this->Page->save($target);
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
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
			throw new NotFoundException('Invalid page.');
		}

		$page = $this->Page->findById($id);
		if (!$page) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid page.');
		}

		// only work for PUT requests
		if ($this->request->is(array('page', 'put'))) {
			// set the ID
			$this->Page->id = $id;
			// set the current timestamp for modification
			$this->Page->data['Page']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Page->save($this->request->data)) {
				$this->Session->setFlash('The page has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the page.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $page;
		}

		$this->set('title_for_layout', __('Edit Page - %s', $page['Page']['title']));
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
		if ($this->Page->delete($id)) {
			// reindex the entries
			$pages = $this->Page->find('all', array('order' => array('Page.index' => 'ASC')));
			$numPages = count($pages);
			for ($i = 0; $i < $numPages; $i++) {
				$page = $pages[$i];
				$page['Page']['index'] = $i;
				$this->Page->save($page);
			}

			$this->Session->setFlash('The page has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * The default index simply redirects to the homepage action.
 *
 * @return null
 */
	public function index() {
		return $this->redirect(array('action' => 'view'));
	}

/**
 * View the given page. If no page ID is given, the homepage is rendered.
 *
 * @param int|null $id The ID of the entry to view.
 * @throws NotFoundException Thrown if an entry with the given ID is not found.
 * @return null
 */
	public function view($id = null) {
		// get the homepage
		$home = $this->Page->find('first', array('order' => array('Page.index' => 'ASC')));

		// check if an ID was given -- if not, use the first one
		$page = (!$id) ? $home : $this->Page->findById($id);

		if (!$page) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid page.');
		}

		// store the entry
		$this->set('page', $page);
		$this->set('home', $home['Page']['id'] === $page['Page']['id']);
		$this->set('title_for_layout', $page['Page']['title']);
	}
}
