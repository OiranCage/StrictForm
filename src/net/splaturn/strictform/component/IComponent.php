<?php

namespace net\splaturn\strictform\component;

interface IComponent{
	public function getName() : string;
	public function convertToJson() : array;
}