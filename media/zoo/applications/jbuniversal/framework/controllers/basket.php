<?php
/**
 * JBZoo is universal CCK based Joomla! CMS and YooTheme Zoo component
 * @category   JBZoo
 * @author     smet.denis <admin@joomla-book.ru>
 * @copyright  Copyright (c) 2009-2012, Joomla-book.ru
 * @license    http://joomla-book.ru/info/disclaimer
 * @link       http://joomla-book.ru/projects/jbzoo JBZoo project page
 */
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__) . '/base.php';

class BasketJBUniversalController extends BaseJBUniversalController
{

    const TIME_BETWEEN_PUBLIC_SUBMISSIONS = 1;
    const SESSION_PREFIX                  = 'ZOO_';

    /**
     * Filter action
     * @throws BasketJBUniversalControllerException
     * @return void
     */
    function index()
    {
        // init
        $this->app->jbdebug->mark('basket::init');
        $this->_init();

        $appId  = $this->_jbreq->get('app_id');
        $Itemid = $this->_jbreq->get('Itemid');



        if (!$appId) {
            throw new BasketJBUniversalControllerException('AppId is no set');
        }

        $appParams = $this->application->getParams();

        if ((int)$appParams->get('global.jbzoo_cart_config.enable', 0) == 0) {
            throw new BasketJBUniversalControllerException('Application is not a basket');
        }

        // get items
        $basketItems = $this->app->jbcart->getAllItems();
        $itemIds     = $this->app->jbcart->getItemIds();
        $items       = JBModelFilter::model()->getZooItemsByIds($itemIds);

        if ($appParams->get('global.jbzoo_cart_config.type-layout')) {
            list($type, $layout) = explode(':', $appParams->get('global.jbzoo_cart_config.type-layout'));
        } else {
            throw new BasketJBUniversalControllerException('Form template is no set');
        }

        if (!JFactory::getUser()->id && (int)$appParams->get('global.jbzoo_cart_config.auth', 0)) {
            $this->setRedirect(JRoute::_($this->app->jbrouter->auth(), false), JText::_('JBZOO_AUTH_PLEASE'));
        }

        $this->basketItems    = $basketItems;
        $this->params         = $this->_params;
        $this->items          = $items;
        $this->appId          = $appId;
        $this->Itemid         = $Itemid;
        $this->errors         = array();
        $this->layout_path    = $layout;
        $this->submissionType = $type;
        $this->appParams      = $appParams;

        if (!$this->template = $this->application->getTemplate()) {
            $this->app->error->raiseError(500, JText::_('No template selected'));

            return;
        }

        // set renderer
        $this->renderer = $this->app->renderer->create('basket')->addPath(array(
            $this->app->path->path('component.site:'),
            $this->template->getPath()
        ));

        $this->type = $this->application->getType($type);
        $this->item = $this->_createEmptyItem($this->type);

        // get submition
        $submissionId     = (int)$appParams->get('global.jbzoo_cart_config.submission-id', 0);
        $this->submission = $this->app->table->submission->get((int)$submissionId);

        if ($this->submission) {

            $this->application = $this->submission->getApplication();

            $layout     = $this->submission->getForm($this->type->id)->get('layout', '');
            $layoutPath = $this->application->getGroup() . '.' . $this->type->id . '.' . $layout;
            $positions  = $this->renderer->getConfig('item')->get($layoutPath, array());

            // get elements from positions
            $elementsConfig = array();
            foreach ($positions as $position) {
                foreach ($position as $element) {
                    if (isset($element['element'])) {
                        $elementsConfig[$element['element']] = $element;
                    }
                }
            }

            $this->template = $this->application->getTemplate();
            $sessionFormKey = self::SESSION_PREFIX . 'SUBMISSION_FORM_' . $this->submission->id;
            if ($post = unserialize($this->app->system->application->getUserState($sessionFormKey))) {
                $this->app->system->application->setUserState($sessionFormKey, null);
                $this->errors = $this->_bind($post, $elementsConfig, $this->item);
            }

        } else {
            throw new BasketJBUniversalControllerException('Submission form is no set');
        }

        $this->app->jbdebug->mark('basket::renderInit');
        $this->getView('basket')->addTemplatePath($this->template->getPath())->setLayout('basket')->display();
        $this->app->jbdebug->mark('basket::display');
    }

