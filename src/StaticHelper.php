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

        if (isset($content['extra']['merge-plugin']['include'])) {
            $extraIncludes = $content['extra']['merge-plugin']['include'];
            foreach ($extraIncludes as $file) {
                $searchableFromIncluded = self::getAllExtra($file, $searchForString, $baseDir);
                if (is_null($searchableFromIncluded)) {
                    continue;
                }

                if (is_null($searchable)) {
                    $searchable=$searchableFromIncluded;
                    continue;
                }

                if (gettype($searchable) != gettype($searchableFromIncluded)) {
                    throw new \Exception("Types in included files are incompatible");
                } else {
                    if (is_array($searchable)) {
                        $searchable = array_replace($searchable, $searchableFromIncluded);
                    } elseif (is_string($searchable)) {
                        $searchable = $searchableFromIncluded;
                    }
                }


            }
        }
        return $searchable;
    }
}