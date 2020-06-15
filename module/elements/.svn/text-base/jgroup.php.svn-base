<?php
/**
 * @project VisitorMaxToolbox
 * @author Nicholas K. Dionysopoulos http://www.cmsmoz.com
 * @license GNU General Public License, version 3 of the license or - at your option - any later version
 * @copyright Copyright (c)2009 Nicholas K. Dionysopoulos. All rights reserved.
 *
 * Joomla! Group Selection Element for Joomla! modules 
 */

// Make sure we are being included by a parent Joomla! file
defined('_JEXEC') or die('Restricted access');

class JElementJgroup extends JElement
{

	function fetchElement($name, $value, &$xmlElement, $control_name)
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT id AS value, name AS text'
		. ' FROM #__groups'
		. ' ORDER BY id';

		$db->setQuery( $query );
		$groups = $db->loadObjectList();
		
		$access = JHTML::_('select.genericlist', $groups, $control_name.'['.$name.']', 'class="inputbox" size="3"', 'value', 'text', intval( $value ), '', 1 );
		
		return $access;
	}
}