<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * Class Base common function
 * The time function use to avoid different timezone
 * @package App\Models
 */
class Base extends Model
{

    /**
     * get timestramp for now
     * @return int
     */
    public function freshTimestamp()
    {
        $now = Carbon::now();
        return $now->getTimestamp();
    }

    /**
     * get time string format is: 2016-06-16 15:13:12
     * @return string
     */
    public function getTimeString()
    {
        $now = Carbon::now();
        return $now->toDateTimeString();
    }
}
