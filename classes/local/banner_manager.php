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
 * Banner manager
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_softwarewarning\local;

/**
 * Banner manager
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class banner_manager {

    /**
     * Reset the support cache
     * @return void
     */
    public static function reset_support_cache() {
        $cache = \cache::make('local_softwarewarning', 'support');
        $cache->purge();
        self::build_support_cache();
    }

    /**
     * Returns banner config for specified browser.
     * @param string $browser Browser name to get Config for
     * @return array|false Array of [version => bannername] (sorted descendingly), or false if no browserconfig.
     */
    public static function get_browser_config(string $browser) {
        $cache = \cache::make('local_softwarewarning', 'support');
        if ($cache->get('build') !== true) {
            self::build_support_cache();
        }
        return $cache->get('_' . $browser);
    }

    /**
     * Returns the name of the banner that will be displayed for the given browser and version.
     *
     * @param string $browser The browser.
     * @param string $version The version.
     * @return string Name of the banner. ('supported' for no banner)
     */
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

    /**
     * Builds the support cache from the local_softwarewarning/support config.
     * @return void
     */
    public static function build_support_cache() {
        $cache = \cache::make('local_softwarewarning', 'support');
        $config = get_config('local_softwarewarning', 'support');
        $cache->purge();
        $array = json_decode($config, true);
        foreach ($array as $browser => $bconfig) {
            if (isset($bconfig['all'])) {
                $condition = strtolower($bconfig['all']);
                if (!in_array($condition, banner::BANNERS)) {
                    throw new \coding_exception('condition should be unsupported or deprecated!');
                }
                $cache->set('_' . $browser, ['all' => $condition]);
                continue;
            }
            $buildconfig = [];
            krsort($bconfig);
            foreach ($bconfig as $version => $condition) {
                $condition = strtolower($condition);
                if (!in_array($condition, banner::BANNERS)) {
                    throw new \coding_exception('condition should be unsupported or deprecated!');
                }
                $buildconfig[$version] = $condition;
            }
            $cache->set('_' . $browser, $buildconfig);
        }
        $cache->set('build', true);
    }

    /**
     * Gets the banner for the current user either from cache, or recalculates it on cache miss.
     * @return \stdClass|null the banner
     */
    public static function get_banner() {
        if (get_config('local_softwarewarning', 'enabled') !== '1') {
            return null;
        }
        $cache = \cache::make('local_softwarewarning', 'banner');
        $banner = $cache->get('banner');
        if ($banner === false) {
            $browser = get_browser();
            if (!$browser) {
                $banner = null;
            } else {
                $bannertype = self::decide_banner_type($browser->browser, $browser->majorver);
                $banner = self::build_banner($bannertype);
            }
            $cache->set('banner', $banner);
        }
        if ($banner !== null && $banner->closable && isset($_COOKIE['disablebrowserwarn'])) {
            $banner = null;
            $cache->set('banner', $banner);
        }
        return $banner;
    }

    /**
     * Builds the banner from a specified bannertype.
     * @param string $bannertype The bannertype.
     * @return \stdClass|null The banner or null for no banner.
     */
    public static function build_banner($bannertype) {
        if (!$bannertype || $bannertype == banner::SUPPORTED) {
            return null;
        }
        $banner = new \stdClass();
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
