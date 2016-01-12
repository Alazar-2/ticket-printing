		<?php
			$this->ExtForm->create('Record');
			$this->ExtForm->defineFieldFunctions();
		?>
		var RecordEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $record['Record']['id'])); ?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $issues;
					$options['value'] = $record['Record']['issue_id'];
					$this->ExtForm->input('issue_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $fields;
					$options['value'] = $record['Record']['field_id'];
					$this->ExtForm->input('field_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $record['Record']['value'];
					$this->ExtForm->input('value', $options);
				?>			]
		});
		
		var RecordEditWindow = new Ext.Window({
			title: '<?php __('Edit Record'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: RecordEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					RecordEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Record.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(RecordEditWindow.collapsed)
						RecordEditWindow.expand(true);
					else
						RecordEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					RecordEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							RecordEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentRecordData();
<?php } else { ?>
							RefreshRecordData();
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
					RecordEditWindow.close();
				}
			}]
		});
