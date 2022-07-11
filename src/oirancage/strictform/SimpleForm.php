<?php

declare(strict_types = 1);

namespace oirancage\strictform;

use Closure;
use oirancage\strictform\component\Button;
use oirancage\strictform\component\exception\InvalidFormResponseException;
use oirancage\strictform\response\SimpleFormResponse;
use pocketmine\form\FormValidationException;
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
			if($this->onCloseCallback !== null){
				($this->onCloseCallback)($player);
			}
			return;
		}

		if(is_int($data)){
			try{
				$response = new SimpleFormResponse($player, $this->buttons, $data);
			}catch(InvalidFormResponseException $exception){
				if($this->onErrorCallback === null){
					throw new FormValidationException($exception->getMessage());
				}
				($this->onErrorCallback)($exception);
				return;
			}
			$callback = $this->onSuccessCallback;
			if($callback !== null){
				$callback($response);
			}
			return;
		}

		$type = gettype($data);
		$exception = new InvalidFormResponseException("type null or int is expected, $type given.");
		if($this->onErrorCallback === null){
			throw new FormValidationException($exception->getMessage());
		}
		($this->onErrorCallback)($player, $exception);
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