    /**
     * Delete item action
     */
    public function clear()
    {
        $this->app->jbcart->removeItems();
        $this->app->jbajax->send();
    }

    /**
     * Clear action
     */
    public function delete()
    {
        $this->_init();

        $itemId    = $this->_jbreq->get('itemid');
        $item      = $this->app->table->item->get($itemId);
        $appParams = $this->application->getParams();

        $this->app->jbcart->removeItem($item);
        $recountResult = $this->app->jbcart->recount($appParams);

        $this->app->jbajax->send($recountResult);
    }

    /**
     * Reload module action
     */
    public function reloadModule()
    {
        $this->_init();

        $moduleId = $this->_jbreq->get('moduleId');
        $html     = $this->app->jbjoomla->renderModuleById($moduleId);

        jexit($html);
    }

    /**
     * Quantity action
     */
    public function quantity()
    {
        $this->_init();

        $value     = (int)$this->_jbreq->get('value');
        $itemId    = (int)$this->_jbreq->get('itemId');
        $item      = $this->app->table->item->get($itemId);
        $appParams = $this->application->getParams();

        $this->app->jbcart->changeQuantity($item, $value);

        $recountResult = $this->app->jbcart->recount($appParams);

        $this->app->jbajax->send($recountResult);
    }

    /**
     * Create order action
     */
    public function createOrder()
    {
        $this->app->request->checkToken() or jexit('Invalid Token');
        $this->_init();

        $post   = $this->app->request->get('post:', 'array');
        $appId  = $this->_jbreq->get('app_id');
        $Itemid = $this->_jbreq->get('Itemid');
        $msg    = '';

        try {
            $application = $this->app->table->application->get($appId);

            if (!$application) {
                throw new BasketJBUniversalControllerException('AppId is no set');
            }

            $appParams = $this->application->getParams();
            list($type, $layoutPath) = explode(':', $appParams->get('global.jbzoo_cart_config.type-layout'));

            $this->type = $application->getType($type);

            $item = $this->_createEmptyItem($this->type, $application);

            if (!$this->type) {
                throw new BasketJBUniversalControllerException('Type is not defined');
            }

            $this->template = $application->getTemplate();
            $this->renderer = $this->app->renderer->create('basket')->addPath(array(
                $this->app->path->path('component.site:'),
                $this->template->getPath()
            ));

            $submissionId = $appParams->get('global.jbzoo_cart_config.submission-id');
            $submission   = $this->app->table->submission->get($submissionId);
            $layout       = $submission->getForm($this->type->id)->get('layout', '');
            $layoutPath   = $application->getGroup() . '.' . $this->type->id . '.' . $layout;
            $positions    = $this->renderer->getConfig('item')->get($layoutPath, array());

            // get elements from positions
            $elementsConfig = array();
            foreach ($positions as $position) {
                foreach ($position as $element) {
                    $elementsConfig[$element['element']] = $element;
                }
            }

            if (isset($post['elements'])) {
                $this->app->request->setVar('elements', $this->app->submission->filterData($post['elements']));
                $post = $this->app->request->get('post:', 'array');
                $post = array_merge($post, $post['elements']);
            }

            foreach ($_FILES as $key => $userfile) {
                if (strpos($key, 'elements_') === 0) {
                    $post[str_replace('elements_', '', $key)]['userfile'] = $userfile;
                }
            }

            $error = $this->_bind($post, $elementsConfig, $item);

            $sessionFormKey = self::SESSION_PREFIX . 'SUBMISSION_FORM_' . $submission->id;

            // save item if it is valid
            if ($error) {
                $this->app->system->application->setUserState($sessionFormKey, serialize($post));
                $this->app->error->raiseWarning(0, JText::_('JBZOO_CART_SUBMIT_ERRROS'));

            } else {
                $user = JFactory::getUser();

                $nowDate     = $this->app->date->create()->toSql();
                $nowDateTime = new DateTime($nowDate);
                $date        = $this->app->date->create()->toSql() . ' (GMT ' . ($nowDateTime->getOffset() / 3600) . ')';

                $item->name        = $this->type->name . ' / ' . $date . ($user->email ? ' / ' . $user->email : '');
                $item->alias       = $this->app->alias->item->getUniqueAlias($item->id, $this->app->string->sluggify($item->name));
                $item->state       = 1;
                $item->modified    = $nowDate;
                $item->modified_by = $user->get('id');

                $timestamp = time();
                if ($timestamp < $this->app->system->session->get('ZOO_LAST_SUBMISSION_TIMESTAMP') + BasketJBUniversalController::TIME_BETWEEN_PUBLIC_SUBMISSIONS) {
                    $this->app->system->application->setUserState($sessionFormKey, serialize($post));
                    throw new BasketJBUniversalControllerException('You are submitting too fast, please try again in a few moments.');
                }

                $this->app->system->session->set('ZOO_LAST_SUBMISSION_TIMESTAMP', $timestamp);

                foreach ($elementsConfig as $element) {
                    if (($element = $item->getElement($element['element'])) && $element instanceof iSubmissionUpload) {
                        $element->doUpload();
                    }
                }

                $item->getParams()->set('config.primary_category', 0);
                $this->app->event->dispatcher->notify($this->app->event->create($item, 'basket:beforesave', array('item' => $item, 'appParams' => $appParams)));
                $this->app->event->dispatcher->notify($this->app->event->create($submission, 'submission:beforesave', array('item' => $item, 'new' => true)));
                $this->app->table->item->save($item);
                $this->app->event->dispatcher->notify($this->app->event->create($item, 'basket:saved', array('item' => $item, 'appParams' => $appParams)));

                $this->app->jbcart->removeItems();

                $orderDetails = JBModelOrder::model()->getDetails($item);
                if ((int)$appParams->get('global.jbzoo_cart_config.payment-enabled') && $orderDetails->getTotalPrice() > 0) {
                    $msg = JText::_('JBZOO_CART_SUCCESS_TO_PAYMENT_MESSAGE');
                    $this->setRedirect(JRoute::_($this->app->jbrouter->basketPayment($Itemid, $appId, $item->id), false));

                    return;

                } else {
                    $msg = JText::_('JBZOO_CART_SUCCESS_MESSAGE');
                    $this->setRedirect(JRoute::_($this->app->jbrouter->basketSuccess($Itemid, $appId), false), $msg);

                    return;
                }
            }

        } catch (BasketJBUniversalControllerException $e) {

            $error = true;
            $this->app->error->raiseWarning(0, (string)JText::_($eq));

        } catch (AppException $e) {

            $error = true;
            $this->app->error->raiseWarning(0, JText::_('There was an error saving your submission, please try again later.'));
            $this->app->error->raiseWarning(0, (string)$e);
        }

        $this->setRedirect(JRoute::_($this->app->jbrouter->basket($Itemid, $appId), false), $msg);
    }

