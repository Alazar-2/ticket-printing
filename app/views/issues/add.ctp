		<?php
			$this->ExtForm->create('Issue');
			$this->ExtForm->defineFieldFunctions();
		?>
		var IssueAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$this->ExtForm->input('ticket_id', $options);
				?>			]
		});
		function IssueTicket(id) {
                    Ext.Ajax.request({
                        url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'issue')); ?>/'+id,
                        success: function(response, opts) {
                            var ticket_data = response.responseText;

                            eval(ticket_data);

                            TicketIssueWindow.show();
                        },
                        failure: function(response, opts) {
                            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the ticket view form. Error code'); ?>: ' + response.status);
                        }
                    });
                }
		var IssueAddWindow = new Ext.Window({
			title: '<?php __('Add Issue'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: IssueAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					IssueAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Issue.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(IssueAddWindow.collapsed)
						IssueAddWindow.expand(true);
					else
						IssueAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					IssueAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							IssueAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentIssueData();
<?php } else { ?>
							RefreshIssueData();
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
					IssueAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							IssueAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentIssueData();
<?php } else { ?>
							RefreshIssueData();
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
					IssueAddWindow.close();
				}
			}]
		});
