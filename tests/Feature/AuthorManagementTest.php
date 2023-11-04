<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Author;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorManagementTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created()
    {


        $this->post(route("authors.store"), [
            "name"=> "Author name",
            "dob"=> "05/10/1993",
        ]);

        $this->assertCount(1, Author::all());
        $this->assertInstanceOf(Carbon::class, Author::all()->first()->dob);
        $this->assertEquals("1993-05-10", Author::all()->first()->dob->format("Y-m-d"));
    }
}
