<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class firstName extends Model
{
    //
    protected $table = 'ref_firstnames';
    public $timestamps = false;
    protected $guarded = ['id'];
}
