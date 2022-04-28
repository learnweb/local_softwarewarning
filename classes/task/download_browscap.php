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
 * Softwarewarning download browsercap
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_softwarewarning\local\task;

/**
 * Softwarewarning download browscap
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class download_browscap extends \core\task\scheduled_task {

    /**
     * Returns the name of the cron task
     * @return string
     */
    public function get_name() {
        return get_string('download_browscap', 'local_softwarewarning');
    }

    /**
     * Cron task definition.
     * Downloads the browscap
     */
    public function execute() {
        $browscappath = get_config('local_softwarewarning', 'browscappath');

        if (!trim($browscappath)) {
            return;
        }

        // TODO Check version before download.
        /*$ini = parse_ini_file($browscappath, true);

        if ($ini && isset($ini['GJK_Browscap_Version']) && isset($ini['GJK_Browscap_Version']['Version'])) {
            $newversion = intval(file_get_contents('https://browscap.org/version-number'));
            if ($newversion === 0) {
                throw new \file_exception('Could not get version number');
            }
            $currentversion = intval($ini['GJK_Browscap_Version']['Version']);
            if ($currentversion === $newversion) {
                return;
            }
        }*/

        $url = 'https://browscap.org/stream?q=PHP_BrowsCapINI';

        $ch = curl_init($url);

        $fp = fopen($browscappath . '.tmp', 'wb');

        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_exec($ch);

        curl_close($ch);

        fclose($fp);

        rename($browscappath . '.tmp', $browscappath);
    }
}
