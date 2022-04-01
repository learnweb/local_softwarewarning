<?php

function decide_banner() {

}

function render_the_banner() {

}

function local_softwarewarning_before_http_headers() {
    global $PAGE;
    if ($PAGE->theme->name !== 'wwu2019') {
        return;
    }
    $banner = \local_softwarewarning\cache_util::get_banner();
    if ($banner) {
        $PAGE->add_body_class('withbanner');
    }
}

function local_softwarewarning_before_standard_top_of_body_html(): string {
    global $PAGE, $OUTPUT;
    if ($PAGE->theme->name !== 'wwu2019') {
        return '';
    }
    $banner = \local_softwarewarning\cache_util::get_banner();
    if (!$banner) {
        return '';
    }

    $PAGE->requires->js_call_amd('local_softwarewarning/banner', 'init');
    return $OUTPUT->render_from_template('local_softwarewarning/banner', $banner);
}