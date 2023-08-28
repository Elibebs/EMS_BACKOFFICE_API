<?php

namespace App\Traits;

trait ClientTrait
{
	protected $clientRegisterParams = [
		"name",
		"email",
		"phone_number",
		"short_name"
	];

	protected $clientLogoUpload = [
		"image",
	];
}
