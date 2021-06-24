<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Smart\SurveyForm\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\ComponentVisibilityInterface;


class FormFieldset extends \Magento\Ui\Component\Form\Fieldset implements ComponentVisibilityInterface
{
    /**
     * @param ContextInterface $context
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        $this->context = $context;

        parent::__construct($context, $components, $data);
    }

    /**
     * Can show customer addresses tab in tabs or not
     *
     * Will return false for not registered customer in a case when admin user created new customer account.
     * Needed to hide addresses tab from create new customer page
     *
     * @return boolean
     */
    public function isComponentVisible(): bool
    {
        $formId = $this->context->getRequestParam('id');
        return (bool)$formId;
    }
}
