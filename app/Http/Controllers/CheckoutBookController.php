<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CheckoutBookController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }
    /**
     * Summary of store
     * @param \App\Models\Book $book
     * @return void
     */
    public function store(Book $book)
    {
        $book->checkout(auth()->user());
    }

}
