<?php

namespace oirancage\strictform\component;

class UrlImage implements IImage{

	public function __construct(
		private string $imageUrl
	){
	}

	public function getType() : string{
		return "url";
	}

	public function getData() : string{
		return $this->imageUrl;
	}
}