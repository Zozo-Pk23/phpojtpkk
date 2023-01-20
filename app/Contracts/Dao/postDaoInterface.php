<?php

namespace App\Contracts\Dao;

interface postDaoInterface
{
    public function index();
    public function create($bullet);
    public function edit($id);
    public function delete($id);
    public function update($id,$request);
}
