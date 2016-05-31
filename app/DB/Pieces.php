<?php
namespace App\DB;

class Pieces extends Parser
{
    protected $fillable = [
        'id',
        'title',
        'images',
        'event',
        'boostname',
        'levels',
    ];

    protected $casts = [
        'images' => 'array',
        'levels' => 'array',
        'boostname' => 'array',
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];


}
