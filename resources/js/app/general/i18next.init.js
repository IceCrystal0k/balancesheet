function I18NextTranslate(options) {
    var _options = {
        lng: 'en',
        fallbackLng: 'en',
        debug: false,
        resGetPath: options.translationPath + '__lng__/__ns__.json',
        lngWhitelist: ['ro', 'en', 'fr', 'de'],
        selector: '#kt_body',
        callback: null,
    };

    options = $.extend({}, _options, options);

    i18n.init(options).done(function () {
        $(options.selector).i18n();
        if (options.callback != null) options.callback();
    });
}

module.exports = I18NextTranslate;
