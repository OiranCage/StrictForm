<?php

namespace oirancage\strictform\response;

use InvalidArgumentException;
use oirancage\strictform\component\Dropdown;
use oirancage\strictform\component\exception\InvalidFormResponseException;
use oirancage\strictform\component\ICustomFormComponent;
use oirancage\strictform\component\Input;
use oirancage\strictform\component\Slider;
use oirancage\strictform\component\StepSlider;
use oirancage\strictform\component\Toggle;
use pocketmine\player\Player;

class CustomFormResponse implements IFormResponse{

	/** @var array<string, mixed>  */
	private array $validatedResponse = [];
	/** @var array<string, ICustomFormComponent> */
	private array $componentMap = [];

	/**
	 * @phpstan-param array<int, ICustomFormComponent> $components
	 * @phpstan-param array                  $validatedResponse
	 * @throws InvalidFormResponseException
	 */
	public function __construct(
		private Player $player,
		private array $components,
		array $rawResponse
	){
		self::validate($this->components, $rawResponse);
		foreach($this->components as $key => $component){
			$this->componentMap[$component->getName()] = $component;
			$this->validatedResponse[$component->getName()] = $rawResponse[$key];
		}
	}

	/**
	 * @phpstan-param ICustomFormComponent[] $components
	 * @phpstan-param array                  $validatedResponse
	 * @throws InvalidFormResponseException
	 */
	private static function validate(array $components, array $rawResponse){
		if(count($components) !== count($rawResponse)){
			throw new InvalidFormResponseException("component part counts between server-side and client-side mismatched.");
		}

		foreach($components as $key => $component){
			$component->validate($rawResponse[$key] ?? null);
		}
	}

	public function getFrom() : Player{
		return $this->player;
	}

	public function getStepSliderValue(string $name) : string{
		$component = $this->componentMap[$name] ?? null;
		if(!$component instanceof StepSlider){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $component->getOption($this->validatedResponse[$name])->getName();
	}

	public function getSliderValue(string $name) : int|float{
		$component = $this->componentMap[$name] ?? null;
		if(!$component instanceof Slider){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $this->validatedResponse[$name];
	}

	public function getToggleValue(string $name) : bool{
		$component = $this->componentMap[$name] ?? null;
		if(!$component instanceof Toggle){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $this->validatedResponse[$name];
	}

	public function getInputValue(string $name) : string{
		$component = $this->componentMap[$name] ?? null;
		if(!$component instanceof Input){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return (string) $this->validatedResponse[$name];
	}

	public function getDropdownValue(string $name) : string{
		$component = $this->componentMap[$name] ?? null;
		if(!$component instanceof Dropdown){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $component->getOption($this->validatedResponse[$name])->getName();
	}
}