<?php

namespace App\Controllers;

use Appkita\RestController;

class Home extends RestController
{
	protected $authentication = 'TOKEN';
	public function index()
	{
		echo 'test';
	}
}
