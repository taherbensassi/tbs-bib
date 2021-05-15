// ES6 Modules
const modules = {
    text: require('./modules/text-content.js').default,
};

// initialize modules
Object.keys(modules).forEach((module) => {
    $('body').find(`[data-js-module="${module}"]`).each((i, el) => {
        const $el = $(el);
        const opt = $el.attr('data-js-options');
        $el.data('module', {[module]: new modules[module]($el, opt ? JSON.parse(opt) : {})});
        $el.data('module')[module].initialize();
    });
});

console.log('loaded main.js (tbs_content_elements)');
