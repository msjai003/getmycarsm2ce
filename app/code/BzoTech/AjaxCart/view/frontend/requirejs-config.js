var config = {
    map: {
        '*': {

            customModal: 'BzoTech_AjaxCart/js/ajaxcart-modal',
            quickView: 'BzoTech_AjaxCart/js/ajaxcart-quickview',
            ajaxCart: 'BzoTech_AjaxCart/js/ajaxcart-addtocart',
            addToCart: 'BzoTech_AjaxCart/js/ajaxcart-msrp',
            sidebar: 'BzoTech_AjaxCart/js/ajaxcart-sidebar',
            compareItems: 'BzoTech_AjaxCart/js/ajaxcart-compare',
            wishlist: 'BzoTech_AjaxCart/js/ajaxcart-wishlist'
        }
    },
    deps: [
        'Magento_Catalog/js/catalog-add-to-cart',
        'Magento_Msrp/js/msrp'
    ]
};