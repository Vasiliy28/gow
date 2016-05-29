<?php
namespace App\DB;

class Pieces extends Parser
{
    protected $fillable = [
        'title',
        'images',
        'event',
        'boostname',
        'levels',
        'piece_id'
    ];

    protected $casts = [
        'images' => 'array',
        'levels' => 'array',
        'boostname'=>'array',
    ];



    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];


}
