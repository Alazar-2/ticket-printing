		<?php
			$this->ExtForm->create('IssuesFieldsRecord');
			$this->ExtForm->defineFieldFunctions();
		?>
		var IssuesFieldsRecordEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['issueId'];
					$this->ExtForm->input('issueId', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['branchid'];
					$this->ExtForm->input('branchid', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['ticket_id'];
					$this->ExtForm->input('ticket_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['fieldId'];
					$this->ExtForm->input('fieldId', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['label'];
					$this->ExtForm->input('label', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['type'];
					$this->ExtForm->input('type', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $issues_fields_record['IssuesFieldsRecord']['value'];
					$this->ExtForm->input('value', $options);
				?>			]
		});
		
		var IssuesFieldsRecordEditWindow = new Ext.Window({
			title: '<?php __('Edit Issues Fields Record'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: IssuesFieldsRecordEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					IssuesFieldsRecordEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Issues Fields Record.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(IssuesFieldsRecordEditWindow.collapsed)
						IssuesFieldsRecordEditWindow.expand(true);
					else
						IssuesFieldsRecordEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					IssuesFieldsRecordEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							IssuesFieldsRecordEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentIssuesFieldsRecordData();
<?php } else { ?>
							RefreshIssuesFieldsRecordData();
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
					IssuesFieldsRecordEditWindow.close();
				}
			}]
		});
