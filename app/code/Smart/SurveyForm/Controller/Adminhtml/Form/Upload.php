<?php

namespace Smart\SurveyForm\Controller\Adminhtml\Form;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Smart\SurveyForm\Model\ImageUploader;

/**
 * Class Upload
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Form
 */
class Upload extends Action
{
    /**
     * @var ImageUploader
     */
    public $imageUploader;

    /**
     * Upload constructor.
     * @param Context $context
     * @param ImageUploader $imageUploader
     */
    public function __construct(
        Context $context,
        ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }

    /**
     * Check allow
     *
     * @return bool
     */
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Smart_Survey::smart_survey_form');
    }

    /**
     * Function Execute
     *
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $attr = 'thumbnail_image';
        $file = [
            $attr => [
                'name' => $_FILES['general']['name'][$attr],
                'type' => $_FILES['general']['type'][$attr],
                'tmp_name' => $_FILES['general']['tmp_name'][$attr],
                'error' => $_FILES['general']['error'][$attr],
                'size' => $_FILES['general']['size'][$attr]
            ]
        ];
        $_FILES = $file;
        $result = $this->imageUploader->saveFileToTmpDir(key($_FILES));
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
