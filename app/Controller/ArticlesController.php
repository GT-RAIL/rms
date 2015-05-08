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
 * @version		2.0.9
 * @package		app.Controller
 */
class ArticlesController extends AppController {

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
 * The admin index action lists information about all articles. This allows the admin to add, edit, or delete
 * entries.
 *
 * @return null
 */
	public function admin_index() {
		// load the pages list
		$pages = $this->Article->Page->find('all', array('order' => array('Page.index' => 'ASC')));
		$this->set('pages', $pages);

		// grab all the entries
		$this->set(
			'articles',
			$this->Article->find('all', array('order' => array('Article.page_id, Article.index' => 'ASC')))
		);
	}

/**
 * The admin add action. This will allow the admin to create a new entry.
 *
 * @return null
 */
	public function admin_add() {
		// load the pages list
		$pages = $this->Article->Page->find('list', array('order' => array('Page.index' => 'ASC')));
		$this->set('pages', $pages);

		// only work for POST requests
		if ($this->request->is('post')) {
			// create a new entry
			$this->Article->create();
			// place at the end
			$numArticles = $this->Article->find(
				'count',
				array('conditions' => array('Article.page_id' => $this->request->data['Article']['page_id']))
			);
			$this->Article->data['Article']['index'] = $numArticles;
			// set the current timestamp for creation and modification
			$this->Article->data['Article']['created'] = date('Y-m-d H:i:s');
			$this->Article->data['Article']['modified'] = date('Y-m-d H:i:s');
			// attempt to save the entry
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash('The article has been saved.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to add the article.');
		}

		$this->set('title_for_layout', 'Add Article');
	}

/**
 * Increment the index of the given article ID. This assumes the database is in a consistent state and that all
 * indexes are sequential. This essentially swaps the entry after the given index with the target entry.
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
			throw new NotFoundException('Invalid article.');
		}

		$target = $this->Article->findById($id);
		if (!$target) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid article.');
		}
		$index = $target['Article']['index'];

		// grab all articles for the same page
		$articles = $this->Article->find(
			'all',
			array(
				'conditions' => array('Article.page_id' => $target['Article']['page_id']),
				'order' => array('Article.index' => 'ASC')
			)
		);

		// make sure we can actually increment
		if ($index + 1 < count($articles)) {
			// place the target at the end temporarily
			$target['Article']['index'] = count($articles);
			$this->Article->save($target);

			// move the next entry down
			$article = $articles[$index + 1];
			$article['Article']['index'] = $index;
			$this->Article->save($article);

			// and finally place the target in the correct spot
			$target['Article']['index'] = $index + 1;
			$target['Article']['modified'] = date('Y-m-d H:i:s');
			$this->Article->save($target);
		}

		// return to the index
		return $this->redirect(array('action' => 'index'));
	}

/**
 * Decrement the index of the given article ID. This assumes the database is in a consistent state and that all
 * indexes are sequential. This essentially swaps the entry before the given index with the target entry.
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
			throw new NotFoundException('Invalid article.');
		}

		$target = $this->Article->findById($id);
		if (!$target) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid article.');
		}
		$index = $target['Article']['index'];

		// make sure we can actually decrement
		if ($index > 0) {
			// grab all articles for the same page
			$articles = $this->Article->find(
				'all',
				array(
					'conditions' => array('Article.page_id' => $target['Article']['page_id']),
					'order' => array('Article.index' => 'ASC')
				)
			);

			// place the target at the end temporarily
			$target['Article']['index'] = count($articles);
			$this->Article->save($target);

			// move the previous entry up
			$article = $articles[$index - 1];
			$article['Article']['index'] = $index;
			$this->Article->save($article);

			// and finally place the target in the correct spot
			$target['Article']['index'] = $index - 1;
			$target['Article']['modified'] = date('Y-m-d H:i:s');
			$this->Article->save($target);
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
		// load the pages list
		$pages = $this->Article->Page->find('list', array('order' => array('Page.index' => 'ASC')));
		$this->set('pages', $pages);

		if (!$id) {
			// no ID provided
			throw new NotFoundException('Invalid article.');
		}

		$article = $this->Article->findById($id);
		if (!$article) {
			// no valid entry found for the given ID
			throw new NotFoundException('Invalid article.');
		}

		// only work for PUT requests
		if ($this->request->is(array('article', 'put'))) {
			// set the ID
			$this->Article->id = $id;
			// set the current timestamp for modification
			$this->Article->data['Article']['modified'] = date('Y-m-d H:i:s');

			// check if we are changing pages
			if ($article['Article']['page_id'] !== $this->request->data['Article']['page_id']) {
				// grab all articles for the same page
				$index = $this->Article->find(
					'count',
					array(
						'conditions' => array('Article.page_id' => $this->request->data['Article']['page_id']),
					)
				);
				$this->Article->data['Article']['index'] = $index;
			}

			// attempt to save the entry
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash('The article has been updated.');
				return $this->redirect(array('action' => 'index'));
			}
			$this->Session->setFlash('Unable to update the article.');
		}

		// store the entry data if it was not a PUT request
		if (!$this->request->data) {
			$this->request->data = $article;
		}

		$this->set('title_for_layout', __('Edit Article - %s', $article['Article']['title']));
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

		// check the page this article belongs to
		$article = $this->Article->findById($id);

		// attempt to delete the entry
		if ($this->Article->delete($id)) {
			// reindex the entries
			$articles = $this->Article->find(
				'all',
				array(
					'conditions' => array('Article.page_id' => $article['Article']['page_id']),
					'order' => array('Article.index' => 'ASC')
				)
			);
			$numArticles = count($articles);
			for ($i = 0; $i < $numArticles; $i++) {
				$article = $articles[$i];
				$article['Article']['index'] = $i;
				$this->Article->save($article);
			}

			$this->Session->setFlash('The article has been deleted.');
			return $this->redirect(array('action' => 'index'));
		}
	}

/**
 * The default index simply redirects to the page's homepage action.
 *
 * @return null
 */
	public function index() {
		return $this->redirect(array('controller' => 'pages', 'action' => 'view'));
	}
}
