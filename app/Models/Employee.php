<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['employee_name', 'amount']; // necesario para agregar masivamente

    public function transactions()
    {
    	return $this->hasMany('App\Transaction');
    }
}
