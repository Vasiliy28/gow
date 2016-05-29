<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.05.16
 * Time: 22:32
 */

namespace App\Http\Controllers;


class MaterialsController extends ParserController
{
    public function getIndex()
    {
        return view('materials.index');
    }

    public function postIndex()
    {
        return view('materials.index');
    }

}