var config = {
    map: {
        '*': {
            'unveil': 'js/jquery.unveil',
            'catalogBuyNow': 'js/catalog-buynow'
        }
    },
    deps: [
        "js/main"
    ],
    config: {
        mixins: {
            'Magento_Swatches/js/swatch-renderer': {
                'js/swatch-renderer-mixin': true
            }
        }
    }
};