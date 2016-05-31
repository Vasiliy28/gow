<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.05.16
 * Time: 18:07
 */

namespace App\DB;


class Gems extends Parser
{
    const HAS_FOUR_TH_SLOT = 1;
    const HAS_NOT_FOUR_TH_SLOT = 0;

    protected $fillable = [
        'id',
        'title',
        'images',
        'event',
        'four_th_slot',
        'boostname',
        'levels',
        'gallery',
    ];

    protected $casts = [
        'images' => 'array',
        'levels' => 'array',
        'boostname'=>'array',
        'gallery' => 'array',
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

   public static function boot()
   {
      self::creating( function ( $model ) {
          if ( ! is_array($model->images)) {
              $model->images = [];
          }
          if ( ! is_array($model->levels)) {
              $model->levels = [];
          }
          if ( ! is_array($model->boostname)) {
              $model->boostname = [];
          }
          if(! is_array($model->gallery)) {
              $model->gallery = [];
          }
      });

      parent::boot(); // TODO: Change the autogenerated stub
   }

}