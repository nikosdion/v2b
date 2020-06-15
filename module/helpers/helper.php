<?php
/**
 * @project VisitorMaxToolbox
 * @author Nicholas K. Dionysopoulos http://www.cmsmoz.com
 * @license GNU General Public License, version 3 of the license or - at your option - any later version
 * @copyright Copyright (c)2009 Nicholas K. Dionysopoulos. All rights reserved.
 *
 * Helper class 
 */

class modV2BHelper
{
	/**
	 * Gets the number of visits a user has made to this site
	 * @return int Number of visits a user has made, this one included
	 */
	function getVisits(&$params)
	{
		$uniqueid = $params->get('uniqueid', '');
		
		$user =& JFactory::getUser();
		if($user->guest)
		{
			// Cookie expiration time, in seconds
			$cookieexpiration = (int)$params->get('cookieexpiration','90');
			$default_expiration = 60*60*24*$cookieexpiration;
			
			// If the cookie is not set, please set it
			if(is_null(JRequest::getVar('v2bvisit'.$uniqueid, null, 'COOKIE')))
			{
				setcookie('v2bvisit'.$uniqueid, 0, time() + $default_expiration);
			}
			
			// How many visits has the vistor made yet?
			$visits = JRequest::getVar('v2bvisit'.$uniqueid, 0, 'COOKIE');
			if(!is_numeric($visits)) $visits = 0; // Make sure it's a number!
		}
		else
		{
			$parameter_name = 'v2bvisit'.$uniqueid;
			$visits = (int)$user->getParam($parameter_name, 0);
		}
		
		// Get the (volatile) session variable indicating last visit date, or yesterday if it's not set...
		$session =& JFactory::getSession();
		jimport('joomla.utilities.date');
		$date_now = new JDate();
		$date_yesterday = new JDate( strtotime(gmdate("M d Y H:i:s", time() - 24*60*60 )) );
		$date_last_visit = new JDate( $session->get('lastvisit', $date_yesterday->toUnix(), 'v2b'.$uniqueid) );
		
		// Increase visit count ONLY if the last visit was at least 24 hours ago
		// (or, effectively, if the session changed)
		if( ($date_last_visit->toUnix() - $date_yesterday->toUnix()) <= 0 )
		{
			$visits++;
			if($user->guest)
			{
				setcookie('sethgodinvisit', $visits, time() + $default_expiration);
				$session->set('lastvisit', $date_now->toUnix(), 'sethgodin');
			}
			else
			{
				$user->setParam($parameter_name, $visits);
			}
		}
		
		// Return the value
		return $visits;
	}
	
	/**
	 * Processes the message, replacing placeholders with their values and running any
	 * plug-ins
	 * @param string $message The message to process
	 * @return string The processed message
	 */
	function preProcessMessage($message)
	{
		// Parse [SITE]
		$site_url = JURI::base();
		$message = str_replace('[SITE]', $site_url, $message);
		
		// Run content plug-ins
		global $mainframe;
		$params		=& $mainframe->getParams('com_content');
		$article = new JObject();
		$article->text = $message;
		$limitstart = 0;
		JPluginHelper::importPlugin('content');
		$dispatcher	=& JDispatcher::getInstance();
		$results = $dispatcher->trigger('onPrepareContent', array (& $article, & $params, $limitstart));
		$message = $article->text;
		
		// Return the value
		return $message;
	}	
}