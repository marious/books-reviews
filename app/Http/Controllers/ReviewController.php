<?php

namespace App\Http\Controllers;

use App\DataTransferObject\BookReviewDto;
use App\Http\Requests\CreateBookReviewRequest;
use App\Models\Book;
use App\Services\BookReviewService;

class ReviewController extends Controller
{
    public function __construct(protected BookReviewService $bookService)
    {
    }

    public function create(Book $book)
    {
        return view('books.reviews.create', ['book' => $book]);
    }

    public function store(CreateBookReviewRequest $request, Book $book)
    {
        $this->bookService->makeReview($book, BookReviewDto::fromRequest($request));

        return to_route('books.show', ['book' => $book]);
    }
}
