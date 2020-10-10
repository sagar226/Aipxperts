<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Guid\Guid;

class Employee extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id','name','designation_id'
    ];
    public function designation(){
        return $this->hasone(Designation::class,'id','designation_id');
    }

    public function salary(){
        return $this->belongsTo(Salary::class,'id','employee_id');
    }
}
