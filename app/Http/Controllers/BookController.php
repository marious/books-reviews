<?php

namespace App\Http\Controllers;

use App\Services\BookService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function __construct(protected readonly BookService $bookService)
    {
    }

    /**s
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('books.index', [
            'books' => $this->bookService->getBooks($request->input('title'), $request->input('filter')),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $book = $this->bookService->getBook((int) $id);
            return view('books.show', ['book' => $book]);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }
}
