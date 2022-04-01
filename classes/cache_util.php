<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Softwarewarning cache helper
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_softwarewarning;

/**
 * Softwarewarning cache helper
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cache_util {

    public static function reset_minsupported_cache() {
        $cache = \cache::make('local_softwarewarning', 'support');
        $cache->purge();
        self::build_minsupported_cache();
    }

    public static function get_browser_config(string $browser) {
        $cache = \cache::make('local_softwarewarning', 'support');
        if ($cache->get('build') !== true) {
            self::build_minsupported_cache();
        }
        return $cache->get('_' . $browser);
    }

    public static function decide_banner_type(string $browser, $version) {
        $config = self::get_browser_config($browser);
        if (!$config) {
            return banner::UNRECOGNIZED;
        }
        if (isset($config['all'])) {
            return $config['all'];
        }
        $lastentry = banner::SUPPORTED;
        // Config is sorted descendingly.
        foreach ($config as $maxversion => $type) {
            if ($maxversion < $version) {
                return $lastentry;
            }
            $lastentry = $type;
        }
        return $lastentry;
    }

    public static function build_minsupported_cache() {
        $cache = \cache::make('local_softwarewarning', 'support');
        $config = get_config('local_softwarewarning', 'support');
        $cache->purge();
        $array = json_decode($config, true);
        foreach ($array as $browser => $bconfig) {
            if (isset($bconfig['all'])) {
                $condition = strtolower($bconfig['all']);
                if (!in_array($condition, banner::banners)) {
                    throw new \coding_exception('condition should be unsupported or deprecated!');
                }
                $cache->set('_' . $browser, ['all' => $condition]);
                continue;
            }
            $buildconfig = [];
            krsort($bconfig);
            foreach ($bconfig as $version => $condition) {
                $condition = strtolower($condition);
                if (!in_array($condition, banner::banners)) {
                    throw new \coding_exception('condition should be unsupported or deprecated!');
                }
                $buildconfig[$version] = $condition;
            }
            $cache->set('_' . $browser, $buildconfig);
        }
        $cache->set('build', true);
        return $array;
    }

    public static function get_banner() {
        $cache = \cache::make('local_softwarewarning', 'banner');
        $banner = $cache->get('banner');
        if ($banner === false || true) {
            $banner = self::decide_banner();
            $cache->set('banner', $banner);
        }
        if ($banner !== null && $banner->closable && isset($_COOKIE['disablebrowserwarn'])) {
            $banner = null;
            $cache->set('banner', $banner);
        }
        return $banner;
    }

    public static function decide_banner() {
        $banner = new \stdClass();
        $browser = get_browser();
        if (!$browser) {
            return null;
        }
        $bannertype = self::decide_banner_type($browser->browser, $browser->majorver);
        if ($bannertype == banner::SUPPORTED) {
            return null;
        }

        $banner->text = format_text(get_config('local_softwarewarning', 'banner_' . $bannertype . '_text'),
                FORMAT_MOODLE, ['para' => false]);
        $banner->closable = get_config('local_softwarewarning', 'banner_' . $bannertype . '_closable');
        $banner->severity = get_config('local_softwarewarning', 'banner_' . $bannertype . '_severity');
        if ($url = get_config('local_softwarewarning', 'banner_' . $bannertype . '_url')) {
            $banner->href = (new \moodle_url($url))->out(false);
        }
        return $banner;
    }

}
