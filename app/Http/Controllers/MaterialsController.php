<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.05.16
 * Time: 22:32
 */

namespace App\Http\Controllers;


use App\DB\Materials;
use Illuminate\Support\Facades\Storage;

class MaterialsController extends ParserController
{
    const FILE_NAME = 'materials.txt';

    public function getIndex()
    {
        $materials = '';
        $materials = Materials::all();
        $data = [
            'materials' => $materials && !$materials->isEmpty() ? $materials : '',
            'file_path' => parent::getFilePath(self::FILE_NAME) ,
        ];
        return view('materials.index', $data);
    }

    public function postIndex()
    {
        $materials = '';
        $urls = $this->getAllUrls();
        
        foreach ($urls as $key => $url) {

            if($key > 15) {
                break;
            }
            $data = $this->getDataMaterialByUrl($url);
            
            if( ! $material = Materials::find($data['id'])) {
                $material = new Materials();
            }
            
            $material->fill($data);
            $material->save();
        }
        $materials = Materials::all();
        $materials_json = $materials->toJson();
        Storage::disk('public_import')->put(self::FILE_NAME , $materials_json);

        $data = [
            'materials' => $materials && !$materials->isEmpty() ? $materials : '',
            'file_path' => parent::getFilePath(self::FILE_NAME) ,
        ];
        return view('materials.index', $data);
    }
    private function getAllUrls()
    {
        $url = 'http://gow.help/en/resources/materials/';
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

        return $urls;

    }

    private function getDataMaterialByUrl($url)
    {
        $data = [];
        $html = \Cache::rememberForever('material_' . $url, function () use ($url) {
            return file_get_contents($url);
        });
//        $html = file_get_contents($url);


        $result = \phpQuery::newDocumentHTML($html);
        \phpQuery::unloadDocuments($html);

        $title = $result->find('.pageContent')->find('h1')->eq(0)->text();
        $table_detail = $result->find('.gemMainDetail');
        $rows_detail = $table_detail->find('tr');
        $images = 'http://gow.help' . $result->find('.detailImg img')->attr('data-img');
        foreach ($rows_detail as $row) {
            $value = pq($row)->find('td')->eq(0)->text();
            if (preg_match('/event/i', $value)) {
                $data['event'] = pq($row)->find('td')->eq(1)->text();
                break;
            }
        }
        $used = $result->find('.allGemsList')->find('.name');
        foreach ($used as $key => $item) {
            $data['used'][$key] = pq($item)->text();
        }
        $data['title'] = $title;
        $data['images'] = $images;
        $data['id'] = preg_replace("/[^0-9]/", '', $url);
        return $data;


    }

}