<?php
namespace Smart\Promotions\Controller\Adminhtml\Promotions;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Smart\Promotions\Model\Promotions;

/**
 * Class Delete
 *
 * @package Smart\Promotions\Controller\Adminhtml\Promotions
 */
class Delete extends Action
{
    /**
     * @var Promotions
     */
    protected $_model;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param Promotions $model
     */
    public function __construct(
        Action\Context $context,
        Promotions $model
    ) {
        parent::__construct($context);
        $this->_model = $model;
    }

    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_model;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Form deleted'));
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Department does not exist'));
        return $resultRedirect->setPath('*/*/');
    }
}
