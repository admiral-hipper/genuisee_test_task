<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = ['source', 'value'];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'imdbID', 'imdbID');
    }
}
