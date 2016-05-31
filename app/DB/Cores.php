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

  
}
