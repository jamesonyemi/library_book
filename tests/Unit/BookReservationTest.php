<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    public function test_a_book_can_be_checked_out()
    {

        $this->withExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);

    }

    public function test_a_book_can_be_checked_in_when_a_user_returns_a_book()
    {

        $this->withExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkout($user);

        $book->checkin($user);

        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertNotNull(Reservation::first()->checked_in_at);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);

    }

    // if a book is not checked out, then throw an exception

    public function test_throw_an_exception_if_a_book_is_not_checked_out()
    {
        # code...
        $this->expectException(\Exception::class);

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkin($user);

    }

    public function test_a_user_can_check_out_a_book_twice()
    {
        # code...
        $this->withExceptionHandling();
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);

        $book->checkout($user);

        $this->assertCount(Reservation::all()->last()->id, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(Reservation::all()->last()->id)->user_id);
        $this->assertEquals(now(), Reservation::find(Reservation::all()->last()->id)->checked_out_at);
        $this->assertNull(Reservation::find(Reservation::all()->last()->id)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(Reservation::all()->last()->id)->checked_out_at);


        $book->checkin($user);

        $this->assertCount(Reservation::all()->last()->id, Reservation::all());
        $this->assertEquals($user->id, Reservation::find(Reservation::all()->last()->id)->user_id);
        $this->assertEquals(now(), Reservation::find(Reservation::all()->last()->id)->checked_out_at);
        $this->assertNotNull(Reservation::find(Reservation::all()->last()->id)->checked_in_at);
        $this->assertEquals(now(), Reservation::find(Reservation::all()->last()->id)->checked_in_at);

    }
}
