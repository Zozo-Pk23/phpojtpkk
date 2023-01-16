<?php

namespace App\Imports;

use App\Models\Post;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PostImport implements ToModel, WithValidation, WithHeadingRow
{
    /** 
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use Importable;

    public function rules(): array
    {
        return [
            'title' => ['required', 'max:50', 'unique:posts,title'],
            'description' => ['required'],
        ];
    }
    public function model(array $row)
    {
        return new Post([
            'title'     => $row['title'],
            'description'    => $row['description'],
            'status' => 1,
            'created_user_id' => Auth::user()->id,
            'updated_user_id' => Auth::user()->id,
        ]);
    }
}
