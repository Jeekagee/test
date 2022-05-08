<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Details extends Model
{
    public $timestamps = false;
    protected $table = 'visit_details';
    protected $fillable = ['id','token_no', 'public_ref_no','purpose','comment','branch_id','status','action_date','action_comment','created_at','updated_at'];
}
