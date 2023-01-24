<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
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
        $type = Auth::user()->type;
        $loginUserId = Auth::user()->id;
        if ($type == 0) {
            $posts = DB::table('posts')
                ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at',)
                ->join('users', 'users.id', '=', 'posts.created_user_id')
                ->where('posts.delete_flag', '=', 0)
                ->get();
            return $posts;
        } else {
            $posts = DB::table('posts')
                ->select('posts.id', 'posts.title', 'posts.description', 'users.name As pname', 'posts.created_at',)
                ->join('users', 'users.id', '=', 'posts.created_user_id')
                ->where('posts.delete_flag', '=', 0)
                ->where('posts.created_user_id', '=', $loginUserId)
                ->get();
            return $posts;
        }
    }
    public function headings(): array
    {
        return ["ID", "TITLE", "DESCRIPTION", "POSTED USER", "POSTED DATE"];
    }
}
