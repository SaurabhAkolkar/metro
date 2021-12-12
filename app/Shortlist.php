<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shortlist extends Model
{
   protected $fillable = ['user_id', 'short_listed_id'];
   protected $primaryKey = 'user_id';


   public function users()
    {
        return $this->belongsTo('App\User','short_listed_id');
    }

}
