<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.05.16
 * Time: 15:15
 */

namespace App\Http\Controllers;


use App\DB\Buildings;
use App\Helpers\FlashHelper;

class BuildingsController extends ParserController
{
    const FILE_NAME = 'buildings.txt';
    public function getIndex()
    {

        $buildings = Buildings::all();

        \View::share('buildings' , $buildings);
        \View::share('file_path' , $this->getFilePath(self::FILE_NAME));

       return view('cores.index');
    }

    public function postIndex()
    {
        $buildings = '';

        $this->getAllUrls();
        $buildings = Buildings::all();
        $urls = $this->getAllUrls();

        if($urls && is_array($urls)) {
            foreach ($urls as $key => $url) {
                if($key >14){
                    break;
                }
                $this->getDataBuildingByUrl($url);
            }
        }
        FlashHelper::info("Parse Ok");
        \View::share('buildings' , $buildings);
        \View::share('file_path' , $this->getFilePath(self::FILE_NAME));

        return view('cores.index');
    }

    public function getDataBuildingByUrl($url)
    {
        $data = [];
        $html = \Cache::rememberForever('building_' . $url, function () use ($url) {
            return file_get_contents($url);
        });
    }

    private function getAllUrls()
    {
        $url = 'http://gow.help/en/resources/buildings/';
        $html = file_get_contents($url);

        $result = \phpQuery::newDocumentHTML($html);

        if ($result) {
            $section = $result->find('.allGemsList');
            $links = $section->find('a');

            foreach ($links as $key => $link) {
                $link = pq($link)->attr('href');
                $urls[$key] = 'http://gow.help' . $link;
            }
        }

        \phpQuery::unloadDocuments();

        return $urls;
    }

}