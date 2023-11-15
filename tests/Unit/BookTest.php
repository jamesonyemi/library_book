<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class BookTest extends TestCase
{

    use RefreshDatabase;
    public function test_an_author_id_is_recorded()
    {

        $this->expectExceptionCode(0);

        Book::create([
            "title"=> "Cool Title",
            "author_id" => 1,
            ]);

        $this->assertCount(1, Book::all());
    }
}
