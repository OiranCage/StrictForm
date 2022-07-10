<?php

namespace net\splaturn\strictform\response;

use pocketmine\player\Player;

interface IFormResponse{
	public function getFrom() : Player;
}