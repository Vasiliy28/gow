<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

abstract class ParserController extends Controller
{
    const PATH_TO_FILE_IN_PUBLIC = "imports";
    
    public function getFilePath($fileName)
    {
        $file = public_path() . "/imports/" . $fileName;
        
        if (file_exists($file)) {
            return self::PATH_TO_FILE_IN_PUBLIC . '/' . $fileName;
        }
        
        return false;
    }
}


