<?php

namespace oirancage\strictform\component;

use oirancage\strictform\component\exception\InvalidFormResponseException;

class Label implements ICustomFormComponent{

	public function __construct(
		private string $name,
		private string $text
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		return [
			"type" => $this->getType(),
			"text" => $this->text
		];
	}

	public function getType() : string{
		return "label";
	}

	public function validate(mixed $value) : void{
		if(!is_null($value)){
			$type = gettype($value);
			throw new InvalidFormResponseException("type null is expected, $type given.");
		}
	}
}