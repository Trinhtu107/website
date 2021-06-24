<?php
/**
 * User: tutv
 * Date: 18/06/2021 16:14
 */

namespace Smart\Promotions\Controller\Adminhtml\Promotions;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Smart\SurveyForm\Model\ImageUploader;

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
     * Function Execute
     *
     * @return ResponseInterface|ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $attr = 'thumbnail_image';

        if (array_key_exists('image',$_FILES)) {
            $file = [
                $attr => [
                    'name' => $_FILES['image']['name'],
                    'type' => $_FILES['image']['type'],
                    'tmp_name' => $_FILES['image']['tmp_name'],
                    'error' => $_FILES['image']['error'],
                    'size' => $_FILES['image']['size']
                ]
            ];
        }

        if (array_key_exists('imageDetail',$_FILES)) {
            $file = [
                $attr => [
                    'name' => $_FILES['imageDetail']['name'],
                    'type' => $_FILES['imageDetail']['type'],
                    'tmp_name' => $_FILES['imageDetail']['tmp_name'],
                    'error' => $_FILES['imageDetail']['error'],
                    'size' => $_FILES['imageDetail']['size']
                ]
            ];
        }

        $_FILES = $file;
        $result = $this->imageUploader->saveFileToTmpDir(key($_FILES));
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}
