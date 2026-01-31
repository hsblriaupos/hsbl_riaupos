<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventData extends Model {
    protected $table = 'events_data';
    protected $fillable = [
        'event_name',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'term_cond', // TAMBAHKAN INI
    ];
}