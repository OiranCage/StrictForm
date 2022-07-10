<?php

namespace net\splaturn\strictform\component;

interface IImage{
	public function getType() : string;
	public function getData() : string;
}