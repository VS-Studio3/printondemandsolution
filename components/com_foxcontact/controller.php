<?php
/*
This file is part of "Fox Joomla Extensions".

You can redistribute it and/or modify it under the terms of the GNU General Public License
GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html

You have the freedom:
* to use this software for both commercial and non-commercial purposes
* to share, copy, distribute and install this software and charge for it if you wish.
Under the following conditions:
* You must attribute the work to the original author by leaving untouched the link "powered by",
except if you obtain a "registerd version" http://www.fox.ra.it/forum/14-licensing/151-remove-the-backlink-powered-by-fox-contact.html

Author: Demis Palma
Documentation at http://www.fox.ra.it/forum/2-documentation.html
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
* FoxContact Component Controller
*/
class FoxContactController extends JController
{
	public function display($cachable = false, $urlparams = false)
	{
		$application = JFactory::getApplication("site");
		$menu = $application->getMenu();
		$activemenu = $menu->getActive();
		$view = JRequest::getCmd("view", $this->default_view);

		// When called the form without a valid menu id, hijack to the invalid view
		if ($view == "foxcontact" && !$activemenu)
		{
			$_GET["view"] = "invalid";
			$_REQUEST["view"] = "invalid";
			$GLOBALS['_JREQUEST']["view"]["DEFAULTCMD0"] = "invalid";
		}

		return parent::display($cachable = false, $urlparams = false);
	}
}
