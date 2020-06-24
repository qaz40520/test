<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'weather';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'region',
    ];

    /**
     * @param string $region
     * @return int | null
     */
    public static function getRegionId(string $region)
    {
        self::updateOrInsert(['region' => $region]);
        return self::where('region', $region)->first()->id;

    }

//    public function getWeekWeather()
//    {
//        return $this->hasMany('App\Model\WeatherDetail')->whereIn('date', )
//    }
//
//    private static function getOneWeekDate()
//    {
//        for ($i = 0; $i < 7; ++$i) {
//            echo date('Y/m/d', strtotime('+' . $i . ' days'));
//        }
//    }
}
