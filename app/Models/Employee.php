<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory;use SoftDeletes;
    public function country(){
        return $this->belongsTo(Country::class,"country_id","id");
    }
    public function state(){
        return $this->belongsTo(State::class,"state_id","id");
    }
    public function city(){
        return $this->belongsTo(City::class,"city_id","id");
    }
    public function department(){
        return $this->belongsTo(Department::class,"department_id","id");
    }
}
