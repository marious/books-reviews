<?php

namespace App\DataTransferObject;

use App\Http\Requests\CreateBookReviewRequest;

class BookReviewDto
{
    public function __construct(
        public readonly string $review,
        public int $rating
    ) {
    }

    public static function fromRequest(CreateBookReviewRequest $request): self
    {
        return new self(
            review: $request->validated('review'),
            rating: $request->validated('rating')
        );
    }
}
