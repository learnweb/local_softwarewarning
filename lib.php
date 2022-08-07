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
 * lib.php for local_softwarewarning
 *
 * @package    local_softwarewarning
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Add 'withbanner' to bodyclass if banner is displayed.
 * @return void
 */
function local_softwarewarning_before_http_headers() {
    global $PAGE;
    $PAGE->add_body_class('theme-' . $PAGE->theme->name);
    $banner = \local_softwarewarning\local\banner_manager::get_banner();
    if ($banner) {
        $PAGE->add_body_class('withbanner');
    }
}

/**
 * Display banner.
 * @return string The banner html string.
 */
function local_softwarewarning_before_standard_top_of_body_html(): string {
    global $PAGE, $OUTPUT;
    $banner = \local_softwarewarning\local\banner_manager::get_banner();
    if (!$banner) {
        return '';
    }
    $PAGE->requires->js_call_amd('local_softwarewarning/banner', 'init');
    return $OUTPUT->render_from_template('local_softwarewarning/banner', $banner);
}
