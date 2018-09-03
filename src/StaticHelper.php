<?php
/**
 * Created by PhpStorm.
 * User: vladitot
 * Date: 03.09.18
 * Time: 16:28
 */

namespace ExtraPlugin;


class StaticHelper
{
    /**
     * Getter params from extra
     * Say them, which key do you want to get. Another time you will able to export them, for example.
     * @param $file
     * @param $searchForString
     * @return array
     */
    public static function getAllExtra($file, $searchForString) {
        $searchFor = explode('-', $searchForString);
        $searchable = [];

        $content = json_decode(file_get_contents($file), true);
        if (isset($content['extra'])) {
            $currentEl = $content['extra'];

            foreach ($searchFor as $item) {
                if (isset($currentEl[$item])) {
                    $currentEl = $currentEl[$item];
                } else {
                    break;
                }
            }
            if ($currentEl != $content['extra']) {
                $searchable = $currentEl;
            }
        }

        if (isset($content['extra']['merge-plugin']['include'])) {
            $extraIncludes = $content['extra']['merge-plugin']['include'];
            $includedFoundSearchables = [];
            foreach ($extraIncludes as $file) {
                $includedFoundSearchables = array_replace($includedFoundSearchables, self::getAllExtra($file, $searchForString));
            }
        } else {
            $includedFoundSearchables = [];
        }
        return array_replace($searchable, $includedFoundSearchables);
    }
}