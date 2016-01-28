		<?php
			$this->ExtForm->create('IssuesFieldsRecord');
			$this->ExtForm->defineFieldFunctions();
		?>
		var IssuesFieldsRecordAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('issueId', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('branchid', $options);
				?>,
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
					$this->ExtForm->input('fieldId', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('label', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('type', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('value', $options);
				?>			]
		});
		
		var IssuesFieldsRecordAddWindow = new Ext.Window({
			title: '<?php __('Add Issues Fields Record'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: IssuesFieldsRecordAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					IssuesFieldsRecordAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Issues Fields Record.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(IssuesFieldsRecordAddWindow.collapsed)
						IssuesFieldsRecordAddWindow.expand(true);
					else
						IssuesFieldsRecordAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					IssuesFieldsRecordAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							IssuesFieldsRecordAddForm.getForm().reset();
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
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					IssuesFieldsRecordAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							IssuesFieldsRecordAddWindow.close();
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
					IssuesFieldsRecordAddWindow.close();
				}
			}]
		});
