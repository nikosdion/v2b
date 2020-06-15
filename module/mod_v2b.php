<?php
/**
 * @project VisitorMaxToolbox
 * @author Nicholas K. Dionysopoulos - http://www.cmsmoz.com
 * @license GNU General Public License, version 3 of the license or - at your option - any later version
 * @copyright Copyright (c)2009 Nicholas K. Dionysopoulos. All rights reserved.
 * 
 * "Visitor Max Toolbox" is the advanced version of our "What would Seth Godin
 * do?" module for Joomla!. It creates a different user experience for new and
 * returning visitors. It achieves that by displaying a customized message -
 * in any of the presentation modes - to new visitors. It differenciates between
 * new and returning visitors by setting a cookie on their browser.
 */

// Make sure we are being included by a parent Joomla! file
defined('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'helpers'.DS.'helper.php');

// Set default values for parameters
$params->def('message','Welcome to our site! Please subscribe to our <a href="[SITE]/index.php?format=feed&type=rss">RSS feed</a>.');
$params->def('newvisits', 3);

// Get "newvisits" parameter
$newvisits = $params->get('newvisits', 3);
if(!is_numeric($newvisits))
{
	$newvisits = 3;
	$params->set('newvisits', 3);
}

// Increase the number of pages the user has viewed in the current session
$uniqueid = $params->get('uniqueid', '');
$session =& JFactory::getSession();
$pageviewspersession = $session->get('pageviews', 0, 'v2b'.$uniqueid);
$pageviewspersession++;
if(!defined('_V2B_LOCK_PAGEVIEW_UPDATE'))
{
	// Only update if no other module instance has done the same thing during this page view
	define('_V2B_LOCK_PAGEVIEW_UPDATE', 1);
	$session->set('pageviews', $pageviewspersession, 'v2b'.$uniqueid);
}

// Get parameters
$maxpagepersession = (int)$params->get('maxpagepersession', '0'); 
$mingroup = (int)$params->get('mingroup',0);
$maxgroup = (int)$params->get('maxgroup',2);

// Get number of visits so far
$visits = modV2BHelper::getVisits($params);

// Calculate view criteria
$display_message = true; // Initialize
// Visit limit criterion
$display_message = $display_message && ( $visits <= $newvisits );
// Pages per session criterion 
if($maxpagepersession > 0) $display_message = $display_message && ( $pageviewspersession <= $maxpagepersession ); // Pages per session limit not reached
// Minimum access level criterion
$user =& JFactory::getUser();
$display_message = $display_message && ( $user->aid >= $mingroup );
// Maximum access level criterion
$display_message = $display_message && ( $user->aid <= $maxgroup );

// Do we have to show the message?
if( $display_message )
{
	// Pre-process the message
	$message = modV2BHelper::preProcessMessage($params->get('message'));
	// Output the message using the template
	jimport('joomla.filesystem.file');
	$template = $params->get('layout', 'default');
	$template_file = JModuleHelper::getLayoutPath('mod_v2b', $template);
	if (JFile::exists($template_file))
	{
		require_once $template_file;
	}
	else
	{
		// Throw error
		$message = "Error: Specified template <tt>$template</tt> does not exist";
		include_once JModuleHelper::getLayoutPath('mod_v2b', '_error');
		return;
	}
}