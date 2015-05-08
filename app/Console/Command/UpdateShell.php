<?php

/**
 * RMS Update Shell
 *
 * The update shell will update the RMS to the latest version.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.1
 * @version		2.0.9
 * @package		app.Console.Command
 */
class UpdateShell extends AppShell {

/**
 * The used models for the shell.
 *
 * @var array
 */
	public $uses = array('Setting');

/**
 * The main function for the shell.
 *
 * @return null
 */
	public function main() {
		$complete = false;

		while (!$complete) {
			// load the site settings
			$setting = $this->Setting->findById(Setting::$default);
			$this->out(__('Current RMS Version: %s', $setting['Setting']['version']));

			// check the updater
			$function = __('update%s', str_replace('.', '_', $setting['Setting']['version']));
			if (method_exists($this, $function)) {
				$this->$function();
			} else {
				$complete = true;
			}
		}
	}

/**
 * Update from version 2.0.0 to version 2.0.1.
 *
 * @return null
 */
	public function update2_0_0() {
		$this->out(' + Updating to RMS version 2.0.1...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.1'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.1 to version 2.0.2.
 *
 * @return null
 */
	public function update2_0_1() {
		$this->out(' + Updating to RMS version 2.0.2...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.2'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.2 to version 2.0.3.
 *
 * @return null
 */
	public function update2_0_2() {
		$this->out(' + Updating to RMS version 2.0.3...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.3'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.3 to version 2.0.4.
 *
 * @return null
 */
	public function update2_0_3() {
		$this->out(' + Updating to RMS version 2.0.4...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.4'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.4 to version 2.0.5.
 *
 * @return null
 */
	public function update2_0_4() {
		$this->out(' + Updating to RMS version 2.0.5...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.5'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.5 to version 2.0.6.
 *
 * @return null
 */
	public function update2_0_5() {
		$this->out(' + Updating to RMS version 2.0.6...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.6'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.6 to version 2.0.7.
 *
 * @return null
 */
	public function update2_0_6() {
		$this->out(' + Updating to RMS version 2.0.7...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.7'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.7 to version 2.0.8.
 *
 * @return null
 */
	public function update2_0_7() {
		$this->out(' + Updating to RMS version 2.0.8...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.8'));
		$this->Setting->save($data, false);
	}

/**
 * Update from version 2.0.8 to version 2.0.9.
 *
 * @return null
 */
	public function update2_0_8() {
		$this->out(' + Updating to RMS version 2.0.9...');
		// no database changes, just tick the version
		$data = array('Setting' => array('id' => Setting::$default, 'version' => '2.0.9'));
		$this->Setting->save($data, false);
	}
}
