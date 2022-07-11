<?php

declare(strict_types = 1);

namespace oirancage\strictform;

use Closure;
use oirancage\strictform\component\exception\InvalidFormResponseException;
use oirancage\strictform\component\ICustomFormComponent;
use oirancage\strictform\response\CustomFormResponse;
use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class CustomForm implements Form {
	private ?Closure $onSuccessCallback = null;
	private ?Closure $onErrorCallback = null;
	private ?Closure $onCloseCallback = null;

	/**
	 * @phpstan-param ICustomFormComponent[] $components
	 */
	public function __construct(
		private string $title,
		private array $components
	){
	}

	public function handleResponse(Player $player, $data) : void{
		if(is_null($data)){
			if($this->onCloseCallback !== null){
				($this->onCloseCallback)($player);
			}
			return;
		}

		if(is_array($data)){
			try{
				$response = new CustomFormResponse($player, $this->components, $data);
			}catch(InvalidFormResponseException $exception){
				if($this->onErrorCallback === null){
					throw new FormValidationException($exception->getMessage());
				}
				($this->onErrorCallback)($exception);
				return;
			}
			if($this->onSuccessCallback === null){
				($this->onSuccessCallback)($response);
			}
			return;
		}

		$type = gettype($data);
		$exception = new InvalidFormResponseException("type null or array is expected, $type given.");
		if($this->onErrorCallback === null){
			throw new FormValidationException($exception->getMessage());
		}
		($this->onErrorCallback)($player, $exception);
	}

	public function jsonSerialize(){
		return [
			"type" => "custom_form",
			"title" => $this->title,
			"content" => array_map(fn(ICustomFormComponent $component) => $component->convertToJson(), $this->components)
		];
	}


	/**
	 * @phpstan-param Closure(CustomFormResponse $response):void $callback
	 */
	public function onSuccess(Closure $callback) : void{
		Utils::validateCallableSignature(function(CustomFormResponse $response) : void{}, $callback);
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
}
