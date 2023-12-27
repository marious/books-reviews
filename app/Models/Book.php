<?php

namespace App\Models;

use App\Abstract\Models\CustomModel;
use App\Builders\BookBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends CustomModel
{
    use HasFactory;

    protected $fillable = ['title', 'author'];

    protected static function booted()
    {
        static::updated(fn (Book $book) => cache()->forget('book:' . $book->id));
        static::deleted(fn (Book $book) => cache()->forget('book:' . $book->id));
    }

    public function newEloquentBuilder($query): Builder
    {
        return new BookBuilder($query);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
}
