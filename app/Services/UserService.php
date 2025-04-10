<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
   protected $UserRepository;

    public function register(array $data)
    {
        $data['Role'] = 'Student';
        return $this->UserRepository->createUser($data);
    }

}
