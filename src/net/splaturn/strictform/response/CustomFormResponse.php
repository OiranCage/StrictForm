<?php

namespace net\splaturn\strictform\response;

use InvalidArgumentException;
use net\splaturn\strictform\component\Dropdown;
use net\splaturn\strictform\component\exception\InvalidFormResponseException;
use net\splaturn\strictform\component\ICustomFormComponent;
use net\splaturn\strictform\component\Input;
use net\splaturn\strictform\component\Slider;
use net\splaturn\strictform\component\StepSlider;
use net\splaturn\strictform\component\Toggle;
use pocketmine\player\Player;

class CustomFormResponse implements IFormResponse{

	private array $validatedResponse;

	/**
	 * @phpstan-param ICustomFormComponent[] $components
	 * @phpstan-param array                  $validatedResponse
	 * @throws InvalidFormResponseException
	 */
	public function __construct(
		private Player $player,
		private array $components,
		array $rawResponse
	){
		self::validate($this->components, $rawResponse);
		$this->validatedResponse = $rawResponse;
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
		$component = $this->components[$name] ?? null;
		if(!$component instanceof StepSlider){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $component->getOption($this->validatedResponse[$name])->getName();
	}

	public function getSliderValue(string $name) : int{
		$component = $this->components[$name] ?? null;
		if(!$component instanceof Slider){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $this->validatedResponse[$name];
	}

	public function getToggleValue(string $name) : bool{
		$component = $this->components[$name] ?? null;
		if(!$component instanceof Toggle){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $this->validatedResponse[$name];
	}

	public function getInputValue(string $name) : string{
		$component = $this->components[$name] ?? null;
		if(!$component instanceof Input){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $this->validatedResponse[$name];
	}

	public function getDropdownValue(string $name) : string{
		$component = $this->components[$name] ?? null;
		if(!$component instanceof Dropdown){
			throw new InvalidArgumentException("Wrong type response is detected.");
		}
		return $component->getOption($this->validatedResponse[$name])->getName();
	}
}