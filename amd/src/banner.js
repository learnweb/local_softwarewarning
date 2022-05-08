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
 * Makes the banner closable
 *
 * @module     local_softwarewarning/banner
 * @copyright  2022 Justus Dieckmann WWU
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Init
 */
export function init() {
    // Setup close button.
    document.querySelector('button.close[data-dismiss="banner"]').onclick = (e) => {
        e.preventDefault();
        document.getElementById('banner-parent').remove();
        document.body.classList.remove('withbanner');
        document.cookie = `disablebrowserwarn=true` +
            `;path=${new URL(M.cfg.wwwroot).pathname};SameSite=strict`;
    };
}