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

/**
 * JBZoo tools controller for back-end
 */
class JBToolsJBuniversalController extends AppController
{

    /**
     * Constructor
     * @param array $default
     */
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->baseurl = $this->app->link(array('controller' => $this->controller), false);
    }

    /**
     * Check database action
     */
    public function checkdb()
    {
        $searchDb = JBModelSearchindex::model();
        $searchDb->checkColumns();
        $totalRows = $searchDb->reIndex();
        $message   = 'JBZoo - total updated lines: ' . $totalRows;
        $this->app->jbupdate->check();

        $this->setRedirect(
            'index.php?' . http_build_query(array(
                'option'     => 'com_zoo',
                'controller' => 'manager',
                'task'       => 'types',
                'group'      => 'jbuniversal'
            )), $message
        );
    }

    /**
     * Toggle update message
     */
    public function toggleUpdate()
    {
        $configPath = $this->app->path->path('jbapp:config') . '/config.php';

        if (!defined('JBZOO_CONFIG_SHOWUPDATE') || JBZOO_CONFIG_SHOWUPDATE) {

            $message = 'Update messages is disabled';
            $this->_saveFile(array(
                'JBZOO_CONFIG_SHOWUPDATE' => 0
            ), $configPath);

        } else {
            $message = 'Update messages is enabled';
            $this->_saveFile(array(
                'JBZOO_CONFIG_SHOWUPDATE' => 1
            ), $configPath);

        }

        $this->setRedirect(
            'index.php?' . http_build_query(array(
                'option'     => 'com_zoo',
                'controller' => 'manager',
                'task'       => 'types',
                'group'      => 'jbuniversal'
            )), $message
        );
    }

    /**
     * Licence form action
     * TODO create normal template for form
     */
    public function licence()
    {
        $username = defined('JBZOO_USERNAME') ? JBZOO_USERNAME : '';
        $password = '';

        if (!$this->app->jbrequest->isPost()
            || empty($_REQUEST['jbuser']['name'])
            || empty($_REQUEST['jbuser']['password'])
        ) {

            if ($this->app->jbrequest->isPost()
                && (empty($_REQUEST['jbuser']['name']) || empty($_REQUEST['jbuser']['password']))
            ) {
                $this->app->jbnotify->warning('Please fill out all fields.');
            }

            ?>
        <h1>Check JBZoo Licence</h1>

        <p>To obtain a license key, visit <a href="http://joomla-book.ru/" target="_blank">Joomla-book.ru</a>
            or write <a href="mailto:admin@joomla-book.ru">admin@joomla-book.ru</a>.</p>

        <p>Please enter your username and password on your application that you received after purchase.</p>

        <form action="index.php" method="post" name="adminForm" id="message-form" class="form-validate"
              autocomplete="off">
            <div class="width-100">
                <fieldset class="adminform">
                    <ul class="adminformlist">
                        <li>
                            <label for="user-name" class="hasTip required" title="">
                                JBZoo username<span class="star">&nbsp;*</span>
                            </label>
                            <input type="text"
                                   name="jbuser[name]"
                                   value="<?php echo $username;?>"
                                   placeholder="Enter you JBZoo username licence"
                                   id="user-name"
                                   class="required"
                                   size="60"
                                   autocomplete="off"/>
                        </li>
                        <li>
                            <label for="user-password" class="hasTip required" title="">
                                Password<span class="star">&nbsp;*</span>
                            </label>
                            <input type="password"
                                   name="jbuser[password]"
                                   value="<?php echo $password;?>"
                                   placeholder="Enter you JBZoo password"
                                   id="user-password"
                                   class="required"
                                   size="60"
                                   autocomplete="off"/>
                        </li>
                        <li>
                            <div class="clr clear"></div>
                            <input type="submit" name="submit" value="Register!">
                        </li>
                    </ul>
                </fieldset>
            </div>
            <input type="hidden" name="tmpl" value="component">
            <input type="hidden" name="option" value="com_zoo">
            <input type="hidden" name="controller" value="jbtools">
            <input type="hidden" name="task" value="licence">
        </form>
        <?php
        } else {

            $host     = preg_replace('#^www\.#', '', $_SERVER['SERVER_NAME']);
            $login    = trim($_POST['jbuser']['name']);
            $password = trim($_POST['jbuser']['password']);
            $hash     = sha1($host . '|' . $login . '|' . $password);

            $params = array(
                'JBZOO_USERNAME' => $login,
                'JBZOO_PASSWORD' => $hash,
            );

            $defaultLicencePath = $this->app->path->path('jbapp:config') . '/licence.php';
            $domainLicencePath  = $this->app->path->path('jbapp:config') . '/licence.' . $host . '.php';

            if ($this->_saveFile($params, $defaultLicencePath) && $this->_saveFile($params, $domainLicencePath)) {
                $this->app->jbnotify->notice('Registration was successful. Thank you!');
                $this->app->jbnotify->notice('Please, close it window');
                $this->app->jbcache->clear('update');
            }

        }
    }

    /**
     * Show payment links
     */
    public function paymentLinks()
    {
        $appId = (int)$this->app->jbrequest->get('app_id');
        $app   = $this->app->table->application->get($appId);

        $baseUrl = JURI::root();

        $resultUrl  = $baseUrl . 'index.php?option=com_zoo&controller=payment&task=paymentcallback&app_id=' . $appId;
        $successUrl = $baseUrl . 'index.php?option=com_zoo&controller=payment&task=paymentsuccess&app_id=' . $appId;
        $failUrl    = $baseUrl . 'index.php?option=com_zoo&controller=payment&task=paymentfail&app_id=' . $appId;

        ?>
    <p><?php echo JText::_('JBZOO_URL_ALL_DESC');?></p>
    <hr>

    <h3>Result URL</h3>
    <p><?php echo JText::_('JBZOO_URL_RESULT_DESC');?></p>
    <textarea rows="3" cols="70" readonly="readonly" style="width:auto;height:auto;"><?php echo $resultUrl;?></textarea>

    <h3>Success URL</h3>
    <p><?php echo JText::_('JBZOO_URL_SUCCESS_DESC');?></p>
    <textarea rows="3" cols="70" readonly="readonly" style="width:auto;height:auto;"><?php echo $successUrl;?></textarea>

    <h3>Fail URL</h3>
    <p><?php echo JText::_('JBZOO_URL_FAIL_DESC');?></p>
    <textarea rows="3" cols="70" readonly="readonly" style="width:auto;height:auto;"><?php echo $failUrl;?></textarea>
    <?php

    }

    /**
     * Save file
     * @param array $params
     * @param $path
     * @return bool
     */
    protected function _saveFile(array $params, $path)
    {

        $fileTemplate = array(
            '<?php',
            '/**',
            ' * JBZoo is universal CCK based Joomla! CMS and YooTheme Zoo component',
            ' * @category   JBZoo',
            ' * @author     smet.denis <admin@joomla-book.ru>',
            ' * @copyright  Copyright (c) 2009-2012, Joomla-book.ru',
            ' * @license    http://joomla-book.ru/info/disclaimer',
            ' * @link       http://joomla-book.ru/projects/jbzoo JBZoo project page',
            ' */',
            '',
            'defined(\'_JEXEC\') or die(\'Restricted access\');',
            '',
        );

        foreach ($params as $key => $value) {

            $constName  = JString::strtoupper($key);
            $constValue = is_string($value) ? "'" . $value . "'" : $value;

            $fileTemplate[] = 'define(\'' . $constName . '\', ' . $constValue . ');';
        }

        $fileTemplate[] = '';

        $fileContent = implode("\n", $fileTemplate);

        if (JFile::exists($path)) {
            JFile::delete($path);
        }

        if (!JFile::write($path, $fileContent)) {
            $this->app->jbnotify->warning('The file is not created, check the permissions on the directory JBZoo.');

            return false;
        }

        return true;
    }

}

class ExceptionJBToolsJBuniversalController extends AppException
{
}
