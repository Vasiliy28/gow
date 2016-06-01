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

    const FILE_NAME = 'cores.txt';

    public function getIndex()
    {
        $cores = Cores::all();
        \View::share( 'file_path', $this->getFilePath(self::FILE_NAME) );
        \View::share('cores', $cores);
        return view('cores/index');
    }

    public function postIndex()
    {
        FlashHelper::info('POST INDEX');
        $urls = $this->getAllUrls();

        if ($urls && is_array($urls)) {
            foreach ($urls as $key => $url) {
                if ($key > 15) {
                    break;
                }
                $data = $this->getDataCoreByUrl($url);
                
                if ( ! $core = Cores::find($data['id'])) {
                    $core = new Cores();
                }

                $core->fill($data);
                $core->save();
            }
        }

        $cores = Cores::all();

        if ($cores && ! $cores->isEmpty()) {
            $cores_json = $cores->toJson();
            Storage::disk('public_import')->put(self::FILE_NAME, $cores_json);
        }

        \View::share( 'file_path', $this->getFilePath(self::FILE_NAME) );
        \View::share('cores', $cores);
        return view('cores/index');

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

    private function getDataCoreByUrl($url)
    {
        $data = [];
        $html = \Cache::rememberForever('core_' . $url, function () use ($url) {
            return file_get_contents($url);
        });


        $result = \phpQuery::newDocumentHTML($html);
        \phpQuery::unloadDocuments($html);

        $table_detail = $result->find('.gemMainDetail');
        $rows_detail = $table_detail->find('tr');

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

        foreach ($rows_detail as $row) {

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
        $data['images'][] = 'http://gow.help' . $result->find('.detailImg img')->attr('data-img');
        $data['id'] = preg_replace("/[^0-9]/", '', $url);

        return $data;

    }

}