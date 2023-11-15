<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{

   use RefreshDatabase;
    /** @test */
    public function test_a_book_can_be_added_to_the_library()
    {

        $this->withExceptionHandling();

        $response = $this->post(route("books.store"), $this->data());

        $response->assertOk();
        $this->assertCount(1, Book::all());

    }

    public function test_a_title_is_required()
    {

        $response = $this->post(route("books.store"), [
            "title"=> "",
            "author"=> "Brian Tracy"
            ]);

            $response->assertSessionHasErrors(["title"]);

    }

    public function test_a_author_is_required()
    {

        $response = $this->post(route("books.store"),
            array_merge( $this->data(), ['author_id' => ''] ));

        $response->assertSessionHasErrors(["author_id"]);

    }

    public function test_a_book_title_and_author_are_required()
    {

        $response = $this->post(route("books.store"), [
            "title"=> "",
            "author_id"=> ""
            ]);

        $response->assertSessionHasErrors(["author_id", "title"]);

    }

    /** @test */
    public function can_update_book()
    {

        $this->withExceptionHandling();

        $this->post(route("books.store"), $this->data());

        $response = $this->patch(route("books.update", Book::first()->id), [
            "title"=> "New Title",
            "author_id"=> "New Author",
            ]);

            $this->assertEquals("New Title", Book::first()->title);
            $this->assertEquals(2, Book::first()->author_id);
    }

    /** @test */

    public function a_book_can_be_deleted()
    {

        $this->post(route("books.store"), $this->data());

        $response = $this->delete(route("books.destroy", Book::first()->id));

        $this->assertCount(0, Book::all());
        $response->assertRedirect("books.index");

    }

    /** @test */
    public function a_new_author_is_automatically_added()
    {
$this->withExceptionHandling();
        $this->post(route("books.store"), [
            "title"=> "Eat Lango",
            "author_id"=> 'New author',
        ]);

        $author = Author::first();
        $book = Book::first();

        $this->assertCount(1, Author::all());
        $this->assertEquals($author->id, $book->author_id);
    }

    private function data()
    {

      return [
            "title"=> "Eat the Frog",
            "author_id"=> "Brian Tracy"
        ];
    }

}
