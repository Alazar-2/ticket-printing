		<?php
			$this->ExtForm->create('Issue');
			$this->ExtForm->defineFieldFunctions();
		?>
		var IssueAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'report')); ?>',
			defaultType: 'textfield',
			items: [
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
                						$options['items'] = $branches;
                					$this->ExtForm->input('branch_id', $options);
                				?>,
{ xtype: 'datefield',
 fieldLabel: 'from Date', 
 name: 'data[Issue][fromDt]', 
 id: 'data[Issue][fromDt]'},

 { xtype: 'datefield',
 fieldLabel: 'to Date',
 name: 'data[Issue][toDt]',
 id: 'data[Issue][toDt]'},
 {
 					xtype: 'combo',
 					store: new Ext.data.ArrayStore({
 						sortInfo: { field: "name", direction: "DSC" },
 						storeId: 'my_array_store',
 						id: 0,
 						fields: ['id','name'],

 						data: [
 						['HTML','HTML'],['EXCEL','EXCEL'],['PDF','PDF']
 						]

 					}),
 					displayField: 'name',
 					typeAhead: true,
 					hiddenName:'data[Issue][type]',
 					id: 'type',
 					name: 'type',
 					mode: 'local',
 					triggerAction: 'all',
 					emptyText: 'Select Type',
 					selectOnFocus:true,
 					valueField: 'id',
 					anchor: '100%',
 					fieldLabel: '<span style="color:red;">*</span> OutPut Type',
 					allowBlank: false,
 					editable: false,
 					lazyRender: true,
 					blankText: 'Your input is invalid.'
 				}
				]
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
			buttons: [{
				text: '<?php __('Display '); ?>',
				handler: function(btn){
					 var form = IssueAddForm.getForm(); // or inputForm.getForm();
					 var el = form.getEl().dom;
					 var target = document.createAttribute("target");
					 target.nodeValue = "_blank";
					 el.setAttributeNode(target);
					 el.action = form.url;
					 el.submit();
				}
			},
{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					IssueAddWindow.close();
				}
			}]
		});
		IssueAddWindow.show();
