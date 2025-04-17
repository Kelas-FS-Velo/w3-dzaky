<?php

namespace App\Models;

use App\Models\Genre; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\belongsToMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'author', 'available'];

    public function genre()
    {
        return $this->belongsToMany(Genre::class, 'genre_books', 'book_id', 'genre_id');
    }


}
