<?php

namespace net\splaturn\strictform\component;

class Button implements IComponent{

	public function __construct(
		private string $name,
		private string $text,
		private ?IImage $image = null
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		$json = [
			"text" => $this->text
		];
		if($this->image !== null){
			$json["image"] = [
				"type" => $this->image->getType(),
				"data" => $this->image->getData()
			];
		}
		return $json;
	}
}