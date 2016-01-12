		<?php
			$this->ExtForm->create('Slip');
			$this->ExtForm->defineFieldFunctions();
		?>
		var SlipAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'add')); ?>',
			defaultType: 'textfield',
                        frame:true,
                        fileUpload: true,

			items: [
				 {
                            xtype: 'fileuploadfield',
                            id: 'form-file',
                            emptyText: 'Select Image',
                            fieldLabel: 'Image',
                            name: 'data[Slip][image]',
                            buttonText: '',
                            anchor:'100%',
                            buttonCfg: {
                                iconCls: 'upload-icon'
                            }},
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$this->ExtForm->input('ticket_id', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('order', $options);
				?>			]
		});
		
		var SlipAddWindow = new Ext.Window({
			title: '<?php __('Add Slip'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: SlipAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					SlipAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Slip.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(SlipAddWindow.collapsed)
						SlipAddWindow.expand(true);
					else
						SlipAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					SlipAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							SlipAddForm.getForm().reset();
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
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					SlipAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							SlipAddWindow.close();
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
					SlipAddWindow.close();
				}
			}]
		});
