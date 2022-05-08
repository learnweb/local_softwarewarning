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

require_once(__DIR__ . '/../../config.php');
global $CFG, $PAGE, $OUTPUT;
require_once($CFG->libdir . '/adminlib.php');

admin_externalpage_setup('local_softwarewarning_browserconstraints');
$PAGE->set_url(new moodle_url('/local/softwarewarning/browserconstraints.php'));

$config = get_config('local_softwarewarning', 'support');

$PAGE->requires->js_call_amd('local_softwarewarning/browserconstraintsui', 'init', [$config, null, null]);

echo $OUTPUT->header();

echo html_writer::div('', '', ['id' => 'local_softwarewarning-anchor']);


echo $OUTPUT->footer();
