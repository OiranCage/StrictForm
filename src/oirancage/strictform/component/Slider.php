<?php

namespace oirancage\strictform\component;

use oirancage\strictform\component\exception\InvalidFormResponseException;

class Slider implements ICustomFormComponent{

	public function __construct(
		private string $name,
		private string $text,
		private float $min,
		private float $max,
		private ?float $step = null,
		private ?float $default = null
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		$json = [
			"type" => $this->getType(),
			"text" => $this->text,
			"min" => $this->min,
			"max" => $this->max,
		];
		if($this->step !== null){
			$json["step"] = $this->step;
		}
		if($this->default !== null){
			$json["default"] = $this->default;
		}
		return $json;
	}

	public function getType() : string{
		return "slider";
	}

	/**
	 * @inheritDoc
	 */
	public function validate(string|int|float|bool|null $value) : void{
		if(!is_int($value) && !is_float($value)){
			$type = gettype($value);
			throw new InvalidFormResponseException("type int or float is expected, $type is given.");
		}
		if($value < $this->min || $this->max < $value){
			throw new InvalidFormResponseException("value should be ranged in {$this->min} ~ {$this->max}, $value given.");
		}
	}
}