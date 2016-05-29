<?php

namespace App\DB;


class Materials extends Parser
{
    protected $fillable = [
        'title',
        'images',
        'event',
        'used',
        'material_id',
    ];

    protected $casts = [
        'images' => 'array',
        'used'=>'array',
    ];



    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

}