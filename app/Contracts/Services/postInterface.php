<?php

namespace App\Contracts\Services;

interface postInterface
{
    public function index();
    public function create($request);
    public function edit($id);
    public function delete($id);
    public function update($id, $request);
}
