<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirstName extends Model
{
    //
    protected $table = 'ref_firstnames';
    public $timestamps = false;
    protected $guarded = ['id'];
}
