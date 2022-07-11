<?php

namespace oirancage\strictform;

use Closure;
use pocketmine\form\Form as IForm;

interface Form extends IForm{
	public function onSuccess(Closure $callback) : self;
	public function onValidationError(Closure $callback) : self;
	public function onClose(Closure $callback) : self;
}