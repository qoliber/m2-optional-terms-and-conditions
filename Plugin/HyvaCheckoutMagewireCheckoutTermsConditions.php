<?php
/**
 * Copyright © Qoliber.
 *
 * @category    Qoliber
 * @package     Qoliber_OptionalTermsAndConditions
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */

declare(strict_types=1);

namespace Qoliber\OptionalTermsAndConditions\Plugin;

use Hyva\Checkout\Magewire\Checkout\TermsConditions;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;

class HyvaCheckoutMagewireCheckoutTermsConditions
{
    /**
     * @param \Hyva\Checkout\Magewire\Checkout\TermsConditions $subject
     * @param \Closure $proceed
     * @return void
     */
    public function aroundMount(
        TermsConditions $subject,
        \Closure $proceed
    ): void {
        $subject->termAcceptance = array_map(static function (AgreementInterface $agreement) {
            return !(bool) $agreement->getData('is_optional');
        }, $subject->getAgreements());
    }
}
