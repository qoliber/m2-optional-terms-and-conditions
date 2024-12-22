<?php
/**
 * Copyright © Qoliber.
 *
 * @category    Qoliber
 * @package     Qoliber_OptionalTermsAndConditions
 * @author      Jakub Winkler <jwinkler@qoliber.com
 */

declare(strict_types=1);

namespace Qoliber\OptionalTermsAndConditions\Block\Adminhtml\Agreement\Edit;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\CheckoutAgreements\Model\AgreementModeOptions;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Store\Model\System\Store;

class Form extends Generic
{
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $_systemStore
     * @param \Magento\CheckoutAgreements\Model\AgreementModeOptions $agreementModeOptions
     * @param array $data
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        protected Store $_systemStore,
        protected AgreementModeOptions $agreementModeOptions,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init class
     *
     * @return void
     */
    protected function _construct(): void
    {
        parent::_construct();

        $this->setId('checkoutAgreementForm');
        $this->setTitle(__('Terms and Conditions Information'));
    }

    /**
     * Prepare form
     *
     * @return \Qoliber\OptionalTermsAndConditions\Block\Adminhtml\Agreement\Edit\Form
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareForm(): Form
    {
        $model = $this->_coreRegistry->registry('checkout_agreement');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Terms and Conditions Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('agreement_id', 'hidden', ['name' => 'agreement_id']);
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name' => 'name',
                'label' => __('Condition Name'),
                'title' => __('Condition Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'is_optional',
            'select',
            [
                'label' => __('Is Optional'),
                'title' => __('Is Optional'),
                'name' => 'is_optional',
                'required' => true,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );

        $fieldset->addField(
            'is_active',
            'select',
            [
                'label' => __('Status'),
                'title' => __('Status'),
                'name' => 'is_active',
                'required' => true,
                'options' => ['1' => __('Enabled'), '0' => __('Disabled')]
            ]
        );

        $fieldset->addField(
            'is_html',
            'select',
            [
                'label' => __('Show Content as'),
                'title' => __('Show Content as'),
                'name' => 'is_html',
                'required' => true,
                'options' => [0 => __('Text'), 1 => __('HTML')]
            ]
        );

        $fieldset->addField(
            'mode',
            'select',
            [
                'label' => __('Applied'),
                'title' => __('Applied'),
                'name' => 'mode',
                'required' => true,
                'options' => $this->agreementModeOptions->getOptionsArray()
            ]
        );

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'name' => 'stores[]',
                    'label' => __('Store View'),
                    'title' => __('Store View'),
                    'required' => true,
                    'values' => $this->_systemStore->getStoreValuesForForm(false, true)
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                ['name' => 'stores[]', 'value' => $this->_storeManager->getStore(true)->getId()]
            );
            $model->setStoreId($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField(
            'checkbox_text',
            'editor',
            [
                'name' => 'checkbox_text',
                'label' => __('Checkbox Text'),
                'title' => __('Checkbox Text'),
                'rows' => '5',
                'cols' => '30',
                'wysiwyg' => false,
                'required' => true
            ]
        );

        $fieldset->addField(
            'content',
            'editor',
            [
                'name' => 'content',
                'label' => __('Content'),
                'title' => __('Content'),
                'style' => 'height:24em;',
                'wysiwyg' => false,
                'required' => true
            ]
        );

        $fieldset->addField(
            'content_height',
            'text',
            [
                'name' => 'content_height',
                'label' => __('Content Height (css)'),
                'title' => __('Content Height'),
                'maxlength' => 25,
                'class' => 'validate-css-length'
            ]
        );

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
