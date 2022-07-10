<?php

namespace oirancage\strictform\component;

class StringEnumOption{

	public function __construct(
		private string $name,
		private string $text
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function getText() : string{
		return $this->text;
	}
}