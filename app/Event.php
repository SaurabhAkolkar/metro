<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    //

  protected $fillable = ['image','heading','description'];

    protected function getArrayableAttributes()
    {

        foreach ($this->attributes as $key => $value) {

            if (is_null($value)) {
                $this->attributes[$key] = '';
            }
        }

        return $this->getArrayableItems($this->attributes);
    }
}
