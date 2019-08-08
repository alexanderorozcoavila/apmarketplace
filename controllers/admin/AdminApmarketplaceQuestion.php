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

require_once(_PS_MODULE_DIR_.'apmarketplace/classes/ApmarketplaceQuestion.php');

class AdminApmarketplaceQuestionController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->bootstrap = true;
        $this->table = 'apmarketplace_question';
        $this->identifier = 'id_apmarketplace_question';
        $this->className = 'ApmarketplaceQuestion';
        parent::__construct();
    }
    
    public function renderList()
    {
        $this->toolbar_title = $this->l('Order');
        $return = null;
        $return .= $this->renderNewQuestion();
        $return .= parent::renderList();
        $return .= $this->renderQuestionList();
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

        if (Tools::getIsset('deleteapmarketplace_question')) {
            $id_apmarketplace_question = Tools::getValue('id_apmarketplace_question');
            Db::getInstance()->execute('
                DELETE FROM `'._DB_PREFIX_.'apmarketplace_question`
                WHERE `id_apmarketplace_question` = '.(int)$id_apmarketplace_question.'
            ');

            Db::getInstance()->execute('
                DELETE FROM `'._DB_PREFIX_.'apmarketplace_answer`
                WHERE `id_apmarketplace_question` = '.(int)$id_apmarketplace_question.'
            ');
            $this->redirect_after = self::$currentIndex.'&token='.$this->token;
        }

        if (((bool)Tools::isSubmit('submitAddapmarketplace_questionAndStay')) == true) {
            $id_apmarketplace_question = Tools::getValue('id_apmarketplace_question');
            $questions = new ApmarketplaceQuestion($id_apmarketplace_question);
            $questions->validate();

            $answer = new ApmarketplaceAnswer();
            $answer->validate($id_apmarketplace_question);
            $answer->id_apmarketplace_question = $id_apmarketplace_question;
            $answer->id_customer = 0;
            $answer->answer = Tools::getValue('answer');
            $answer->active_ans = 1;
            $answer->add(true, false);
        }
    }

    public function renderForm()
    {
        if (Tools::getIsset('id_apmarketplace_question')) {
            $id_apmarketplace_question = Tools::getValue('id_apmarketplace_question');
            $answers = new ApmarketplaceAnswer();
            $answers = $answers->getAnswerByIDQuestion($id_apmarketplace_question);
            $questions = new ApmarketplaceQuestion();
            $questions = $questions->getByIDQuestion($id_apmarketplace_question);
            if (!empty($questions)) {
                $questions = $questions[0];
            }

            $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Reply Question'),
                    'icon' => 'icon-folder-close'
                ),
                'input' => array(
                    array(
                        'type' => 'question',
                        'name' => 'question',
                        'answers' => $answers,
                        'questions' => $questions,
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
                'buttons' => array(
                    'save-and-stay' => array(
                        'title' => $this->l('Save and Stay'),
                        'name' => 'submitAdd'.$this->table.'AndStay',
                        'type' => 'submit',
                        'class' => 'btn btn-default pull-right',
                        'icon' => 'process-icon-save'
                    )
                )
            );
        }
        return parent::renderForm();
    }

    public function renderNewQuestion()
    {
        $return = null;
        $question = new ApmarketplaceQuestion();
        $questions = $question->getByValidate(0);

        if (count($questions) > 0) {
            $fields_list = $this->getStandardFieldList();
            $actions = array('edit', 'delete');
            $helper = new HelperList();
            $helper->shopLinkType = '';
            $helper->simple_header = true;
            $helper->actions = $actions;
            $helper->show_toolbar = false;
            $helper->module = $this->module;
            $helper->listTotal = count($questions);
            $helper->identifier = 'id_apmarketplace_question';
            $helper->title = $this->l('New Question');
            $helper->table = 'apmarketplace_question';
            $helper->token = $this->token;
            $helper->currentIndex = self::$currentIndex;
            $helper->no_link = true;
            $return .= $helper->generateList($questions, $fields_list);
        }
        return $return;
    }
    
    public function renderQuestionList()
    {
        $question = new ApmarketplaceQuestion();
        $questions = $question->getByValidate(1);
        $fields_list = $this->getStandardFieldList();
        $actions = array('edit', 'delete');
        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->simple_header = true;
        $helper->actions = $actions;
        $helper->show_toolbar = false;
        $helper->module = $this->module;
        $helper->listTotal = count($questions);
        $helper->identifier = 'id_apmarketplace_question';
        $helper->title = $this->l('List Question');
        $helper->table = 'apmarketplace_question';
        $helper->token = $this->token;
        $helper->currentIndex = self::$currentIndex;
        $helper->no_link = true;

        return $helper->generateList($questions, $fields_list);
    }
    
    public function getStandardFieldList()
    {
        return array(
            'id_apmarketplace_question' => array(
                'title' => $this->l('ID'),
                'type' => 'text',
            ),
            'name' => array(
                'title' => $this->l('Product Name'),
                'type' => 'text',
            ),
            'customer' => array(
                'title' => $this->l('Customer Name'),
                'type' => 'text',
            ),
            'content' => array(
                'title' => $this->l('Question'),
                'type' => 'text',
            ),
            'date_ques' => array(
                'title' => $this->l('Date'),
                'type' => 'text',
            ),
        );
    }
}
