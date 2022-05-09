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
 * Softwarewarning settings.
 *
 * @package    local_softwarewarning
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_softwarewarning\local\banner;

defined('MOODLE_INTERNAL') || die();
global $ADMIN;

if ($hassiteconfig) {
    $category = new admin_category('local_softwarewarning_category', get_string('pluginname', 'local_softwarewarning'));
    $ADMIN->add('localplugins', $category);
    $settings = new admin_settingpage('local_softwarewarning', get_string('generalsettings', 'local_softwarewarning'));
    $ADMIN->add('local_softwarewarning_category', $settings);

    $ADMIN->add('local_softwarewarning_category', new admin_externalpage('local_softwarewarning_browserconstraints',
        get_string('setbrowserconstraints', 'local_softwarewarning'),
        new moodle_url('/local/softwarewarning/browserconstraints.php')));

    $ADMIN->add('local_softwarewarning_category', new admin_externalpage('local_softwarewarning_testpage',
        get_string('testpage', 'local_softwarewarning'),
        new moodle_url('/local/softwarewarning/testpage.php')));

    if ($ADMIN->fulltree) {
        $settings->add(new admin_setting_configcheckbox('local_softwarewarning/enabled',
            new lang_string('setting:enabled', 'local_softwarewarning'), '', '0'));

        $setting = new admin_setting_configtextarea('local_softwarewarning/support',
            new lang_string('setting:supported', 'local_softwarewarning'),
            null,
            '{"IE": {"all": "unsupported"}, "Firefox": {"90": "deprecated", "80": "unsupported"}}',
            PARAM_TEXT);
        $setting->set_updatedcallback("\\local_softwarewarning\\banner_manager::reset_support_cache");
        $settings->add($setting);
        $settings->add(new admin_setting_configfile('local_softwarewarning/browscappath',
            new lang_string('setting:browscappath', 'local_softwarewarning'), '', ''));

        $banners = [banner::UNRECOGNIZED, banner::DEPRECATED, banner::UNSUPPORTED];

        foreach ($banners as $banner) {
            $settings->add(new admin_setting_heading('local_softwarewarning/banner_' . $banner,
                new lang_string('setting:bannerheading', 'local_softwarewarning',
                new lang_string("bannername:$banner", 'local_softwarewarning')
            ), ''));
            $settings->add(new admin_setting_configtext('local_softwarewarning/banner_' . $banner . '_text',
                new lang_string('setting:bannertextfor', 'local_softwarewarning',
                    new lang_string("bannername:$banner", 'local_softwarewarning')
                ), '', '', PARAM_TEXT));

            $settings->add(new admin_setting_configtext('local_softwarewarning/banner_' . $banner . '_url',
                new lang_string('setting:bannerurlfor', 'local_softwarewarning',
                    new lang_string("bannername:$banner", 'local_softwarewarning')
                ), '', '', PARAM_URL));

            $settings->add(new admin_setting_configcheckbox('local_softwarewarning/banner_' . $banner . '_closable',
                new lang_string('setting:bannerclosablefor', 'local_softwarewarning',
                    new lang_string("bannername:$banner", 'local_softwarewarning')
                ), '', false));

            $settings->add(new admin_setting_configselect('local_softwarewarning/banner_' . $banner . '_severity',
                new lang_string('setting:bannerseverityfor', 'local_softwarewarning',
                    new lang_string("bannername:$banner", 'local_softwarewarning')
                ), '', '', [
                    'info' => 'Info',
                    'warn' => 'Warning',
                    'error' => 'Error'
                ]));
        }
    }
}
