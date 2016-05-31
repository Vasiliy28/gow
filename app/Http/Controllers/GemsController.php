<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.05.16
 * Time: 18:49
 */

namespace App\Http\Controllers;


use App\DB\Gems;
use Illuminate\Support\Facades\Storage;

class GemsController extends ParserController
{
    const FILE_NAME = 'gems.txt';

    public function getIndex()
    {
        $gems = '';
        $gems = Gems::all();
        $data = [
            'gems' => $gems && !$gems->isEmpty() ? $gems : "",
            'file_path' => parent::getFilePath(self::FILE_NAME)
        ];
        return view("gems.index", $data);
    }
    
    public function postIndex()
    {
        $gems = '';

        $urls = $this->getAllUrls();
        if($urls && is_array($urls)) {

            foreach ($urls as $key => $url) {

                if($key > 14) {
                    break;
                }
                $data = $this->getDataGemByUrl($url);

                if(! $gem = Gems::find($data['id'])) {
                    $gem = new Gems();
                }

                $gem->fill($data);
                $gem->save();

            }
        }
        $gems = Gems::all();
        if($gems && !$gems->isEmpty()) {
            $gems_json = $gems->toJson();
            Storage::disk('public_import')->put(self::FILE_NAME, $gems_json);
        }

        $data = [
            'gems' => $gems && !$gems->isEmpty() ? $gems : "",
            'file_path' => parent::getFilePath(self::FILE_NAME)
        ];

        return view("gems.index", $data);
    }

    private function getDataGemByUrl($url)
    {
        $data = [];
        $html = \Cache::rememberForever('gem_' . $url, function () use ($url) {
            return file_get_contents($url);
        });
        $result = \phpQuery::newDocumentHTML($html);
        \phpQuery::unloadDocuments($html);

        $data['title'] = $result->find('.pageContent')->find('h1')->eq(0)->text();
        $data['images'] = 'http://gow.help' . $result->find('img.lazy')->eq(0)->attr('data-img');

        $rows_detail = $result->find('.gemMainDetail')->find('tr');
        foreach ($rows_detail as $row) {

            $value = pq($row)->find('td')->eq(0)->text();

            if (preg_match('/event/i', $value)) {
                $data['event'] = pq($row)->find('td')->eq(1)->text();
                continue;
            }

            if (preg_match('/4th gem slot/i', $value)) {
                $value_4th_gem_slot = pq($row)->find('td')->eq(1)->text();

                if (preg_match('/no/i', $value_4th_gem_slot)) {
                    $data['four_th_slot'] = Gems::HAS_NOT_FOUR_TH_SLOT;
                } else {
                    $data['four_th_slot'] = Gems::HAS_FOUR_TH_SLOT;
                }
                continue;
            }

        }


        $rows_info = pq($result)->find('.gemDetailDiv')->eq(1)->find('tr');

        foreach ($rows_info as $key => $tr) {

            foreach (pq($tr)->find('td') as $index => $td) {

                if (!$index && !$key) {
                    continue;
                }

                if($index && !$key) {
                    $data['gallery'][$index] = 'http://gow.help' . pq($td)->find('img.lazy')->eq(0)->attr('data-img');
                    continue;
                }

                if (!$index && $key) {
                    $data['boostname'][$key] = pq($td)->text();
                    continue;
                }
                $data['levels'][$index][] = pq($td)->text();
            }
        }

        foreach ($data['levels'] as $key => $level) {
            $data['levels'][$key] = implode(",", $level);
        }

        $data['id'] = preg_replace("/[^0-9]/", '', $url);


        return $data;
    }

    private function getAllUrls()
    {
        $urls = [];
        $url = 'http://gow.help/en/resources/gems/';
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