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
use App\Helpers\ParserHelper;

class BuildingsController extends ParserController
{
    const FILE_NAME = 'buildings.txt';

    public function getIndex()
    {
        $buildings = [];

        \View::share('buildings', $buildings);
        \View::share('file_path', $this->getFilePath(self::FILE_NAME));

        return view('buildings.index');
    }

    public function postIndex()
    {

        $this->getAllUrls();

        $urls = $this->getAllUrls();

        if ($urls && is_array($urls)) {
            foreach ($urls as $key => $url) {
                if ($key > 14) {
                    break;
                }
                $data = $this->getDataBuildingByUrl($url);

                if ( ! $building = Buildings::find($data['id'])) {
                    $building = new Buildings();
                }
                $building->fill($data);
                $building->save();
            }
        }

        $buildings = [];
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

        $index_levels = null;
        $index_woods = null;
        $index_stones = null;
        $index_foods = null;
        $index_ores = null;
        $index_times = null;
        $index_requirements = null;
        $index_masters_hammer = null;
        $index_hero_xp = null;
        $index_power = null;
        $bonuses = [];

        foreach ($rows_info as $key => $row) {

            /**
             * not include last row
             */
            if ($rows_info->length - 1 <= $key) {
                break;
            }

            /**
             * get index item
             */
            if ( ! $key) {
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
                $level = (int)pq($row)->find('td')->eq($index_levels)->text();
                /**
                 * get data from index item
                 */
                if ($index_levels !== null) {
                    $data['levels'][$level] = $level;
                }

                if ($index_woods !== null) {

                    $wood = pq($row)->find('td')->eq($index_woods)->text();

                    if (  $wood && $wood != "-") {
                        $wood = preg_replace("/[^\d]/", "", $wood);
                        $data['woods'][$level] = $wood;
                    }

                }

                if ($index_stones !== null) {
                    $stone = pq($row)->find('td')->eq($index_stones)->text();

                    if (  $stone && $stone != "-") {
                        $stone = preg_replace("/[^\d]/", "", $stone);
                        $data['stones'][$level] = $stone;
                    }

                }


                if ($index_foods !== null) {
                    $food = pq($row)->find('td')->eq($index_foods)->text();

                    if (  $food && $food != "-") {
                        $food = preg_replace("/[^\d]/", "", $food);
                        $data['foods'][$level] = $food;
                    }

                }

                if ($index_ores !== null) {
                    $ore = pq($row)->find('td')->eq($index_ores)->text();

                    if (  $ore && $ore != "-") {
                        $ore = preg_replace("/[^\d]/", "", $ore);
                        $data['ores'][$level] = $ore;
                    }

                }

                if ($index_times !== null) {

                    $str_time = pq($row)->find('td')->eq($index_times)->text();
                    $total_second = ParserHelper::convertTimeToTotalSecond($str_time);
                    $data['times'][$level] = $total_second;
                }

                if ($index_requirements !== null) {
                    $requirements = pq($row)->find('td')->eq($index_requirements)->html();
                    if (  $requirements && $requirements != "-") {
                        $requirements = strip_tags($requirements);
                        $requirements = trim($requirements);
                        $requirements = str_replace("\n", ",", $requirements);
                        $data['requirements'][$level] = $requirements;
                    }

                }

                if ($index_masters_hammer !== null) {
                    $master_hammer = pq($row)->find('td')->eq($index_masters_hammer)->text();

                    if (  $master_hammer && $master_hammer != "-") {
                        $master_hammer = preg_replace("/[^\d]/", "", $master_hammer);
                        $data['masters_hammers'][$level] = $master_hammer;
                    }

                }

                if ($index_hero_xp !== null) {
                    $hero_xp = pq($row)->find('td')->eq($index_hero_xp)->text();

                    if (  $hero_xp && $hero_xp != "-") {
                        $hero_xp = preg_replace("/[^\d]/", "", $hero_xp);
                        $data['hero_xp'][$level] = $hero_xp;
                    }

                }

                if ($index_power !== null) {
                    $power = pq($row)->find('td')->eq($index_power)->text();

                    if (  $power && $power != "-") {
                        $power = preg_replace("/[^\d]/", "", $power);
                        $data['power'][$level] = $power;
                    }

                }

                foreach ($bonuses as $name_bonus => $index_bonuses) {
                    $bonus = pq($row)->find('td')->eq($index_bonuses)->text();

                    if (  $bonus && $bonus != "-") {
                        $bonus = preg_replace("/[^\d]/", "", $bonus);
                        $data['bonuses'][$name_bonus][$level] = $bonus;
                    }

                }
            }
        }

        $data['title'] = pq($result)->find('.pageContent h1')->text();
        $data['images'][] = self::GOW_HOST . $result->find('.detailImg img')->attr('data-img');
        $data['id'] = preg_replace("/[^0-9]/", '', $url);
        return $data;

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
                $urls[$key] = self::GOW_HOST . $link;
            }
        }
        \phpQuery::unloadDocuments();

        return $urls;
    }

}