<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'title', 'description', 'created_user_id', 'created_at', 'status', 'delete_flag', 'updated_user_id', 'deleted_at', 'updated_at'
    ];
}
