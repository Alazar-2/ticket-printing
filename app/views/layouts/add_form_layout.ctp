<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php __('Abay Bank EATS Connector Pro'); ?>
	</title>
	<?php
            echo $this->Html->meta('icon');

            echo $this->Html->css('default') . "\n";
            echo $this->Html->css('extjs/resources/css/ext-all') . "\n";
            echo $this->Html->css('extjs/ux/css/ux-all') . "\n";

            echo $this->Html->script('extjs/adapter/ext/ext-base') . "\n";
            echo $this->Html->script('extjs/ext-all') . "\n";
            echo $this->Html->script('handleamharic') . "\n";

            echo $scripts_for_layout . "\n";
	?>
	<script>
	Ext.onReady(function() {
            Ext.QuickTips.init();
            Ext.History.init();

            var list_size = 20;
            var view_list_size = 5;
            var editWin = null;
            Ext.Ajax.timeout = 60000;

            <?php echo $content_for_layout; ?>
		
	});
	</script>
	<style type="text/css">
		@font-face {
			font-family: Nyala;
			font-style:  normal;
			font-weight: normal;
			src: url(<?php echo Configure::read('localhost_string'); ?>/files/nyala.eot);
		}
		#content {
			color:#000000;
			font-family:'Nyala','Geez Unicode','GF Zemen Unicode','visual Geez Unicode',Verdana,"BitStream vera Sans",Helvetica,Sans-serif;
			font-size:16px;
		}
	</style>
</head>
<body>
<form id="history-form" class="x-hidden">
    <input type="hidden" id="x-history-field" />
    <iframe id="x-history-frame"></iframe>
</form>
<span id="app-msg" class="x-hidden"></span>

    <?php
		echo $this->Html->script('extjs/ux/ux-all') . "\n";
		echo $this->Html->script('ext_validators') . "\n";
		echo $this->Html->script('calendar-all') . "\n";
		echo $this->Html->script('calendar-list') . "\n";
        
		echo $scripts_for_layout . "\n";
	?>
</body>
</html>