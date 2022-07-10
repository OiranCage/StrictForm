<?php

namespace net\splaturn\strictform\component;

use net\splaturn\strictform\component\exception\InvalidFormResponseException;

class Input implements ICustomFormComponent{

	public function __construct(
		private string $name,
		private string $text,
		private ?string $placeholder = null,
		private ?string $default = null
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		return [
			"type" => $this->getType(),
			"text" => $this->text,
			"placeholder" => $this->placeholder,
			"default" => $this->default
		];
	}

	public function getType() : string{
		return "input";
	}

	/**
	 * @inheritDoc
	 */
	public function validate(mixed $value) : void{
	}
}