<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exhibitor extends MyBaseModel
{
    //

    /**
     * The events Exhibitor with the organizer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Events()
    {
        return $this->hasMany(\App\Models\Event::class);
        //return $this->hasMany( \App\Models\Event::class )->where( 'to_user_id', '=', Auth::User()->id );
    }
}
