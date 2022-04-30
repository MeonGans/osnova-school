<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'order', 'folder_id'];

    public function pages()
    {
        $this->hasMany(Page::class, 'folder_id');
    }

    public function links()
    {
        $this->hasMany(Link::class);
    }
}
