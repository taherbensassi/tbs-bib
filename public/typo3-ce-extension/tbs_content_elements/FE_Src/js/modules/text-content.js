/**
 * @module appTeaser
 *
 * @author Ilka Klug, THE BRETTINGHAMS GmbH
 */

const Helpers = require('../../../../tbs_provider/FE_Src/js/utils/helpers.js').default;

class AppTeaser {
    constructor($el, obj = {}) {
        this.$el = $el;
        this.options = obj;
    }

    initialize() {
        this.$collapse = this.$el.find('.collapse');

        this.bindEvents();

        this.onResize();

        console.log('initialized module: appTeaser');
    }

    bindEvents() {

        this.$el.on('click mouseenter', 'button', (e) => {
            const $target = $(e.currentTarget);
            if ($target.hasClass('collapsed')) {
                this.$collapse.collapse('hide');
                this.$el.find($target.data('target')).collapse('show');
            } else {
                e.stopPropagation();
                return false;
            }
        });

        $(window).on('resize', Helpers.debounce(this, this.onResize, 250));

    }

    onResize() {
        if (Helpers.isDesktop()) {
            Helpers.setMinHeight(this.$el, '.app-teaser__list, .app-teaser__body, .image');
        } else {
            Helpers.setMinHeight(this.$el, '.app-teaser__list, .app-teaser__body');
        }
    }

}

export default AppTeaser;