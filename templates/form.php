<?php

/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/13/13
 * Time: 9:06 PM
 * Licenced under the GPL v3
 */

/*
 * Changed! Uses objects. The caller must sent a class. This is because the input will be generated
 * with a type, a name and a display field (to show to the user). Also if other attributes need to be added,
 * this can be done easily in the class. E.g. if a new id field is required
 */

define('LB', '<br />'); // Line break definition

class InputField
{
	private $inputType; // Once the values are set, they should not be changed
	private $fieldName;
	private $labelName;
	private $fieldValue;
	public $formatting; // Change to private
	private $_MaxSize;

	public function __construct($type, $name, $display=null, $value=null, $formatting=null, $max_size=null) {
		$this->inputType = $type;
		$this->fieldName = $name;
		$this->labelName = $display;
		$this->fieldValue = $value;
		$this->formatting = $formatting;
		$this->_MaxSize = $max_size;
	}
	public function __get($value) {
		return $this->render();
	}
	public function render() {
		$str =  $this->labelName;
		if(!isset($this->formatting)) {
			$str .= ': ';
		}
		$str.= '<input type="' . $this->inputType .
			'" name="' . $this->fieldName;

		if (isset($this->fieldValue)) {
			$str .= '" value="' . $this->fieldValue;
		}
		if (isset($this->_MaxSize)) {
			$str .= '" maxlength="' . $this->_MaxSize;
		}
		$str .= '" />' . "\n";
		return $str;
	}
}

class Select
{
	private $_SelectOptions;
	private $_SelectName;
	private $_ItemSelected;

	public function __construct($select_name, $options, $display=null, $selected=null) {
		if(is_array($options)) {
			$this->_SelectName = $select_name;
			$this->_SelectOptions = $options;
			$this->_Display = $display;
			$this->_ItemSelected = $selected;
		} else {
			die ("Options must be a relational array");
		}
	}

	public function __get($value) {
		return $this->render();
	}

	public function render() {

		$str = $this->_Display;
		$str .= '<select name="' . $this->_SelectName . '" size="1">';

		foreach($this->_SelectOptions as $value => $item) {
			if($this->_ItemSelected == $value)
				$str .= '<option value="' . $value . '" selected>' . $item . '</option>';
			else
				$str .= '<option value="' . $value . '">' . $item . '</option>';
		}

		$str .= '</select>';

		return $str;
	}

}

class Form
{
	private $inputFields;
	private $formAction;
	private $method;

	function __construct($input_fields, $form_action, $method="POST") {
		$this->inputFields = $input_fields;
		$this->formAction = $form_action;
		$this->method = $method;
	}
	public function __get($value) {
		return $this->render();
	}
	public function render() {
		$form = '<form action="' . $this->formAction . '" method="' . $this->method . '" >';

		if(is_array($this->inputFields)) {
			foreach ($this->inputFields as $each_object) {
				$form .= "\n<div>" . $each_object->render() . '</div>' . LB;
			}
		} elseif (is_object($this->inputFields)) {
			$form .= $this->inputFields->render() . LB;
		} elseif (is_string($this->inputFields)) {
			$form .= $this->inputFields . LB;
		} else {
			die("{$this->inputFields} must be an object instance of InputField");
		}

		$form .= '</form>';
		return $form;

	}
}

