<?php
abstract class RmsController extends AppController {

	public function beforeFilter()  {
		// set the menu items
		$this->setMenuItems();
	}

	private function setMenuItems() {
		// set the main menu for the pages
		$this->loadModel('Page');
		$this->set('pages', $this->Page->find('all'), array('order' => array('Page.index' => 'ASC')));
	}
}