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
let isenabled = true;
let iswwutheme = false;
let bannerparent;
let banner;

// Theme wwu2019 stuff.
let fillelement;
let navbar;

/**
 * Init
 */
export function init() {
    iswwutheme = document.body.classList.contains('theme-wwu2019');
    bannerparent = document.getElementById('banner-parent');
    banner = bannerparent.querySelector('.softwarewarning-banner');
    if (iswwutheme) {
        fillelement = bannerparent.querySelector('.fill-element');
        navbar = document.querySelector('nav#main-menu');
    }
    window.onresize = onResize;
    // Setup close button.
    document.querySelector('button.close[data-dismiss="banner"]').onclick = (e) => {
        e.preventDefault();
        isenabled = false;
        document.getElementById('banner-parent').remove();
        document.body.classList.remove('withbanner');
        document.cookie = `disablebrowserwarn=true` +
            `;path=${new URL(M.cfg.wwwroot).pathname};SameSite=strict`;
        if (iswwutheme) {
            navbar.style.top = null;
        } else {
            document.body.style.height = null;
            document.body.style['-ms-transform'] = null;
            document.body.style['-webkit-transform'] = null;
            document.body.style['-moz-transform'] = null;
            document.body.style.transform = null;
        }
    };
    onResize();
}

/**
 * Does things on resize.
 */
function onResize() {
    if (!isenabled) {
        return;
    }
    const height = banner.clientHeight;
    if (iswwutheme) {
        fillelement.style.height = height + 'px';
        navbar.style.top = height + 'px';
    } else {
        document.body.style.height = `calc(100% - ${height}px)`;
        document.body.style['-ms-transform'] = `translateY(${height}px)`;
        document.body.style['-webkit-transform'] = `translateY(${height}px)`;
        document.body.style['-moz-transform'] = `translateY(${height}px)`;
        document.body.style.transform = `translateY(${height}px)`;
        bannerparent.style['-ms-transform'] = `translateY(-${height}px)`;
        bannerparent.style['-webkit-transform'] = `translateY(-${height}px)`;
        bannerparent.style['-moz-transform'] = `translateY(-${height}px)`;
        bannerparent.style.transform = `translateY(-${height}px)`;
    }
}