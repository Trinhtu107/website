<?php
namespace Smart\SurveyForm\Controller\Adminhtml\Form;

use Exception;
use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Smart\SurveyForm\Model\FormModel;
use Smart\SurveyForm\Model\ResourceModel\Question\CollectionFactory as QuestionFactory;

/**
 * Class Delete
 *
 * @package Smart\SurveyForm\Controller\Adminhtml\Form
 */
class Delete extends Action
{
    /**
     * @var FormModel
     */
    protected $_model;
    /**
     * @var QuestionFactory
     */
    protected $questionFactory;

    /**
     * Delete constructor.
     * @param Action\Context $context
     * @param FormModel $model
     * @param QuestionFactory $questionFactory
     */
    public function __construct(
        Action\Context $context,
        FormModel $model,
        QuestionFactory $questionFactory
    ) {
        parent::__construct($context);
        $this->_model = $model;
        $this->questionFactory = $questionFactory;
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
                $questionCollection = $this->questionFactory->create();
                $question = $questionCollection->addFieldToFilter('form_id', ['eq' => $id])
                    ->addFieldToSelect('*');
                foreach ($question as $item) {
                    $item->delete();
                }
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
