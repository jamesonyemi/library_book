<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookCheckOutTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out_by_an_authenticated_user()
    {

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/checkout/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);

    }

    /** @test */
    public function only_authenticated_user_can_checked_out_a_book()
    {

        $book = Book::factory()->create();

        $this->post('/checkout/'. $book->id)->assertRedirect('/login');

        $this->assertCount(0, Reservation::all());

    }

    /** @test */
    public function only_real_books_can_checked_out()
    {

        $this->actingAs($user = User::factory()->create())->post('/checkout/123')->assertStatus(404);

        $this->assertCount(0, Reservation::all());

    }


    /** @test */
    public function a_book_can_be_checked_in_by_an_authenticated_user()
    {

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/checkout/' . $book->id);
        $this->actingAs($user)->post('/checkin/' . $book->id);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);

    }

   /** @test */
    public function only_authenticated_user_can_check_in_a_book()
    {

        // $this->withOutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post('/checkout/' . $book->id);

        Auth::logout();

        $this->post('/checkin/' . $book->id)->assertRedirect("/login");

        $this->assertCount(1, Reservation::all());
        $this->assertEquals(Null, Reservation::first()->checked_in_at);

    }

    /** @test */
    public function a_404_should_be_thrown_if_a_book_is_not_checked_out_first()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)->post("/checkin/". $book->id)->assertStatus(404);

        $this->assertCount(0, Reservation::all());

    }


}
