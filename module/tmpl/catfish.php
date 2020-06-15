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
	// Load our catfish library
	$document->addScript( JURI::base().'/media/v2b/js/jquery-catfish.js' );
	// Load jQuery Timers
	$document->addScript( JURI::base().'/media/v2b/js/jquery.timers.js' );
	// Add IE6 support	
	$ie6css = <<<ENDSCRIPT
<!--[if lt IE 7]>
	<style type="text/css">
		body {
			overflow-x: hidden;
		}
		div#catfish {
			/* IE5.5+/Win - this is more specific than the IE 5.0 version */
			display: none;
			position: absolute;
			right: auto; bottom: auto;
			left: expression( ( 0 + ( ignoreMe2 = document.documentElement.scrollLeft ? document.documentElement.scrollLeft : document.body.scrollLeft ) ) + 'px' );
			top: expression( ( -0 - catfish.offsetHeight + ( document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight ) + ( ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop ) ) + 'px' );
			padding: 0px;
			margin: 0px;
			overflow: hidden;
		}
	</style>
<![endif]-->
ENDSCRIPT;
	$document->addCustomTag($ie6css);
	
	// Get Catfish parameters

	// Animation type
	$catfishanimation = $params->get('catfishanimation','slide');
	$_validparams = array('slide','fade','none');
	if(!in_array($catfishanimation, $_validparams)) $catfishanimation = 'slide';

	// Height of the <div>
	$catfishheight = (int)$params->get('catfishheight',100);
	if($catfishheight <= 0) $catfishheight = 100;
	
	// Custom class
	$catfishclass = $params->get('catfishclass','');
	if(empty($catfishclass))
	{
		$class = '';
		// Load default catfish styling CSS
		$document->addStylesheet(JURI::base().'/media/v2b/js/catfish.css');
	}
	else
	{
		$class = ' class="'.$catfishclass.'"';
	}
	
	// Auto-close
	$catfishtimeout = (int)$params->get('catfishtimeout', 0);
?>

<div id="catfish"<?php echo $class ?>>
<?php echo $message;?>
<a href="#" id="catfish-close"><?php echo JText::_('VM_LABEL_CLOSE'); ?></a>
<script type="text/javascript">
	(function($) {
		$(window).load(function(){
			$('#catfish').catfish({
				closeLink: '#catfish-close',
				height: <?php echo $catfishheight ?>,
				animation: '<?php echo $catfishanimation ?>'
			});

<?php if($catfishtimeout > 0): ?>
			$(this).oneTime(<?php echo 1000 * $catfishtimeout ?>, function() {
				$('#catfish-close').click();
			});
<?php endif; ?>
		});
	})(jQuery);
</script>
</div>