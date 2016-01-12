<script>
function selectLanguage(language) {
	location = '<?php echo $this->Html->url(array('controller'=>'about', 'action'=>$this->action)); ?>/' + language;
}
</script>

<span style="margin-left:20px">
English<input type="radio" name="language" onClick="selectLanguage('en');" value="english"<?php echo ($language == 'en')? ' checked': ''; ?>>
አማርኛ<input type="radio" name="language" onClick="selectLanguage('am');" value="amharic"<?php echo ($language == 'am')? ' checked': ''; ?>>
</span>

<h2><?php echo ($language == 'en')? 'About NMA': 'ስለ ብሔራዊ ሚቲዎሮሎጂ ኤጀንሲ'; ?></h2>
<p>
<?php echo $this->Html->image('about_nma.png'); ?>
</p>
