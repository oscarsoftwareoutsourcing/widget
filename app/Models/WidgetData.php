<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WidgetData extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'widget_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['widget_id', 'origin', 'info_data'];
}
