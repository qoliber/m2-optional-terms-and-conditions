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

use Magento\CheckoutAgreements\Model\AgreementModeOptions;
use Magento\CheckoutAgreements\Model\AgreementsProvider;
use Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory as AgreementCollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class CheckoutAgreementsModelAgreementsProvider
{
    /**
     * @param \Magento\CheckoutAgreements\Model\ResourceModel\Agreement\CollectionFactory $agreementCollectionFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param string[]|int[] $agreementIds
     */
    public function __construct(
        protected AgreementCollectionFactory $agreementCollectionFactory,
        protected StoreManagerInterface $storeManager,
        protected ScopeConfigInterface $scopeConfig,
        private array $agreementIds = []
    ) {
    }

    /**
     * Only fetch is_optional terms and conditions
     *
     * @return int[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function aroundGetRequiredAgreementIds(
        AgreementsProvider $subject,
        \Closure $proceed
    ): array {
        if ($this->scopeConfig->isSetFlag(AgreementsProvider::PATH_ENABLED, ScopeInterface::SCOPE_STORE)) {
            $agreementCollection = $this->agreementCollectionFactory->create();
            $agreementCollection->addStoreFilter($this->storeManager->getStore()->getId());
            $agreementCollection->addFieldToFilter('is_active', 1);
            $agreementCollection->addFieldToFilter('is_optional', 0);
            $agreementCollection->addFieldToFilter('mode', AgreementModeOptions::MODE_MANUAL);
            $this->agreementIds = $agreementCollection->getAllIds();
        }

        return $this->agreementIds;
    }
}
