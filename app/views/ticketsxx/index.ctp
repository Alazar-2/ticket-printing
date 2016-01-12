
var store_tickets = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'list_data')); ?>'
	})
});


function AddTicket() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var ticket_data = response.responseText;
			
			eval(ticket_data);
			
			TicketAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the ticket add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditTicket(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var ticket_data = response.responseText;
			
			eval(ticket_data);
			
			TicketEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the ticket edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewTicket(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var ticket_data = response.responseText;

            eval(ticket_data);

            TicketViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the ticket view form. Error code'); ?>: ' + response.status);
        }
    });
}

function IssueTicket(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'issue')); ?>/'+id,
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

function ViewParentFields(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_fields_data = response.responseText;

            eval(parent_fields_data);

            parentFieldsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewParentIssues(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_issues_data = response.responseText;

            eval(parent_issues_data);

            parentIssuesViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewParentSlips(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'index2')); ?>/'+id,
        success: function(response, opts) {
            var parent_slips_data = response.responseText;

            eval(parent_slips_data);

            parentSlipsViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
        }
    });
}


function DeleteTicket(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Ticket successfully deleted!'); ?>');
			RefreshTicketData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the ticket add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchTicket(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'search')); ?>',
		success: function(response, opts){
			var ticket_data = response.responseText;

			eval(ticket_data);

			ticketSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the ticket search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByTicketName(value){
	var conditions = '\'Ticket.name LIKE\' => \'%' + value + '%\'';
	store_tickets.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshTicketData() {
	store_tickets.reload();
}


if(center_panel.find('id', 'ticket-tab') != "") {
	var p = center_panel.findById('ticket-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Tickets'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'ticket-tab',
		xtype: 'grid',
		store: store_tickets,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
		],
		viewConfig: {
			forceFit: true
		}
,
		listeners: {
			celldblclick: function(){
				IssueTicket(Ext.getCmp('ticket-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Tickets</b><br />Click here to create a new Ticket'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddTicket();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-ticket',
					tooltip:'<?php __('<b>Edit Tickets</b><br />Click here to modify the selected Ticket'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditTicket(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-ticket',
					tooltip:'<?php __('<b>Delete Tickets(s)</b><br />Click here to remove the selected Ticket(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Ticket'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteTicket(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Ticket'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Tickets'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteTicket(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: '<?php __('View Ticket'); ?>',
					id: 'view-ticket',
					tooltip:'<?php __('<b>View Ticket</b><br />Click here to see details of the selected Ticket'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewTicket(sel.data.id);
						};
					},
					menu : {
						items: [
{
							text: '<?php __('View Fields'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentFields(sel.data.id);
								};
							}
						}
,{
							text: '<?php __('View Issues'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentIssues(sel.data.id);
								};
							}
						}
,{
							text: '<?php __('View Slips'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentSlips(sel.data.id);
								};
							}
						}
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'ticket_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByTicketName(Ext.getCmp('ticket_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'ticket_go_button',
					handler: function(){
						SearchByTicketName(Ext.getCmp('ticket_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchTicket();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_tickets,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-ticket').enable();
		p.getTopToolbar().findById('delete-ticket').enable();
		p.getTopToolbar().findById('view-ticket').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-ticket').disable();
			p.getTopToolbar().findById('view-ticket').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-ticket').disable();
			p.getTopToolbar().findById('view-ticket').disable();
			p.getTopToolbar().findById('delete-ticket').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-ticket').enable();
			p.getTopToolbar().findById('view-ticket').enable();
			p.getTopToolbar().findById('delete-ticket').enable();
		}
		else{
			p.getTopToolbar().findById('edit-ticket').disable();
			p.getTopToolbar().findById('view-ticket').disable();
			p.getTopToolbar().findById('delete-ticket').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_tickets.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
