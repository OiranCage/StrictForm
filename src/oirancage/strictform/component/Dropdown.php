<?php

namespace oirancage\strictform\component;

use oirancage\strictform\component\exception\InvalidFormResponseException;

class Dropdown implements ICustomFormComponent{

	/**
	 * @phpstan-param StringEnumOption[]    $options
	 */
	public function __construct(
		private string $name,
		private string $text,
		private array $options,
		private ?int $default
	){
	}

	public function getName() : string{
		return $this->name;
	}

	public function convertToJson() : array{
		return [
			"type" => $this->getType(),
			"text" => $this->text,
			"options" => array_map(fn(StringEnumOption $option) : string => $option->getText(), $this->options),
			"default" => $this->default
		];
	}

	public function getType() : string{
		return "dropdown";
	}

	/**
	 * @inheritDoc
	 */
	public function validate(mixed $value) : void{
		if(!is_int($value)){
			$type = gettype($value);
			throw new InvalidFormResponseException("type int is expected, $type given.");
		}
		if($value < 0 || count($this->options) <= $value){
			throw new InvalidFormResponseException("$value is out of range");
		}
	}

	public function getOption(int $index) : StringEnumOption{
		return $this->options[$index];
	}
}