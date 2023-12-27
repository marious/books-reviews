<?php

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

class BookBuilder extends Builder
{
    public function title(string $title): Builder
    {
        return $this->where('title', 'LIKE', '%' . $title . '%');
    }

    public function withAvgRating(?string $from = null, ?string $to = null): Builder
    {
        return $this->withAvg([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to),
        ], 'rating');
    }

    public function withReviewsCount(?string $from = null, ?string $to = null): Builder
    {
        return $this->withCount([
            'reviews' => fn (Builder $q) => $this->dateRangeFilter($q, $from, $to),
        ]);
    }

    public function popular(?string $from, ?string $to)
    {
        return $this->withReviewsCount($from, $to)
            ->orderBy('reviews_count', 'DESC');
    }

    public function highestRated(?string $from = null, ?string $to = null): Builder
    {
        return $this->withAvgRating($from, $to)
            ->orderBy('reviews_avg_rating', 'DESC');
    }

    public function highestRatedLastMonth()
    {
        return $this->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function highestRatedLast6Months()
    {
        return $this->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(2);
    }

    public function popularLastMonth(): Builder
    {
        return $this->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(2);
    }

    public function popularLast6Months(): Builder
    {
        return $this->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(5);
    }

    public function minReviews(int $minReviews): Builder
    {
        return $this->having('reviews_count', '>=', $minReviews);
    }

    private function dateRangeFilter(Builder $query, ?string $from = null, ?string $to = null)
    {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<=', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }
}
