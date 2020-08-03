<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Guest.
 *
 * @package namespace App\Models;
 */
class Guest extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ap',
        'mac',
        'ssid',
        'ip',
        'minutes'
    ];

    public function user()
    {
        return $this->belongsTo(Associated::class, 'associated_id');
    }


}
