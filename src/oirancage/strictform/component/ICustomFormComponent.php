<?php

namespace oirancage\strictform\component;

use oirancage\strictform\component\exception\InvalidFormResponseException;

interface ICustomFormComponent extends IComponent{
	public function getType() : string;

	/**
	 * @throws InvalidFormResponseException when value type or value its self is invalid.
	 */
	public function validate(mixed $value) : void;
}