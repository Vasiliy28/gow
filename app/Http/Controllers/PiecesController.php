<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.05.16
 * Time: 12:58
 */
namespace App\Http\Controllers;

use App\DB\Pieces;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Storage;


class PiecesController extends ParserController
{
    const FILE_NAME = 'pieces.txt';
    public function getIndex()
    {
        $pieces = '';
        $pieces = Pieces::all();
        $data = [
            'pieces' => $pieces && !$pieces->isEmpty() ? $pieces : "",
            'file_path' => parent::getFilePath(self::FILE_NAME)
        ];
        return view('pieces.index' , $data);

    }

    public function postIndex()
    {
        $pieces = '';
        $urls = $this->getAllUrls();


        foreach ($urls as $key => $url) {
  
            $data = $this->getDataPieceByUrl($url);

            if( ! $piece = Pieces::find($data['id'])) {

                $piece = new Pieces();
            }

            $piece->fill($data);
            $piece->save();

        }

        $pieces = Pieces::all();
        $pieces_json = $pieces->toJson();
       
        Storage::disk('public_import')->put(self::FILE_NAME, $pieces_json);

        $data = [
            'pieces' => $pieces && !$pieces->isEmpty() ? $pieces : "",
            'file_path' => parent::getFilePath(self::FILE_NAME)
        ];
        
        return view('pieces.index' , $data);
        
    }

    public function getAllUrls()
    {
        $url = 'http://gow.help/templates/gow/ajax/findEquipment2/';
        $arg = [
            'p' => 7,
            'catch' => 0,
            'type[]' => 2,
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

    private function getDataPieceByUrl($url)
    {
        $html = \Cache::rememberForever('piece_' . $url, function () use ($url) {
            return file_get_contents($url);
        });
//        $html = file_get_contents($url);

        $result = \phpQuery::newDocumentHTML($html);
        \phpQuery::unloadDocuments($html);


        $table_detail = $result->find('.gemMainDetail');
        $rows_detail = $table_detail->find('tr');
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

        foreach ($rows_detail as $row) {

            $value = pq($row)->find('td')->eq(0)->text();
            if (preg_match('/event/i', $value)) {
                $data['event'] = pq($row)->find('td')->eq(1)->text();
                break;
            }

        }

        $data['title'] = pq($result)->find('.pageContent h1')->text();
        $data['images'] = $images;
        $data['id'] = preg_replace("/[^0-9]/", '', $url);

        return $data;
    }


}