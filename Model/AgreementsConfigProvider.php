<?php
/**
 * Copyright © Qoliber.
 *
 * @category    Qoliber
 * @package     Qoliber_OptionalTermsAndConditions
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */

declare(strict_types=1);

namespace Qoliber\OptionalTermsAndConditions\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface;
use Magento\CheckoutAgreements\Api\CheckoutAgreementsRepositoryInterface;
use Magento\CheckoutAgreements\Model\AgreementsProvider;
use Magento\CheckoutAgreements\Model\Api\SearchCriteria\ActiveStoreAgreementsFilter
    as SearchCriteriaActiveStoreAgreementsFilter;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Escaper;
use Magento\Store\Model\ScopeInterface;

class AgreementsConfigProvider implements ConfigProviderInterface
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfiguration
     * @param \Magento\CheckoutAgreements\Api\CheckoutAgreementsRepositoryInterface $checkoutAgreementsRepository
     * @param \Magento\Framework\Escaper $escaper
     * @param \Magento\CheckoutAgreements\Api\CheckoutAgreementsListInterface|null $checkoutAgreementsList
     * @param SearchCriteriaActiveStoreAgreementsFilter|null $activeStoreAgreementsFilter
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfiguration,
        protected CheckoutAgreementsRepositoryInterface $checkoutAgreementsRepository,
        protected Escaper $escaper,
        private ?CheckoutAgreementsListInterface $checkoutAgreementsList = null,
        private ?SearchCriteriaActiveStoreAgreementsFilter $activeStoreAgreementsFilter = null
    ) {
        $this->checkoutAgreementsList = $checkoutAgreementsList ?: ObjectManager::getInstance()->get(
            CheckoutAgreementsListInterface::class
        );
        $this->activeStoreAgreementsFilter = $activeStoreAgreementsFilter ?: ObjectManager::getInstance()->get(
            ActiveStoreAgreementsFilter::class
        );
    }

    /**
     * Get Config
     *
     * @return mixed[]
     */
    public function getConfig(): array
    {
        $agreements = [];
        $agreements['checkoutAgreements'] = $this->getAgreementsConfig();

        return $agreements;
    }

    /**
     * Returns agreements config.
     *
     * @return mixed[]
     */
    protected function getAgreementsConfig(): array
    {
        $agreementConfiguration = [];
        $isAgreementsEnabled = $this->scopeConfiguration->isSetFlag(
            AgreementsProvider::PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );

        $agreementsList = $this->checkoutAgreementsList->getList(
            $this->activeStoreAgreementsFilter->buildSearchCriteria()
        );
        $agreementConfiguration['isEnabled'] = (bool)($isAgreementsEnabled && count($agreementsList) > 0);

        foreach ($agreementsList as $agreement) {
            $agreementConfiguration['agreements'][] = [
                'content' => $agreement->getIsHtml()
                    ? $agreement->getContent()
                    : nl2br($this->escaper->escapeHtml($agreement->getContent())),
                'checkboxText' => $this->escaper->escapeHtml($agreement->getCheckboxText()),
                'mode' => $agreement->getMode(),
                'agreementId' => $agreement->getAgreementId(),
                'contentHeight' => $agreement->getContentHeight(),
                'isOptional' => (bool) $agreement->getData('is_optional')
            ];
        }

        return $agreementConfiguration;
    }
}
