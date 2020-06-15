<?php
/**
 * @project VisitorMaxToolbox
 * @author Nicholas K. Dionysopoulos http://www.cmsmoz.com
 * @license GNU General Public License, version 3 of the license or - at your option - any later version
 * @copyright Copyright (c)2009 Nicholas K. Dionysopoulos. All rights reserved.
 *
 * The Hackling Editor Element: Rich text (WYSIWYG) editor support for Joomla! modules 
 */

// Make sure we are being included by a parent Joomla! file
defined('_JEXEC') or die('Restricted access');

class JElementRichtext extends JElement
{
	function fetchElement($name, $value, &$xmlElement, $control_name)
	{
		$editor =& JFactory::getEditor();
		$editorhtml = $editor->display( $control_name.'['.$name.']',  $value, '100%', '400', '40', '20', false );
		$editsavejs = $editor->save($control_name);
		$legend = JText::_('VM_CUSTOM_MESSAGE_LBL');
		$script = <<<ENDSCRIPT
		// Inject an editor below the parameters, a la Custom HTML module visuals
		window.addEvent('domready', function() {
			var cleardiv = document.createElement('div');
			var fieldset = document.createElement('fieldset');
			var legend = document.createElement('legend');					
			var editor = document.createElement('div');
			document.getElementsByName('adminForm')[0].appendChild(cleardiv);
			document.getElementsByName('adminForm')[0].appendChild(fieldset);
			fieldset.class = "adminForm";
			legend.innerHTML = "Custom Message";
			fieldset.appendChild(legend);
			fieldset.appendChild(editor);
			cleardiv.class='clr';
			editor.innerHTML = 'ΓΑΜΗΣΕ ΜΕ';
			editor.innerHTML = document.getElementById('hacklingeditor').innerHTML;
			var node = document.getElementById('hacklingeditor');
			node.parentNode.removeChild(node);
		});

		window.addEvent('domready', function() {
			document.adminForm.onsubmit = function() {
				$editsavejs
			}
		});
ENDSCRIPT;
		
		$document =& JFactory::getDocument();
		$document->addScriptDeclaration($script);

		$html = JText::_('VM_CUSTOM_MESSAGE_TXT') . <<<ENDHTML
<div id="hacklingeditor" style="display:none">
$editorhtml
</div>
ENDHTML;

		return $html;		
	}
}
?>