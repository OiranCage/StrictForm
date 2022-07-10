<?php

declare(strict_types = 1);

namespace oirancage\strictform;

use Closure;
use oirancage\strictform\component\exception\InvalidFormResponseException;
use oirancage\strictform\component\ICustomFormComponent;
use oirancage\strictform\response\CustomFormResponse;
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
			$callback = $this->onCloseCallback;
			if($callback !== null){
				$callback($player);
			}
			return;
		}

		if(is_array($data)){
			try{
				$response = new CustomFormResponse($player, $this->components, $data);
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
			$callback($player, new InvalidFormResponseException("type null or array is expected, $type given."));
		}
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
