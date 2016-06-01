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

        \View::share('buildings', $buildings);
        \View::share('file_path', $this->getFilePath(self::FILE_NAME));

        return view('buildings.index');
    }

    public function postIndex()
    {
        $this->getAllUrls();
        $buildings = Buildings::all();
        $urls = $this->getAllUrls();

        if ($urls && is_array($urls)) {
            foreach ($urls as $key => $url) {
                if ($key > 14) {
                    break;
                }
                $this->getDataBuildingByUrl($url);
            }
        }

        \View::share('buildings', $buildings);
        \View::share('file_path', $this->getFilePath(self::FILE_NAME));
        FlashHelper::info('POST INDEX PARSE');
        return view('buildings.index');
    }

    public function getDataBuildingByUrl($url)
    {
        $data = [];
        $html = \Cache::rememberForever('building_' . $url, function () use ($url) {
            return file_get_contents($url);
        });

        $result = \phpQuery::newDocumentHTML($html);
        \phpQuery::unloadDocuments($html);

        $rows_info = pq($result)->find('.researchInfo tr');

        $index_levels = 0;
        $index_woods = 0;
        $index_stones = 0;
        $index_foods = 0;
        $index_ores = 0;
        $index_times = 0;
        $index_requirements = 0;
        $index_masters_hammer = 0;
        $index_hero_xp = 0;
        $index_power = 0;
        $bonuses = [];

        foreach ($rows_info as $key => $row) {

            if ( ! $key ) {
                foreach (pq($row)->find('td') as $index => $td) {

                    $value = pq($td)->text();

                    if (preg_match('/level/i', $value)) {
                        $index_levels = $index;
                        continue;
                    }
                    if (preg_match('/wood/i', $value)) {
                        $index_woods = $index;
                        continue;
                    }
                    if (preg_match('/stone/i', $value)) {
                        $index_stones = $index;
                        continue;
                    }
                    if (preg_match('/food/i', $value)) {
                        $index_foods = $index;
                        continue;
                    }
                    if (preg_match('/ore/i', $value)) {
                        $index_ores = $index;
                        continue;
                    }
                    if (preg_match('/time/i', $value)) {
                        $index_times = $index;
                        continue;
                    }
                    if (preg_match('/requirements/i', $value)) {
                        $index_requirements = $index;
                        continue;
                    }
                    if (preg_match('/master\'s hammer/i', $value)) {
                        $index_masters_hammer = $index;
                        continue;
                    }
                    if (preg_match('/hero xp/i', $value)) {
                        $index_hero_xp = $index;
                        continue;
                    }
                    if (preg_match('/power/i', $value)) {
                        $index_power = $index;
                        continue;
                    }

                    $bonuses[$value] = $index;
                }
            } else {
                $data['levels'][] = pq($row)->find('td')->eq($index_levels)->html();
                $data['woods'][] = pq($row)->find('td')->eq($index_woods)->html();
                $data['stones'][] = pq($row)->find('td')->eq($index_stones)->html();
                $data['foods'][] = pq($row)->find('td')->eq($index_foods)->html();
                $data['ores'][] = pq($row)->find('td')->eq($index_ores)->html();
                $data['times'][] = pq($row)->find('td')->eq($index_times)->html();
                $data['requirements'][] = pq($row)->find('td')->eq($index_requirements)->html();
                $data['masters_hammers'][] = pq($row)->find('td')->eq($index_masters_hammer)->html();
                $data['hero_xp'][] = pq($row)->find('td')->eq($index_hero_xp)->html();
                $data['power'][] = pq($row)->find('td')->eq($index_power)->html();

                foreach ($bonuses as $name_bonus => $index_bonuses) {
                    $data['bonuses'][$name_bonus][] = pq($row)->find('td')->eq($index_bonuses)->html();
                    $a = 1;
                }

            }

        }


        $data['title'] = pq($result)->find('.pageContent h1')->text();
        $data['images'][] = 'http://gow.help' . $result->find('.detailImg img')->attr('data-img');
        $a = 1;

    }

    private function getAllUrls()
    {
        $urls = [];
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