		<?php
			$this->ExtForm->create('Slip');
			$this->ExtForm->defineFieldFunctions();
		?>
		var SlipEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $slip['Slip']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $slip['Slip']['url'];
					$this->ExtForm->input('url', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$options['value'] = $slip['Slip']['ticket_id'];
					$this->ExtForm->input('ticket_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $slip['Slip']['order'];
					$this->ExtForm->input('order', $options);
				?>			]
		});
		
		var SlipEditWindow = new Ext.Window({
			title: '<?php __('Edit Slip'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: SlipEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					SlipEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Slip.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(SlipEditWindow.collapsed)
						SlipEditWindow.expand(true);
					else
						SlipEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					SlipEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							SlipEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentSlipData();
<?php } else { ?>
							RefreshSlipData();
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
					SlipEditWindow.close();
				}
			}]
		});
