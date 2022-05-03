/* eslint-disable */

import Templates from 'core/templates';

/**
 * Init
 * @param {object} config
 * @param {array} bannertypes
 * @param {array} defaultbrowsers
 */
export async function init(config, bannertypes, defaultbrowsers) {
    const root = document.getElementById('local_softwarewarning-config');
    const selectEls = root.querySelectorAll('*[data-sw-selected]');
    for (let selectEl of selectEls) {
        selectEl.value = selectEl.getAttribute('data-sw-selected');
    }

    root.oninput = (e, i) => {
        console.log(e, i);
    }

    root.hidden = false;
}

async function wait(ms) {
    return new Promise(resolve => {
        setTimeout(resolve, ms);
    });
}

async function createBrowserconfigelement() {
    const html = await Templates.render('local_softwarewarning/browserconfig', {i: 0, banners: ['ban1', 'ban2']});
    const div = document.createElement('div');
    div.innerHTML = html;
    // console.log(div.firstElementChild);
}