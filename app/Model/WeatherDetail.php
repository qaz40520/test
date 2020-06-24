<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class WeatherDetail extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weather_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'region_id', 'date', 'highest', 'lowest', 'overview',
    ];

    public $timestamps = false;
}
