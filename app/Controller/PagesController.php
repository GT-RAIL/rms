<?php
App::import('Controller', 'Rms');

class PagesController extends RmsController {
	public $helpers = array('Html', 'Form');

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
}