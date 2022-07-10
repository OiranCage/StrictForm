<?php

namespace net\splaturn\strictform\component\exception;

/**
 * throws when form response from client is invalid.
 * e.g. form response "true" for custom form,
 *      "array" or "int over amount of form elements" for simple form.
 */
class InvalidFormResponseException extends \Exception{

}