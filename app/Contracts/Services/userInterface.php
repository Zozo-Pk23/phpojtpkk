<?php

namespace App\Contracts\Services;

interface userInterface
{
    public function index();
    public function save($request);
    public function deleteuser($id);
    public function updatepassword($id, $request);
    public function findUserById($id);
    public function updateProfile($id, $request);
}
