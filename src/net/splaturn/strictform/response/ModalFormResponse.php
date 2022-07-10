<?php

namespace net\splaturn\strictform\response;

use net\splaturn\strictform\component\Button;
use pocketmine\player\Player;

class ModalFormResponse implements IFormResponse{
	private int $validatedResponse;

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

	public function getSelectedValue() : string{
		return $this->validatedResponse;
	}
}