<?php

namespace App\Contracts\Dao;

interface userDaoInterface
{
    public function save($request);
    public function deleteuser($id);
    public function searchuser($request);
    public function changepasswordscreen($id);
    public function updatepassword($id, $request);
}
