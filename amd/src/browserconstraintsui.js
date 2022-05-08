/* eslint-disable */

import Templates from 'core/templates';

let anchor, configroot, bannertypes, defaultbrowsers;

/**
 * Init
 * @param {object} _config
 * @param {array} _bannertypes
 * @param {array} _defaultbrowsers
 */
export async function init(_config, _bannertypes, _defaultbrowsers) {
    bannertypes = _bannertypes;
    defaultbrowsers = _defaultbrowsers;

    anchor = document.getElementById('local_softwarewarning-anchor');
    const root = await createBrowserConfigElement(_config);
    configroot = root.querySelector('#local_softwarewarning-config');
    const savebtn = root.querySelector('#local_softwarewarning-save');

    savebtn.onclick = () => {
        const configjson = JSON.stringify(getConfigFromDOM());
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '';
        let input = document.createElement('input');
        input.name = 'sesskey';
        input.value = M.cfg.sesskey;
        form.appendChild(input);
        input = document.createElement('input');
        input.name = 'json';
        input.value = configjson;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    const selectEls = configroot.querySelectorAll('*[data-sw-selected]');
    for (let selectEl of selectEls) {
        selectEl.value = selectEl.getAttribute('data-sw-selected');
    }

    configroot.oninput = async (e) => {
        const target = e.target;
        const type = target.getAttribute('data-sw-type');
        const browserelement = getAncestorWithClass(target, 'sw-browser');
        switch (type) {
            case 'name':
                const isEmpty = target.value.length === 0;
                browserelement.setAttribute('data-sw-empty', isEmpty);
                if (!isEmpty && browserelement.nextElementSibling === null) {
                    await appendEmptyBrowserElement();
                }
                break;
            case 'issinglerule':
                browserelement.setAttribute('data-sw-singlerule', target.checked);
                break;
            case 'multi-version':
                const misEmpty = target.value.length === 0;
                const versionelement = getAncestorWithClass(target, 'sw-version');
                versionelement.setAttribute('data-sw-empty', misEmpty);
                if (!misEmpty && versionelement.nextElementSibling === null) {
                    await appendEmptyVersionElement(versionelement.parentElement);
                }
                break;
        }
    }

    configroot.onclick = async (e) => {
        const versionAncestor = getAncestorWithClass(e.target, 'sw-delete-version');
        if (versionAncestor != null) {
            const swverion = getAncestorWithClass(versionAncestor, 'sw-version');
            const parent = swverion.parentElement;
            swverion.remove();
            if (parent.lastElementChild.getAttribute('data-sw-empty') !== 'true') {
                await appendEmptyVersionElement(parent);
            }
        } else {
            const browserAncestor = getAncestorWithClass(e.target, 'sw-delete-browser');
            if (browserAncestor != null) {
                getAncestorWithClass(browserAncestor, 'sw-browser').remove();
                if (configroot.lastElementChild.getAttribute('data-sw-empty') !== 'true') {
                    await appendEmptyBrowserElement();
                }
            }
        }
    }

    anchor.appendChild(root);
}

function getAncestorWithAttribute(node, attribute) {
    while(!node.hasAttribute(attribute)) {
        node = node.parentElement;
    }
    return node;
}

function getAncestorWithClass(node, theclass)  {
    while(!node.classList.contains(theclass)) {
        if (node.parentElement == null) {
            return null;
        }
        node = node.parentElement;
    }
    return node;
}

async function createBrowserConfigElement(config) {
    const context = transformConfig(config);
    return await renderTemplate('local_softwarewarning/browserconfig', context);
}

/**
 *
 * @param {Object} config
 */
function transformConfig(config) {
    const browsers = [];
    for (let browser in config) {
        const versions = config[browser];
        const versionsArr = [];
        for (let version in versions) {
            if (version === 'all')
                continue;

            versionsArr.push({
                version: version,
                banner: versions[version]
            })
        }
        versionsArr.push({
            version: "",
            banner: "unrecognized"
        })
        browsers.push({
            browsername: browser,
            issinglerule: !!versions['all'],
            singlebanner: versions['all'],
            multirulebanners: versionsArr
        })
    }
    browsers.push({
        browsername: '',
        issinglerule: false,
        singlebanner: "unrecognized",
        multirulebanners: [{
            version: "",
            banner: "unrecognized"
        }]
    });
    return {
        "banners": bannertypes,
        "browsers": browsers
    };
}

async function renderTemplate(template, context) {
    const html = await Templates.render(template, context);
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.firstElementChild;
}

async function appendEmptyBrowserElement() {
    const node = await renderTemplate('local_softwarewarning/browserconfig_browser', {
        "banners": bannertypes,
        "browsername": "",
        "issinglerule": false,
        "singlebanner": "unrecognized",
        "multirulebanners": [
            {
                "version": "",
                "banner": "unrecognized"
            }
        ]
    });
    configroot.appendChild(node);
}

async function appendEmptyVersionElement(parentElement) {
    const node = await renderTemplate('local_softwarewarning/browserconfig_version', {
        "banners": bannertypes,
        "version": "",
        "banner": "unrecognized"
    });
    parentElement.appendChild(node);
}

function getConfigFromDOM() {
    const config = {};
    for (let browserdom of configroot.children) {
        const browser = browserdom.querySelector('input[data-sw-type="name"]').value;
        if (browser.length === 0)
            continue;

        const issinglerule = browserdom.getAttribute('data-sw-singlerule') === 'true';
        let versions = {};
        if (issinglerule) {
            versions['all'] = browserdom.querySelector('select[data-sw-type="singlerule"]').value;
        } else {
            const anchor = browserdom.querySelector('.sw-multirule-anchor');
            for (let versiondom of anchor.children) {
                const version = parseInt(versiondom.querySelector('input[data-sw-type="multi-version"]').value);
                if (isNaN(version))
                    continue;
                versions[version] = versiondom.querySelector('select[data-sw-type="multi-banner"]').value;
            }
        }
        config[browser] = versions;
    }
    return config;
}