<?php
namespace App\DB;

class Cores extends Parser
{
    protected $fillable = [
        'title',
        'images',
        'event',
        'slot',
        'boostname', 
        'levels',
        'core_id'
    ];

    protected $casts = [
        'images' => 'array',
        'levels' => 'array',
        'boostname'=>'array',
    ];

    protected $appends = [
        'title',
        'images',
        'event',
        'slot',
        'boostname',
        'levels',
        'core_id'
    ];
  

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];
}
