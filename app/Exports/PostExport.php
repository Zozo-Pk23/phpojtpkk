<?php

namespace App\Exports;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PostExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // return Post::select('title', 'description', 'created_user-d', 'created_at')
        //     //->join('users', 'users.id', '=', 'created_user_id')
        //     ->get();
        $posts = DB::table('posts')
            ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at',)
            ->join('users', 'users.id', '=', 'posts.created_user_id')
            ->get();
        return $posts;
    }
    public function headings(): array
    {
        return ["ID","TITLE", "DESCRIPTION", "POSTED USER", "POSTED DATE"];
    }
}
