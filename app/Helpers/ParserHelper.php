<?php

namespace App\Helpers;

class ParserHelper
{


    public static function convertTimeToTotalSecond($strTime = null)
    {

        $total_second = 0;
        $one_minute = (int)60;
        $one_hour = (int)60 * $one_minute;
        $one_day = (int)24 * $one_hour;

        $time_temp = array_reverse(explode(" ", $strTime));

        if (is_array($time_temp)) {
            foreach ($time_temp as $item) {

                if (strpos($item, 's') !== false) {
                    $second = (int)$item;

                    if ($second > 0 && $second <= 60) {
                        $total_second += (int)$item;
                    }

                    continue;
                }


                if (strpos($item, 'm') !== false) {
                    $minute = (int)$item;

                    if ($minute > 0 && $minute <= 60) {
                        $total_second += (int)$minute * $one_minute;
                    }
                    continue;
                }

                if (strpos($item, 'h') !== false) {
                    $hour = (int)$item;
                    if ($hour > 0 && $hour <= 24) {
                        $total_second += (int)$hour * $one_hour;
                    }

                    continue;
                }

                if (strpos($item, 'd') !== false) {
                    $day = (int)$item;

                    if ($day > 0 && $day <= 31) {
                        $total_second += (int)$day * $one_day;
                    }
                    continue;
                }

            }
        }

        return $total_second;

    }

}