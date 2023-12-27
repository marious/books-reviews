@extends('layouts.app')
@section('content')
    <h1 class="mb-10 text-2xl">Add Review for {{ $book->title }}</h1>
    <form method="post" action="{{ route('books.reviews.store', $book) }}">
        @csrf
        <label for="review">Review</label>
        <textarea name="review" id="review" rquired class="input mb-4">{{ old('review') }}</textarea>
        @error('review')
        <p class="text-red-500">{{ $message }}</p>
        @enderror

        <label for="rating">Rating</label>

        <select name="rating" id="rating" class="input mb-4" requireds>
            <option value="">Select Rating</option>
            @for ($i = 1; $i <= 5; $i++)
            <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        @error('rating')
            <p class="text-red-500">{{ $message }}</p>
        @enderror

        <button type="submit" class="btn">Add Review</button>
    </form>
@endsection
