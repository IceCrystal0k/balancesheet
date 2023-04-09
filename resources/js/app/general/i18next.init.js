function I18NextTranslate(options) {
    var _options = {
        lng: 'en',
        fallbackLng: 'en',
        debug: false,
        backend: {
            loadPath: options.translationPath + '{{lng}}.json',
        },
        callback: null,
    };
    options = $.extend({}, _options, options);
    i18n.use(i18nBackend).init(options, options.callback);
}

module.exports = I18NextTranslate;
