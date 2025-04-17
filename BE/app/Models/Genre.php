<?php

namespace App\Models;

use App\Models\GenreBook; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Genre extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function genrebook(): BelongsTo
    {
        return $this->BelongsTo(GenreBook::class, 'genre_id', 'id');
    }
}
