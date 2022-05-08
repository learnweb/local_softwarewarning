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
 * Page for setting browser constraints for local_softwarewarning.
 *
 * @package    local_softwarewarning
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_softwarewarning\local\banner;

require_once(__DIR__ . '/../../config.php');
global $CFG, $PAGE, $OUTPUT;
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_softwarewarning_browserconstraints');
$PAGE->set_url(new moodle_url('/local/softwarewarning/browserconstraints.php'));

$configjson = optional_param('json', null, PARAM_RAW);
if ($configjson) {
    require_sesskey();
    $config = json_decode($configjson, true);
    $validconfig = [];
    foreach ($config as $browser => $versions) {
        if (array_key_exists('all', $versions)) {
            if (!in_array($versions['all'], banner::BANNERS)) {
                throw new coding_exception("${$browser}[all] (${versions['all']} not valid banner");
            }
            $validconfig[$browser] = ['all' => $versions['all']];
        } else {
            $validconfig[$browser] = [];
            krsort($versions);
            foreach ($versions as $version => $banner) {
                if (!is_number($version)) {
                    throw new coding_exception("$version is not a valid number for $browser");
                }
                if (!in_array($banner, banner::BANNERS)) {
                    throw new coding_exception("${$browser}[$version] (${$banner} not valid banner");
                }
                $validconfig[$browser][intval($version)] = $banner;
            }
            krsort($validconfig[$browser]);
        }
    }
    set_config('support', json_encode($validconfig), 'local_softwarewarning');
    \local_softwarewarning\local\banner_manager::reset_support_cache();
    redirect($PAGE->url);
}

$config = json_decode(get_config('local_softwarewarning', 'support'));

$PAGE->requires->js_call_amd('local_softwarewarning/browserconstraintsui', 'init',
    [$config, banner::BANNERS, ['IE', 'Chrome', 'Firefox', 'Safari', 'Opera']]);

echo $OUTPUT->header();

echo html_writer::div('', '', ['id' => 'local_softwarewarning-anchor']);

echo $OUTPUT->footer();
