<?php

namespace oirancage\strictform\component;

class ResourcePackImage implements IImage{

	public function __construct(
		private string $imagePath
	){
	}

	public function getType() : string{
		return "path";
	}

	public function getData() : string{
		return $this->imagePath;
	}
}