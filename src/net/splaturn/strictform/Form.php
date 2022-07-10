<?php

namespace net\splaturn\strictform;

use Closure;
use pocketmine\form\Form as IForm;

interface Form extends IForm{
	public function onSuccess(Closure $callback) : void;
	public function onValidationError(Closure $callback) : void;
	public function onClose(Closure $callback) : void;
}