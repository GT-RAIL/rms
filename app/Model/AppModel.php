<?php

App::uses('Model', 'Model');

/**
 * Main Application Model
 *
 * The application model base class contains useful functions and definitions for all models in the RMS.
 *
 * @author		Russell Toris - rctoris@wpi.edu
 * @copyright	2014 Worcester Polytechnic Institute
 * @link		https://github.com/WPI-RAIL/rms
 * @since		RMS v 2.0.0
 * @version		2.0.9
 * @package		app.Model
 */
class AppModel extends Model {

/**
 * Check if a field is equal to another. This is useful for password validations.
 *
 * @param string $check The name of the field to check.
 * @param string $otherField The name of the other field to check with the first one.
 * @return bool Returns if the two fields have equal contents.
 */
	public function equalToField($check, $otherField) {
		$name = '';
		foreach ($check as $key => $value) {
			$name = $key;
			break;
		}
		return $this->data[$this->name][$otherField] === $this->data[$this->name][$name];
	}
}
