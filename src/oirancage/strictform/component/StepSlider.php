<?php

namespace oirancage\strictform\component;

use oirancage\strictform\component\exception\InvalidFormResponseException;

class StepSlider implements ICustomFormComponent{

	/**
	 * @phpstan-param StringEnumOption[] $options
	 */
	public function __construct(
		private string $name,
		private string $text,
		private array $options,
		private ?int $default = null
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		$json = [
			"type" => $this->getType(),
			"text" => $this->text,
			"options" => array_map(fn(StringEnumOption $option) : string => $option->getText(), $this->options)
		];

		if($this->default !== null){
			$json["default"] = $this->default;
		}

		return $json;
	}

	public function getType() : string{
		return "step_slider";
	}

	/**
	 * @inheritDoc
	 */
	public function validate(mixed $value) : void{
		if(!is_int($value)){
			$type = gettype($value);
			throw new InvalidFormResponseException("type int is expected, $type given");
		}

		if($value < 0 || count($this->options) <= $value){
			throw new InvalidFormResponseException("$value is out of range.");
		}
	}

	public function getOption(int $index) : StringEnumOption{
		return $this->options[$index];
	}
}