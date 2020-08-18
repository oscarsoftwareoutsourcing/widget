<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apps extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'type', 'type_user', 'parameteres', 'widget_type'];
}
