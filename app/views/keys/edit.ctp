		<?php
			$this->ExtForm->create('Key');
			$this->ExtForm->defineFieldFunctions();
		?>
		var KeyEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $key['Key']['id'])); ?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $from_branches;
					$options['value'] = $key['Key']['from_branch_id'];
					$this->ExtForm->input('from_branch_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $to_branches;
					$options['value'] = $key['Key']['to_branch_id'];
					$this->ExtForm->input('to_branch_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $key['Key']['key'];
					$this->ExtForm->input('key', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $key['Key']['tt_direction'];
					$this->ExtForm->input('tt_direction', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $key['Key']['amount_range'];
					$this->ExtForm->input('amount_range', $options);
				?>			]
		});
		
		var KeyEditWindow = new Ext.Window({
			title: '<?php __('Edit Key'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: KeyEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					KeyEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Key.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(KeyEditWindow.collapsed)
						KeyEditWindow.expand(true);
					else
						KeyEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					KeyEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							KeyEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentKeyData();
<?php } else { ?>
							RefreshKeyData();
<?php } ?>
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			},{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					KeyEditWindow.close();
				}
			}]
		});
