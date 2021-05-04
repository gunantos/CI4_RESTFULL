<?php

namespace App\Entities;

use CodeIgniter\Entity;

class User extends Entity
{
    protected $attributes = [
        'id'=>null,
        'name'=>null,
        'email'=>null,
        'password'=>null,
        'facebook'=>null,
        'google'=>null,
        'github'=>null,
        'telp'=>null,
        'block'=>0,
        'activation'=>null,
        'created_at'=>null,
        'updated_at'=>null
    ];

    public function setPassword(string $pass) {
        $this->attributes['password'] = \password_hash($pass, PASSWORD_BCRYPT);
        return $this;
    }

    public function setCreatedAt(string $dateString) {
        $this->attributes['created_at'] = new Time($dateString, 'UTC');
        return $this;
    }

    public function getCreatedAt(string $format = 'Y-m-d H:i:s')
    {
        $this->attributes['created_at'] = $this->mutateDate($this->attributes['created_at']);

        $timezone = $this->timezone ?? app_timezone();

        $this->attributes['created_at']->setTimezone($timezone);

        return $this->attributes['created_at']->format($format);
    }
}