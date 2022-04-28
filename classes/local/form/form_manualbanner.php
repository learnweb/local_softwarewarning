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
 * A moodle form for selecting a banner.
 *
 * @package    local_softwarewarning
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
namespace local_softwarewarning\local\form;

use local_softwarewarning\banner;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * A moodle form for selecting a banner.
 *
 * @package    local_softwarewarning
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class form_manualbanner extends \moodleform {

    /**
     * Defines forms elements
     */
    public function definition() {
        $mform = $this->_form;
        $mform->disable_form_change_checker();

        $mform->addElement('select', 'banner', get_string('banner', 'local_softwarewarning'),
            banner::banners);
        $this->add_action_buttons(false, get_string('submit'));
    }

}
