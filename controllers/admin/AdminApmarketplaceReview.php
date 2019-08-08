<?php
/**
 * 2007-2015 Leotheme
 *
 * NOTICE OF LICENSE
 *
 * Leo feature for prestashop 1.7: ajax cart, review, compare, wishlist at product list
 *
 * DISCLAIMER
 *
 *  @author    leotheme <leotheme@gmail.com>
 *  @copyright 2007-2015 Leotheme
 *  @license   http://leotheme.com - prestashop template provider
 */

require_once(_PS_MODULE_DIR_.'apmarketplace/classes/ApmarketplaceReview.php');

class AdminApmarketplaceReviewController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->table = 'apmarketplace_review';
        $this->identifier = 'id_review';
        $this->className = 'ApmarketplaceReview';
        parent::__construct();
    }
    
    public function renderList()
    {
        $this->toolbar_title = $this->l('Review Criteria');
        $return = null;
        $return .= $this->renderModerateLists();
        $return .= parent::renderList();
        $return .= $this->renderReviewsList();
        return $return;
    }

    public function postProcess()
    {
        if (count($this->errors) > 0) {
            if ($this->ajax) {
                $array = array('hasError' => true, 'errors' => $this->errors[0]);
                die(Tools::jsonEncode($array));
            }
            return;
        }

        if (Tools::isSubmit('deleteapmarketplace_review') && Tools::getValue('id_review')) {
            $id_review = (int) Tools::getValue('id_review');
            $review = new ApmarketplaceReview($id_review);
            $review->delete();
            $this->redirect_after = self::$currentIndex.'&token='.$this->token;
        }

        if (Tools::isSubmit('approveReview') && Tools::getValue('id_review')) {
            $id_review = (int) Tools::getValue('id_review');
            $review = new ApmarketplaceReview($id_review);
            $review->validate();
            $this->redirect_after = self::$currentIndex.'&token='.$this->token;
        }
    }
    
    public function renderModerateLists()
    {
        $return = null;
        $reviews = ApmarketplaceReview::getByValidate(0, false);

        if (count($reviews) > 0) {
            $fields_list = $this->getStandardFieldList();
            $actions = array('approve', 'delete');
            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = $actions;
            $helper->show_toolbar = false;
            $helper->module = $this->module;
            $helper->listTotal = count($reviews);
            $helper->identifier = 'id_review';
            $helper->title = $this->l('Reviews waiting for approval');
            $helper->table = 'apmarketplace_review';
            $helper->token = $this->token;
            $helper->currentIndex = self::$currentIndex;
            $helper->no_link = true;
            $return .= $helper->generateList($reviews, $fields_list);
        }
        return $return;
    }
    
    public function renderReviewsList()
    {
        $reviews = ApmarketplaceReview::getByValidate(1, false);
        $fields_list = $this->getStandardFieldList();
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = array('delete');
        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->listTotal = count($reviews);
        $helper->identifier = 'id_review';
        $helper->title = $this->l('Approved Reviews');
        $helper->table = 'apmarketplace_review';
        $helper->token = $this->token;
        $helper->currentIndex = self::$currentIndex;
        $helper->no_link = true;

        return $helper->generateList($reviews, $fields_list);
    }
    
    public function displayApproveLink($token, $id, $name = null)
    {
        unset($token);
        unset($name);
        $template = $this->createTemplate('list_action_approve.tpl');
        $template->assign(array(
            'href' => $this->context->link->getAdminLink('AdminApmarketplaceReview').'&approveReview&id_review='.$id,
            'action' => $this->l('Approve'),
        ));
        return $template->fetch();
    }
    
    public function getStandardFieldList()
    {
        return array(
            'id_review' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'title' => array(
                'title' => $this->l('Review title'),
                'type' => 'text',
            ),
            'content' => array(
                'title' => $this->l('Review'),
                'type' => 'text',
            ),
            'grade' => array(
                'title' => $this->l('Rating'),
                'type' => 'text',
                'suffix' => '/5',
            ),
            'customer_name' => array(
                'title' => $this->l('Author'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Product'),
                'type' => 'text',
            ),
            'date_add' => array(
                'title' => $this->l('Time of publication'),
                'type' => 'date-time',
            ),
        );
    }
    
    /**
     * PERMISSION ACCOUNT demo@demo.com
     * OVERRIDE CORE
     */
    public function initProcess()
    {
        parent::initProcess();
        
        if (count($this->errors) <= 0) {
            if (!$this->access('delete')) {
                if (Tools::isSubmit('deleteleofeature_product_review')) {
                    $this->errors[] = $this->l('You do not have permission to delete this.');
                }
            }
        }
    }
}
