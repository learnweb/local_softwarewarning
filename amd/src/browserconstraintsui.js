/* eslint-disable */

import Templates from 'core/templates';

let anchor, root;

/**
 * Init
 * @param {object} config
 * @param {array} bannertypes
 * @param {array} defaultbrowsers
 */
export async function init(config, bannertypes, defaultbrowsers) {
    anchor = document.getElementById('local_softwarewarning-anchor');
    root = await createBrowserconfigelement();

    const selectEls = root.querySelectorAll('*[data-sw-selected]');
    for (let selectEl of selectEls) {
        selectEl.value = selectEl.getAttribute('data-sw-selected');
    }

    root.oninput = async (e) => {
        const target = e.target;
        const type = target.getAttribute('data-sw-type');
        const browserelement = getAncestorWithClass(target, 'sw-browser');
        console.log(type, e.target);
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
            default:
                console.log(type, e.target);
        }
    }

    root.onclick = async (e) => {
        const versionAncestor = getAncestorWithClass(e.target, 'sw-delete-version');
        console.log(versionAncestor);
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
                if (root.lastElementChild.getAttribute('data-sw-empty') !== 'true') {
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

async function createBrowserconfigelement() {
    return await renderTemplate('local_softwarewarning/browserconfig', {
        "banners": ["supported", "unsupported", "deprecated"],
        "browsers": [
            {
                "i":  0,
                "browsername": "IE",
                "issinglerule": true,
                "singlebanner": "unsupported",
                "multirulebanners": [
                    {
                        "version": "80",
                        "banner": "deprecated"
                    }
                ]
            },
            {
                "i":  1,
                "browsername": "Firefox",
                "issinglerule": false,
                "singlebanner": "unsupported",
                "multirulebanners": [
                    {
                        "version": "90",
                        "banner": "deprecated"
                    },
                    {
                        "version": "70",
                        "banner": "unsupported"
                    }
                ]
            }
        ]
    });
}

async function renderTemplate(template, context) {
    const html = await Templates.render(template, context);
    const div = document.createElement('div');
    div.innerHTML = html;
    return div.firstElementChild;
}

async function appendEmptyBrowserElement() {
    const node = await renderTemplate('local_softwarewarning/browserconfig_browser', {
        "banners": ['supported', 'unsupported', 'deprecated'],
        "i": 123,
        "browsername": "",
        "issinglerule": true,
        "singlebanner": "supported",
        "multirulebanners": [
            {
                "version": "",
                "banner": "supported"
            }
        ]
    });
    root.appendChild(node);
}

async function appendEmptyVersionElement(parentElement) {
    console.log(parentElement);
    const node = await renderTemplate('local_softwarewarning/browserconfig_version', {
        "banners": ['unrecognized', 'supported', 'unsupported', 'deprecated'],
        "version": "",
        "banner": "unrecognized"
    });
    console.log(node);
    parentElement.appendChild(node);
}