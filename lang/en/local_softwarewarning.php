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
 * Strings for plugin 'local_softwarewarning'
 *
 * @package   local_softwarewarning
 * @copyright 2022 Justus Dieckmann WWU
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Browsersupport Warning';
$string['setting:supported'] = 'Supported versions';
$string['setting:enabled'] = 'Enabled?';
$string['setting:browscappath'] = 'Path where the browscappath is';
$string['setting:bannerheading'] = 'Information for banner "<i>{$a}</i>"';
$string['setting:bannertextfor'] = 'Text for banner "<i>{$a}</i>"';
$string['setting:bannerurlfor'] = 'URL for banner "<i>{$a}</i>"';
$string['setting:bannerclosablefor'] = 'Should banner "<i>{$a}</i>" be closable?';
$string['setting:bannerseverityfor'] = 'Severity (color) for banner "<i>{$a}</i>"';
$string['bannername:unrecognized'] = 'Unrecognized';
$string['bannername:deprecated'] = 'Deprecated';
$string['bannername:unsupported'] = 'Unsupported';
$string['testpage'] = 'Testing page';
$string['banner'] = 'Banner';
$string['setbrowserconstraints'] = 'Set Browserconstraints';
$string['download_browscap'] = 'Download the browscap.ini';

// Strings for test page.
$string['admin:test:heading'] = 'Test banners!';
$string['admin:test:reset-banner'] = 'Reset banner to calculated';
$string['admin:test:getbrowser-returns'] = 'get_browser() returns: ';
$string['admin:test:current-browser-version'] = 'Your current browser is \'{$a->browser}\', version \'{$a->version}\', so your calculated banner would be: <b>{$a->banner}</b>';
$string['admin:test:browser-not-determined'] = 'Your browser could not be determined!';
$string['admin:test:browscap-set-to'] = 'The browcap setting in php ini files is set to {$a}';
$string['admin:test:browscap-not-set'] = 'The browcap setting in php ini files is not set!';

// String for browser constraints.
$string['admin:const:browsername'] = 'Browsername';
$string['admin:const:issinglerule'] = 'Same banner for all versions of this browser?';
$string['admin:const:banner-for-all'] = 'Banner for all versions of browser';
$string['admin:const:banner-for-different'] = 'Banner for different versions of browsers:';
$string['admin:const:remove-browserconfig'] = 'Remove this browserconfig';
$string['admin:const:below-version'] = 'Below version (inclusive)';
$string['admin:const:set-banner'] = 'set banner';
