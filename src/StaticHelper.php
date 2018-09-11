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
     * @return array|string
     * @throws \Exception
     */
    public static function getAllExtra($file, $searchForString, $baseDir='') {
        $searchFor = explode('-', $searchForString);
        $searchable = null;

        $content = json_decode(file_get_contents($baseDir.'/'.$file), true);
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
        if (is_array($searchable)) {
            $includedFoundSearchables = [];
        } else {
            $includedFoundSearchables = null;
        }

        if (isset($content['extra']['merge-plugin']['include'])) {
            $extraIncludes = $content['extra']['merge-plugin']['include'];
            foreach ($extraIncludes as $file) {
                $inMergedSearchable = self::getAllExtra($file, $searchForString, $baseDir);
                //var_dump($inMergedSearchable);
                if (is_array($searchable)) {
                    if (is_array($inMergedSearchable)) {
                        $includedFoundSearchables = array_replace($includedFoundSearchables, $inMergedSearchable);
                    }
                } else {
                    $includedFoundSearchables = $inMergedSearchable;
                }
            }
        }

        if (is_array($searchable)) {
            if (!is_array($includedFoundSearchables)) {
                throw new \Exception('Merged config values types are incompatible');
            }
            return array_replace($searchable, $includedFoundSearchables);
        } else {
            if (is_string($includedFoundSearchables)) {
                return $includedFoundSearchables;
            } else {
                return $searchable;
            }
        }
    }
}