<?php

namespace App\Models;

use App\Models\Author;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'author_id'];

    public function setAuthorIdAttribute($authorId)
    {
        $this->attributes['author_id'] = (Author::create([
            'name'=> $authorId
        ]))->id;
    }

    public function checkout($user)
    {
        $this->reservations()->create([
            'user_id'=> $user->id,
            'checked_out_at'=> now(),
        ]);
    }

    public function checkin($user)
    {
        # code...
        $reservation = $this->reservations()->where('user_id', $user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();

            if (is_null($reservation) ) {
            throw new \Exception("Error Processing Request", 1);
        }

        $reservation->update([
            'checked_in_at' => now(),
            ]);

    }

    public function reservations()
    {
        # code...
        return $this->hasMany(Reservation::class);
    }
}
