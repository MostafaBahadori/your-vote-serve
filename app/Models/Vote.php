<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'candidate_id',
        'election_id'
    ];


    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function election(){
        return $this->belongsTo(Election::class);
    }
}
