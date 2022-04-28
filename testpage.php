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
 * Testing page for local_softwarewarning
 *
 * @package    local_softwarewarning
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
global $CFG, $PAGE, $OUTPUT;
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_softwarewarning_testpage');
$PAGE->set_url(new moodle_url('/local/softwarewarning/testpage.php'));
$browser = get_browser();
$mform = new \local_softwarewarning\local\form\form_manualbanner();
if ($data = $mform->get_data()) {
    $banner = \local_softwarewarning\local\banner::BANNERS[$data->banner];
    $cache = \cache::make('local_softwarewarning', 'banner');
    $cache->set('banner', \local_softwarewarning\local\banner_manager::build_banner($banner));
    redirect($PAGE->url);
}
$action = optional_param('action', null, PARAM_ALPHA);
if ($action) {
    $cache = \cache::make('local_softwarewarning', 'banner');
    $cache->purge_current_user();
    redirect($PAGE->url);
}

echo $OUTPUT->header();

echo $OUTPUT->heading('Test banners!');

$mform->display();

$button = new single_button(new moodle_url($PAGE->url, ['action' => 'reset']),
        'Reset banner to calculated');
echo '<br>';
echo $OUTPUT->render($button);

echo '<br><br><br>';

echo "<pre>get_browser() returns: \n" . json_encode($browser, JSON_PRETTY_PRINT) . '</pre>';
$bannertype = \local_softwarewarning\local\banner_manager::decide_banner_type($browser->browser, $browser->majorver);
if ($browser) {
    echo "=> Your current Browser is " . $browser->browser . ", version " . $browser->majorver .
        ", so your calculated banner would be: <b>" . $bannertype . "<b>";
}
echo $OUTPUT->footer();
