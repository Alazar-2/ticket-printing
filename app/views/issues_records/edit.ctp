		<?php
			$this->ExtForm->create('IssuesRecord');
			$this->ExtForm->defineFieldFunctions();
		?>
		var IssuesRecordEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $issues_record['IssuesRecord']['id'])); ?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$options['value'] = $issues_record['IssuesRecord']['ticket_id'];
					$this->ExtForm->input('ticket_id', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $fields;
					$options['value'] = $issues_record['IssuesRecord']['field_id'];
					$this->ExtForm->input('field_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $issues_record['IssuesRecord']['value'];
					$this->ExtForm->input('value', $options);
				?>			]
		});
		
		var IssuesRecordEditWindow = new Ext.Window({
			title: '<?php __('Edit Issues Record'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: IssuesRecordEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					IssuesRecordEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Issues Record.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(IssuesRecordEditWindow.collapsed)
						IssuesRecordEditWindow.expand(true);
					else
						IssuesRecordEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					IssuesRecordEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							IssuesRecordEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentIssuesRecordData();
<?php } else { ?>
							RefreshIssuesRecordData();
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
					IssuesRecordEditWindow.close();
				}
			}]
		});
