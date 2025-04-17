<?php

namespace App\Models;

use App\Models\Genre; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class GenreBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id', 
        'genre_id'
    ];

    public function genre(): HasMany
    {
        return $this->HasMany(Genre::class, 'id', 'genre_id');
    }
}
