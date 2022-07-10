<?php

declare(strict_types = 1);

namespace net\splaturn\strictform;

use Closure;
use net\splaturn\strictform\component\Button;
use net\splaturn\strictform\component\exception\InvalidFormResponseException;
use net\splaturn\strictform\response\SimpleFormResponse;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class SimpleForm implements Form {

	private ?Closure $onSuccessCallback = null;
	private ?Closure $onErrorCallback = null;
	private ?Closure $onCloseCallback = null;

	/**
	 * @phpstan-param Button[] $buttons
	 */
	public function __construct(
		private string $title,
		private string $content,
		private array $buttons
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

		if(is_int($data)){
			try{
				$response = new SimpleFormResponse($player, $this->buttons, $data);
			}catch(InvalidFormResponseException $exception){
				$callback = $this->onErrorCallback;
				if($callback !== null){
					$callback($player, $exception);
				}
				return;
			}
			$callback = $this->onSuccessCallback;
			if($callback !== null){
				$callback($response);
			}
			return;
		}

		$callback = $this->onErrorCallback;
		if($callback !== null){
			$type = gettype($data);
			$callback($player, new InvalidFormResponseException("type null or int is expected, $type given."));
		}
	}

	/**
	 * @phpstan-param Closure(SimpleFormResponse $response):void $callback
	 */
	public function onSuccess(Closure $callback) : void{
		Utils::validateCallableSignature(function(SimpleFormResponse $response) : void{}, $callback);
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
			"type" => "form",
			"title" => $this->title,
			"content" => $this->content,
			"buttons" => array_map(fn(Button $button) : array => $button->convertToJson(), $this->buttons)
		];
	}
}
