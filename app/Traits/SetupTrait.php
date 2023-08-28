<?php

namespace App\Traits;

trait SetupTrait
{
	protected $locationRegisterParams = [
		"name"
	];

	protected $siteRegisterParams = [
		"name",
		"location_uid",
		"ct"
	];

	protected $siteSourceParams = [
		"key",
		"unit_serial_number",
		"location_uid",
		"ct"
	];

}
