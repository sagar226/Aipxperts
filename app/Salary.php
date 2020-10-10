<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id','salary','employee_id'
    ];

    public function employee(){
        return $this->hasone(Employee::class,'id','employee_id');
    }
}
