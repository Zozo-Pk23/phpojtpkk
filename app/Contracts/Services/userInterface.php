<?php

namespace App\Contracts\Services;

interface userInterface
{
    public function save($request);
    public function deleteuser($id);
    public function searchuser($request);
}