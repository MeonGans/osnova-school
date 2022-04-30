<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'content', 'order', 'folder_id', 'views'];

    public function folder()
    {
        $this->belongsTo(Folder::class);
    }

}
