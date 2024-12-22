/**
 * Copyright © Qoliber.
 *
 * @category    Qoliber
 * @package     Qoliber_OptionalTermsAndConditions
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */

let config = {
    config: {
        mixins: {
            'Magento_CheckoutAgreements/js/view/checkout-agreements': {
                'Qoliber_OptionalTermsAndConditions/js/view/checkout-agreements-mixin': true
            }
        }
    }
};
