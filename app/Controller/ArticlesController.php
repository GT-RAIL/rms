<?php
/**
 * Content Articles Controller
 *
 * Content articles contain information that is displayed on a given page. Each page has a number of content articles.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.0
 * @package		app.Controller
 */
class ArticlesController extends AppController {

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
	 * The admin index page lists information about all articles. This allows the admin to add, edit, or delete entries.
	 */
	public function admin_index() {
		// grab all the entries
		$this->set(
			'articles',
			$this->Article->find('all', array('order' => array('Article.page_id, Article.index' => 'ASC'))));
	}

	/**
	 * The admin add page. This will allow the admin to create a new entry.
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
	}

	/**
	 * Increment the index of the given page ID. This assumes the database is in a consistent state and that all IDs are
	 * sequential. This essentially swaps the entry after the given index with the target entry.
	 *
	 * @param int $id The entry ID to increment the index of.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
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

	/**
	 * Decrement the index of the given page ID. This assumes the database is in a consistent state and that all IDs are
	 * sequential. This essentially swaps the entry before the given index with the target entry.
	 *
	 * @param int $id The entry ID to decrement the index of.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
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

	/**
	 * The admin edit page. This allows the admin to edit an existing entry.
	 *
	 * @param int $id The ID of the entry to edit.
	 * @throws NotFoundException Thrown if an entry with the given ID is not found.
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
		if ($this->request->is(array('role', 'put'))) {
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
	}

	/**
	 * The admin delete page. This allows the admin to delete an existing entry.
	 *
	 * @param int $id The ID of the entry to delete.
	 * @throws MethodNotAllowedException Thrown if a GET request is made.
	 */
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
