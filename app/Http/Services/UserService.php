<?php

namespace App\Http\Services;

use App\Contracts\Services\userInterface;
use App\Http\Dao\UserDao;

class UserService implements userInterface
{
    private $UserDao;
    public function __construct(UserDao $userDao)
    {
        $this->UserDao = $userDao;
    }
    public function save($request)
    {
        return $this->UserDao->save($request);
    }
    public function deleteuser($id)
    {
        return $this->UserDao->deleteuser($id);
    }

    public function searchuser($request)
    {
        return $this->UserDao->searchuser($request);
    }
}
