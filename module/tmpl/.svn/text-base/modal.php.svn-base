<?php
	defined('_JEXEC') or die('Restricted access');
	$document =& JFactory::getDocument();
	
	switch($params->get('jquerysource'))
	{
		case 'none':
			// Do not load
			break;
			
		case 'local':
			$document->addScript(JURI::base().'/media/v2b/js/jquery-1.3.2.min.js');
			break;
			
		case 'google':
		default:
			$document->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js');
			break;
	}
	// Load our modal library
	$document->addScript( JURI::base().'/media/v2b/js/jquery.simplemodal.js' );
	// Load jQuery Timers
	$document->addScript( JURI::base().'/media/v2b/js/jquery.timers.js' );

	$document->addStylesheet(JURI::base().'/media/v2b/js/modal.css');
	
	$modaltimeout = (int)$params->get('modaltimeout', 5);
?>

<div id="v2bmodal" style="display:none">
	<div class='modal-content'>
		<?php echo $message?>
	</div>
	<script type="text/javascript">
	(function($) {
		$(document).ready(function () {
			// create a modal dialog with the data
			myModal = $('#v2bmodal').modal({
				close: true,
				overlayId: 'modal-overlay',
				containerId: 'modal-container',
				onOpen: function(dialog) {
					dialog.overlay.slideDown('slow', function () {
						dialog.container.slideDown('medium', function () {
							dialog.data.fadeIn('fast', function() {
								<?php if($modaltimeout > 0): ?>
								$(this).oneTime(<?php echo 1000 * $modaltimeout ?>, function() {
									$.modal.close();
								});
								<?php endif; ?>
							})
						})
					})
				},
				
				onClose: function (dialog) {
					dialog.data.fadeOut('fast', function () {
						dialog.container.hide('medium', function () {
							dialog.overlay.slideUp('slow', function () {
								$.modal.close();
							});
						});
					});
				}
			});
		});
	})(jQuery);
	</script>
</div>