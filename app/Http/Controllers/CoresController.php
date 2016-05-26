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


class CoresController extends ParserController
{
    public function getIndex()
    {
        return view('cores/index');
    }

    public function postIndex()
    {
        FlashHelper::info('POST INDEX');


        $urls = $this->getAllUrls();

        foreach ($urls as $key => $url){
          $data = $this->getDateCoreByUrl($url);
            
            $core = new Cores();
            $core->fill($data);
            $core->save();
        }

//      return view('cores/index');
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

        $html = \Cache::rememberForever('core_' . $url, function() use ($url) {
            return file_get_contents($url);
        });

         $result = \phpQuery::newDocumentHTML($html);
         $image = $result->find('.detailImg img')->attr('data-img');
         $table_detail = $result->find('.gemMainDetail');
         $rows = $table_detail->find('tr');
         $table_inf = $result->find('.eqInfoDiv table');

         foreach ($table_inf->find('tr') as $key => $tr){

             $data['boostname'][$key] = pq($tr)->find('td')->eq(0)->text();
             $data['levels'][$key][1] = pq($tr)->find('td')->eq(1)->text();
             $data['levels'][$key][2] = pq($tr)->find('td')->eq(2)->text();
             $data['levels'][$key][3] = pq($tr)->find('td')->eq(3)->text();
             $data['levels'][$key][4] = pq($tr)->find('td')->eq(4)->text();
             $data['levels'][$key][5] = pq($tr)->find('td')->eq(5)->text();
             $data['levels'][$key][6] = pq($tr)->find('td')->eq(6)->text();

         }

        foreach ($data['levels'] as $key => $level){
            $data['levels'][$key] = implode(",", $level);
        }

         $data['title'] = $rows->eq(0)->find('td')->eq(1)->text();
         $data['slot'] = $rows->eq(1)->find('td')->eq(1)->text();
         $data['event'] = $rows->eq(3)->find('td')->eq(1)->text();
         $data['images']='http://gow.help'. $image;
         $data['core_id']=preg_replace("/[^0-9]/", '', $url);
        return $data;

    }
}