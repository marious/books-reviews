<?php

namespace App\Services;

use App\Models\Book;
use Illuminate\Database\Eloquent\Builder;

class BookService
{
    public function getBooks(?string $title = null, ?string $filter = null)
    {
        $booksQuery = Book::query()
            ->when($title, fn (Builder $query) => $query->title($title));

        $books = match ($filter) {
            'popular_last_month' => $booksQuery->popularLastMonth(),
            'popular_last_6months' => $booksQuery->popularLast6Months(),
            'highest_rated_last_month' => $booksQuery->highestRatedLastMonth(),
            'highest_rated_last_6months' => $booksQuery->highestRatedLast6Months(),
            default => $booksQuery->latest()->withAvgRating()->withReviewsCount()
        };

        $cacheKey = 'books:' . $title . ':' . $filter;
        return cache()->remember($cacheKey, 3600, fn () => $books->get());
    }

    public function getBook(int $id)
    {
        return cache()->remember(
            'book:' . $id,
            3600,
            fn () =>
            Book::with(['reviews' => fn ($query) => $query->latest()])
                ->withAvgRating()
                ->withReviewsCount()
                ->findOrFail($id)
        );
    }

}
