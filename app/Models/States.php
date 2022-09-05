<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class States extends Model
{
    use HasFactory;
    protected $fillable = ['country_id', 'name',];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function cities()
    {
        return $this->hasMany(cities::class);
    }
}