    /**
     * Create empty item
     */
    protected function _createEmptyItem($type, $application = null)
    {

        if (!$application) {
            $application = $this->application;
        }

        // get item
        $item                   = $this->app->object->create('Item');
        $item->application_id   = $application->id;
        $item->type             = $type->id;
        $item->publish_up       = $this->app->date->create()->toSQL();
        $item->publish_down     = $this->app->database->getNullDate();
        $item->access           = $this->app->joomla->getDefaultAccess();
        $item->created          = $this->app->date->create()->toSQL();
        $item->created_by       = JFactory::getUser()->get('id');
        $item->created_by_alias = '';
        $item->state            = 0;
        $item->searchable       = true;
        $item->getParams()
            ->set('config.enable_comments', true)
            ->set('config.primary_category', 0);

        return $item;
    }

    /**
     * Bind data
     * @param array $post
     * @param array $elementsConfig
     * @param Item  $item
     * @return int
     */
    protected function _bind($post, $elementsConfig, $item)
    {
        $errors = 0;

        foreach ($elementsConfig as $elementData) {
            try {

                if (($element = $item->getElement($elementData['element']))) {
                    $params = $this->app->data->create(array_merge(array('trusted_mode' => true), $elementData));
                    $element->bindData($element->validateSubmission($this->app->data->create(@$post[$element->identifier]), $params));
                }

            } catch (AppValidatorException $e) {
                if (isset($element)) {
                    $element->error = $e;
                    $element->bindData(@$post[$element->identifier]);
                }
                $errors++;
            }
        }

        return $errors;
    }

}

class BasketJBUniversalControllerException extends AppException
{
}
