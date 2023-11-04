<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookManagementTest extends TestCase
{

   use RefreshDatabase;
    /** @test */
    public function test_a_book_can_be_added_to_the_library()
    {

        $this->withExceptionHandling();

        $response = $this->post(route("books.store"), [
            "title"=> "Eat the Frog",
            "author"=> "Brian Tracy"
        ]);

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

    public function test_a_author_name_is_required()
    {

        $response = $this->post(route("books.store"), [
            "title"=> "Eat the Frog",
            "author"=> ""
            ]);

        $response->assertSessionHasErrors(["author"]);

    }

    public function test_a_book_title_and_author_are_required()
    {

        $response = $this->post(route("books.store"), [
            "title"=> "",
            "author"=> ""
            ]);

        $response->assertSessionHasErrors(["author", "title"]);

    }

    /** @test */
    public function can_update_book()
    {

        $this->withExceptionHandling();

        $this->post(route("books.store"), [
            "title"=> "Cool Title",
            "author"=> "Brian Tracey",
        ]);

        $response = $this->patch(route("books.update", Book::first()->id), [
            "title"=> "New Title",
            "author"=> "New Author",
            ]);

            $this->assertEquals("New Title", Book::first()->title);
            $this->assertEquals("New Author", Book::first()->author);
    }

    /** @test */

    public function a_book_can_be_deleted()
    {

        $this->post(route("books.store"), [
            "title"=> "Eat the Elephant",
            "author"=> "Paa Adjei",
        ]);

        $response = $this->delete(route("books.destroy", Book::first()->id));

        $this->assertCount(0, Book::all());
        $response->assertRedirect("books.index");

    }

}
