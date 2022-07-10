<?php

declare(strict_types = 1);

namespace oirancage\strictform;

use Closure;
use oirancage\strictform\component\exception\InvalidFormResponseException;
use oirancage\strictform\response\ModalFormResponse;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class ModalForm implements Form{

	private ?Closure $onSuccessCallback = null;
	private ?Closure $onErrorCallback = null;
	private ?Closure $onCloseCallback = null;

	public function __construct(
		private string $title,
		private string $content,
		private string $button1,
		private string $button2
	){
	}

	public function handleResponse(Player $player, $data) : void{
		if(is_null($data)){
			$callback = $this->onCloseCallback;
			if($callback !== null){
				$callback($player);
			}
			return;
		}

		if(is_bool($data)){
			$response = new ModalFormResponse($player, $data);
			$callback = $this->onSuccessCallback;
			if($callback !== null){
				$callback($response);
			}
			return;
		}

		$callback = $this->onErrorCallback;
		if($callback !== null){
			$type = gettype($data);
			$callback($player, new InvalidFormResponseException("type null or bool is expected, $type given."));
		}
	}

	public function onSuccess(Closure $callback) : void{
		Utils::validateCallableSignature(function(ModalFormResponse $response) : void{}, $callback);
		$this->onSuccessCallback = $callback;
	}

	/**
	 * @phpstan-param Closure(Player $player, InvalidFormResponseException $exception):void $callback
	 */
	public function onValidationError(Closure $callback) : void{
		Utils::validateCallableSignature(function(Player $player, InvalidFormResponseException $exception) : void{}, $callback);
		$this->onErrorCallback = $callback;
	}

	/**
	 * @phpstan-param Closure(Player $response):void $callback
	 */
	public function onClose(Closure $callback) : void{
		Utils::validateCallableSignature(function(Player $player) : void{}, $callback);
		$this->onCloseCallback = $callback;
	}

	public function jsonSerialize(){
		return [
			"type" => "modal",
			"title" => $this->title,
			"content" => $this->content,
			"button1" => $this->button1,
			"button2" => $this->button2
		];
	}
}
