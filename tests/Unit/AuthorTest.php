<?php

namespace Tests\Unit;

use App\Models\Author;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function only_name_is_required_to_create_an_author()
    {

        $this->expectExceptionCode(0);
        Author::firstOrCreate([
            "name"=> "John Doe"
        ]);

        $this->assertCount(1, Author::all());
        $this->assertEquals("", Author::first()->name);

    }
}
