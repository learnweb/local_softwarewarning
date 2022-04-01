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