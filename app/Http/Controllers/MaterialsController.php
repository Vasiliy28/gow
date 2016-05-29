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
        $materials = Materials::all();
        return view('materials.index')->with('materials',!$materials->isEmpty() ? $materials : '');
    }

    public function postIndex()
    {
        $urls = $this->getAllUrls();
        
        foreach ($urls as $url) {
            $data = $this->getDateCoreByUrl($url);
            $materials = Materials::firstOrCreate(array('material_id' => $data['material_id']));
            $materials->fill($data);
            $materials->save();
        }
        $materials = Materials::all();
        $materials_json = $materials->toJson();
        Storage::disk('public_import')->put(self::FILE_NAME , $materials_json);
        
        return view('materials.index')->with('materials',!$materials->isEmpty() ? $materials : '');
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

    private function getDateCoreByUrl($url)
    {
        $html = \Cache::rememberForever('core_' . $url, function () use ($url) {
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
        $data['material_id'] = preg_replace("/[^0-9]/", '', $url);
        return $data;


    }

}