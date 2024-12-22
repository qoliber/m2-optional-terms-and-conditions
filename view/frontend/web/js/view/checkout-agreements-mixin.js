/**
 * Copyright © Qoliber.
 *
 * @category    Qoliber
 * @package     Qoliber_OptionalTermsAndConditions
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */
define([
], function () {
    'use strict';

    return function (originalComponent) {
        return originalComponent.extend({
            defaults: {
                template: 'Qoliber_OptionalTermsAndConditions/checkout/checkout-agreements'
            }
        });
    };
});
