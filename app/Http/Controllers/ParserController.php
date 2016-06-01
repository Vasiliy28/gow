<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

abstract class ParserController extends Controller
{
    const PATH_TO_FILE_IN_PUBLIC = "imports";
    const GOW_HOST = "http://gow.help";
    
    public function getFilePath($fileName)
    {
        $file = public_path() . "/" .self::PATH_TO_FILE_IN_PUBLIC . "/" . $fileName;
        
        if (file_exists($file)) {
            return self::PATH_TO_FILE_IN_PUBLIC . '/' . $fileName;
        }
        
        return false;
    }
}


