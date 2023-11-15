<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CheckInBookController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth");
    }
    public function store(Book $book)
    {
        # code...
        try {
            //code...
            $book->checkin(auth()->user());
            
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([], 404);
        }
    }
}
