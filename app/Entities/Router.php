<?php

namespace App\Entities;

use CodeIgniter\Entity;

class Router extends Entity
{
    protected $attributes = [
        'id'=>null,
        'path'=>null,
        'method'=>null,
        'controller'=>null,
        'filter'=>null,
        'authentication'=>null
    ];
}