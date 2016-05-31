<?php
namespace App\DB;

class Cores extends Parser
{
    protected $fillable = [
        'id',
        'title',
        'images',
        'event',
        'slot',
        'boostname', 
        'levels',
    ];

    protected $casts = [
        'images' => 'array',
        'levels' => 'array',
        'boostname'=>'array',
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        self::creating(function($model) {
            if ( ! is_array($model->images)) {
                $model->images = [];
            }
            if ( ! is_array($model->levels)) {
                $model->levels = [];
            }
            if ( ! is_array($model->boostname)) {
                $model->boostname = [];
            }
        });
        parent::boot();
    } 
}
