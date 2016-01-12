<script>
function selectLanguage(language) {
	location = '<?php echo $this->Html->url(array('controller'=>'about', 'action'=>$this->action)); ?>/' + language;
}
</script>

<span style="margin-left:20px">
English<input type="radio" name="language" onClick="selectLanguage('en');" value="english"<?php echo ($language == 'en')? ' checked': ''; ?>>
አማርኛ<input type="radio" name="language" onClick="selectLanguage('am');" value="amharic"<?php echo ($language == 'am')? ' checked': ''; ?>>
</span>

<table style="width: 100%; border: 0px" cellpadding="0" cellspacing="0">
	<tr>
		<td bgcolor="#0a5387">&nbsp;&nbsp;</td>
		<td bgcolor="#0a5387">&nbsp;</td>
		<td bgcolor="#0a5387">&nbsp;&nbsp;&nbsp;</td>
		<td valign="top" style="background: url('<?php echo Configure::read('localhost_string'); ?>/img/crm.png') repeat-y"><?php echo $this->Html->image('crt.png'); ?></td>
	</tr>
	<tr>
		<td bgcolor="#0a5387">&nbsp;</td>
		<td bgcolor="#0a5387" style="color:white"><h2 style="margin-bottom: 2px"><?php echo ($language == 'en')? $content['AboutContent']['title']: $content['AboutContent']['title_am']; ?></h2>&nbsp;</td>
		<td bgcolor="#0a5387">&nbsp;</td>
		<td bgcolor="#0a5387" style="background: url('<?php echo Configure::read('localhost_string'); ?>/img/crm.png') repeat-y">&nbsp;</td>
	</tr>
	<tr>
		<td bgcolor="#9db6d4">&nbsp;</td>
		<td bgcolor="#9db6d4"><p align="justify"><?php echo ($language == 'en')? $content['AboutContent']['content']: $content['AboutContent']['content_am']; ?></p>&nbsp;</td>
		<td bgcolor="#9db6d4">&nbsp;</td>
		<td bgcolor="#9db6d4" style="background: url('<?php echo Configure::read('localhost_string'); ?>/img/crm.png') repeat-y">&nbsp;</td>
	</tr>
	<tr>
		<td valign="top" style="background: url('<?php echo Configure::read('localhost_string'); ?>/img/cbc.png') repeat-x"><?php echo $this->Html->image('clb.png'); ?></td>
		<td bgcolor="#9db6d4" style="background: url('<?php echo Configure::read('localhost_string'); ?>/img/cbc.png') repeat-x">&nbsp;</td>
		<td bgcolor="#9db6d4" style="background: url('<?php echo Configure::read('localhost_string'); ?>/img/cbc.png') repeat-x">&nbsp;</td>
		<td valign="top"><?php echo $this->Html->image('crb.png'); ?></td>
	</tr>
</table>
