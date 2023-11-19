<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $primaryKey = 'imdbID';

    public $incrementing = false;

    protected $fillable = [
        'imdbID',
        'title',
        'type',
        'releasedDate',
        'year',
        'posterUrl',
        'genre',
        'runtime',
        'country',
        'imdbRating',
        'imdbVotes',
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'imdbID', 'imdbID');
    }

    protected $casts = [
        'releasedDate' => 'date',
    ];
}
