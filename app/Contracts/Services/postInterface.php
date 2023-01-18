<?php

namespace App\Contracts\Services;

interface postInterface
{
    public function create($request);
    public function search($request);
    public function edit($id);
    public function delete($id);
    public function update($id, $request);
}
