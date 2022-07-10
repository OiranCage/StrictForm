<?php

namespace net\splaturn\strictform\component;

use net\splaturn\strictform\component\exception\InvalidFormResponseException;

class Toggle implements ICustomFormComponent{

	public function __construct(
		private string $name,
		private string $text,
		private ?bool $default = null,
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		$json = [
			"type" => $this->getType(),
			"text" => $this->text,
		];
		if($this->default !== null){
			$json["default"] = $this->default;
		}
		return $json;
	}

	public function getType() : string{
		return "toggle";
	}

	public function validate(mixed $value) : void{
		if(!is_bool($value)){
			$type = gettype($value);
			throw new InvalidFormResponseException("value should be bool, $type is given.");
		}
	}
}