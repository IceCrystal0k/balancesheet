'use strict';
/**
 * class which initialize an autocomplete for products
 */
// Class definition
var ProductService = (function () {
    // Elements
    function initActions(options) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
        });
        let _options = { delay: 400, source: searchProducts, minLength: 3 };
        let acOptions = $.extend(_options, options);

        if (options.selector) {
            $(options.selector).autocomplete(acOptions);
        } else {
            console.warn('a selector is required to initialize autocomplete');
        }
    }

    function searchProducts(request, response) {
        $.ajax({
            url: '/products/suggestions',
            dataType: 'json',
            data: { term: request.term },
            success: function (data) {
                console.log('success');
                response(data);
            },
            error: function (data) {
                console.log('failed', data);
            },
        });
    }

    // Public functions
    return {
        // Initialization
        init: function (options) {
            initActions(options);
        },
    };
})();

export default ProductService;
