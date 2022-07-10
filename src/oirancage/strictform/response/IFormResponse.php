<?php

namespace oirancage\strictform\response;

use pocketmine\player\Player;

interface IFormResponse{
	public function getFrom() : Player;
}