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
 * Banner class
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_softwarewarning\local;

/**
 * Banner class
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class banner {

    /** @var string No banner. */
    const SUPPORTED = 'supported';
    /** @var string Banner for unrecognized browsers. */
    const UNRECOGNIZED = 'unrecognized';
    /** @var string Banner for older browser versions. */
    const DEPRECATED = 'deprecated';
    /** @var string Banner for unsupported browsers. */
    const UNSUPPORTED = 'unsupported';

    /** @var string[] List of all banners */
    const BANNERS = [
        self::SUPPORTED,
        self::UNRECOGNIZED,
        self::DEPRECATED,
        self::UNSUPPORTED
    ];

}
