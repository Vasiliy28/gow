<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.05.16
 * Time: 12:35
 */

namespace App\Http\Controllers;

use App\DB\Cores;
use App\Helpers\FlashHelper;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;


class CoresController extends ParserController
{
    public function getIndex()
    {
        $cores = Cores::all();
        return view('cores/index')->with('cores', !$cores->isEmpty() ? $cores : null);
    }

    public function postIndex()
    {
        FlashHelper::info('POST INDEX');

        $urls = $this->getAllUrls();
        foreach ($urls as $key => $url) {
            $data = $this->getDateCoreByUrl($url);
            $core = Cores::firstOrCreate(array('core_id' => $data['core_id']));
            $core->fill($data);
            $core->save();
        }

        $cores = Cores::all();
        $cores_json = $core->toJson();
        Storage::disk('public_import')->put('cores.txt', $cores_json);
        return view('cores/index')->with('cores', $cores);

    }
    private function getAllUrls()
    {
        $url = 'http://gow.help/templates/gow/ajax/findEquipment2/';
        $arg = [
            'p' => 7,
            'catch' => 0,
            'type[]' => 1,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arg);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "");
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $html = curl_exec($ch);
        curl_close($ch);

        $result = \phpQuery::newDocumentHTML($html);

        if ($result) {
            $elements = $result->find('a');
            foreach ($elements as $key => $element) {
                $link = pq($element)->attr('href');
                $urls[$key] = 'http://gow.help/en' . $link;
            }
        }



        \phpQuery::unloadDocuments();
        return $urls;
    }

    private function getDateCoreByUrl($url)
    {
        $html = \Cache::rememberForever('core_' . $url, function () use ($url) {
            return file_get_contents($url);
        });

        $result = \phpQuery::newDocumentHTML($html);
        \phpQuery::unloadDocuments($html);

        $table_detail = $result->find('.gemMainDetail');
        $rows_getail = $table_detail->find('tr');
        $images = 'http://gow.help' . $result->find('.detailImg img')->attr('data-img');

        $table_inf = $result->find('.eqInfoDiv table');
        $rows_info = $table_inf->find('tr');

        foreach ($rows_info as $key => $tr) {

            foreach (pq($tr)->find('td') as $index => $td) {

                if (!$index) {
                    $data['boostname'][$key] = pq($td)->text();
                    continue;
                }
                $data['levels'][$index][] = pq($td)->text();
            }


        }

        foreach ($data['levels'] as $key => $level) {
            $data['levels'][$key] = implode(",", $level);
        }

        foreach ($rows_getail as $row) {

            $value = pq($row)->find('td')->eq(0)->text();

            if (preg_match('/slot/i', $value)) {
                $data['slot'] = pq($row)->find('td')->eq(1)->text();
                continue;
            }

            if (preg_match('/event/i', $value)) {
                $data['event'] = pq($row)->find('td')->eq(1)->text();
                continue;
            }

        }


        $data['title'] = pq($result)->find('.pageContent h1')->text();
        $data['images'] = $images;
        $data['core_id'] = preg_replace("/[^0-9]/", '', $url);

        $a = 1;
        return $data;

    }

}