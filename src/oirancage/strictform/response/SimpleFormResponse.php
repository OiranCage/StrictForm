<?php

namespace oirancage\strictform\response;

use oirancage\strictform\component\Button;
use oirancage\strictform\component\exception\InvalidFormResponseException;
use pocketmine\player\Player;

class SimpleFormResponse{
	private int $validatedResponse;

	/**
	 * @phpstan-param Button[] $buttons
	 * @phpstan-param array    $validatedResponse
	 * @throws InvalidFormResponseException
	 */
	public function __construct(
		private Player $player,
		private array $buttons,
		int $rawResponse
	){
		self::validate($this->buttons, $rawResponse);
		$this->validatedResponse = $rawResponse;
	}

	/**
	 * @phpstan-param Button[] $components
	 * @phpstan-param array    $validatedResponse
	 * @throws InvalidFormResponseException
	 */
	private static function validate(array $buttons, int $rawResponse){
		if($rawResponse < 0 || count($buttons) <= $rawResponse){
			throw new InvalidFormResponseException("component part counts between server-side and client-side mismatched.");
		}
	}

	public function getFrom() : Player{
		return $this->player;
	}

	public function getSelectedButtonValue() : string{
		return $this->buttons[$this->validatedResponse]->getName();
	}
}