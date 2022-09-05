<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cities extends Model
{
    use HasFactory;



    protected $fillable = ['states_id', 'name'];

    public function states()
    {
        return $this->belongsTo(States::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}