<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'city_id',
        'states_id',
        'department_id',
        'country_id',
        'zip_code',
        'birth_date',
        'date_hired'
    ];


    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function states()
    {
        return $this->belongsTo(States::class);
    }

    public function city()
    {
        return $this->belongsTo(cities::class);
    }
}