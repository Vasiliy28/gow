<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.05.16
 * Time: 12:58
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;


class PiecesController extends ParserController
{
    public function getIndex()
    {
        return view('pieces/index');
    }

    public function postIndex()
    {
        return view('pieces/index');
    }

}