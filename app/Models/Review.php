<?php

namespace App\Models;

use App\Abstract\Models\CustomModel;
use App\Builders\ReviewBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends CustomModel
{
    use HasFactory;

    protected $fillable = ['book_id', 'review', 'rating'];

    protected static function booted(): void
    {
        static::updated(fn (Review $review) => cache()->forget('book:' . $review->book_id));
        static::deleted(fn (Review $review) => cache()->forget('book:' . $review->book_id));
        static::created(fn (Review $review) => cache()->forget('book:' . $review->book_id));

    }

    public function newEloquentBuilder($query): Builder
    {
        return new ReviewBuilder($query);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
