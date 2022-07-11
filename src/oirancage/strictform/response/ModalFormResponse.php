<?php

namespace oirancage\strictform\response;

use oirancage\strictform\component\Button;
use pocketmine\player\Player;

class ModalFormResponse implements IFormResponse{
	private bool $validatedResponse;

	/**
	 * @phpstan-param Button[] $buttons
	 * @phpstan-param array    $validatedResponse
	 */
	public function __construct(
		private Player $player,
		bool $rawResponse
	){
		$this->validatedResponse = $rawResponse;
	}


	public function getFrom() : Player{
		return $this->player;
	}

	public function getSelectedValue() : bool{
		return $this->validatedResponse;
	}
}