<?php

namespace App\Services;

use App\DataTransferObject\BookReviewDto;
use App\Models\Book;

class BookReviewService
{
    public function makeReview(Book $book, BookReviewDto $bookReviewDto)
    {
        $book->reviews()->create(['review' => $bookReviewDto->review, 'rating' => $bookReviewDto->rating]);
    }
}
