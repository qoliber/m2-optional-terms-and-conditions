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
use Hyva\Checkout\Model\Magewire\Component\Evaluation\EvaluationResult;
use Hyva\Checkout\Model\Magewire\Component\EvaluationResultFactory;
use Hyva\Checkout\Model\Magewire\Component\EvaluationResultInterface;
use Magento\CheckoutAgreements\Api\Data\AgreementInterface;

class HyvaCheckoutMagewireCheckoutTermsConditions
{
    /**
     * Around Plugin
     *
     * @param \Hyva\Checkout\Magewire\Checkout\TermsConditions $subject
     * @param \Closure $proceed
     * @param \Hyva\Checkout\Model\Magewire\Component\EvaluationResultFactory $resultFactory
     * @return \Hyva\Checkout\Model\Magewire\Component\Evaluation\EvaluationResult
     */
    public function aroundEvaluateCompletion(
        TermsConditions $subject,
        \Closure $proceed,
        EvaluationResultFactory $resultFactory
    ): EvaluationResult {

        $requiredAgreements = $this->getRequiredAgreements($subject);
        $terms = array_filter($subject->termAcceptance, static function ($value, $key) use ($requiredAgreements) {
            return $value === false && in_array($key, $requiredAgreements, true);
        }, ARRAY_FILTER_USE_BOTH);

        if (count($terms) !== 0) {
            /** @todo needs to migrate to "terms-conditions:details:error" eventually. */
            return $resultFactory->createErrorMessageEvent()
                ->withCustomEvent('terms-conditions:details:error')
                ->withMessage('Please accept all terms & conditions')
                ->withDetails([
                    'terms' => array_keys($terms)
                ]);
        }

        return $resultFactory->createSuccess([], 'terms-conditions:details:success');
    }

    /**
     * @param \Hyva\Checkout\Magewire\Checkout\TermsConditions $subject
     * @return int[]
     */
    private function getRequiredAgreements(TermsConditions $subject): array
    {
        $agreements = $subject->getAgreements();

        $requiredAgreements = [];
        foreach ($agreements as $agreement) {
            if (!$agreement->getData('is_optional')) {
                $requiredAgreements[] = (int) $agreement->getId();
            }
        }

        return $requiredAgreements;
    }
}
