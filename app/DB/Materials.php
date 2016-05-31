<?php

namespace App\DB;


class Materials extends Parser
{
    
    protected $fillable = [
        'id',
        'title',
        'images',
        'event',
        'used',
    ];

    protected $casts = [
        'images' => 'array',
        'used'=>'array',
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

}