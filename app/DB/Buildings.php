<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.05.16
 * Time: 15:03
 */

namespace App\DB;


class Buildings extends Parser
{

    protected $fillable = [
        'id',
        'title',
        'images',
        'levels',
        'woods',
        'stones',
        'foods',
        'ores',
        'times',
        'requirements',
        'hero_xp',
        'masters_hammers',
        'power',
        'bonuses'
    ];

    protected $casts = [
        'images' => 'array',
        'levels' => 'array',
        'woods'=> 'array' ,
        'stones'=> 'array',
        'foods'=> 'array',
        'ores'=> 'array',
        'times'=> 'array',
        'requirements'=> 'array',
        'hero_xp'=> 'array',
        'masters_hammers'=> 'array',
        'power'=> 'array',
        'bonuses'=> 'array',
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];


}