
var store_issues = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','ticket','user','status','created'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'list_data')); ?>'
	})
});


function AddIssue() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var issue_data = response.responseText;
			
			eval(issue_data);
			
			IssueAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditIssue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var issue_data = response.responseText;
			
			eval(issue_data);
			
			IssueEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue edit form. Error code'); ?>: ' + response.status);
		}
	});
}
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
function PrintIssue(id) {
	
     var url='<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'printv')); ?>/'+id;
     window.open(url,'_blank');
}

function ViewIssue(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var issue_data = response.responseText;

            eval(issue_data);

            IssueViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue view form. Error code'); ?>: ' + response.status);
        }
    });
}

function ViewIssue2(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'view2')); ?>/'+id,
        success: function(response, opts) {
            var issue_data = response.responseText;

            eval(issue_data);

            IssueView2Window.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteIssue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Issue successfully deleted!'); ?>');
			RefreshIssueData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchIssue(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'search')); ?>',
		success: function(response, opts){
			var issue_data = response.responseText;

			eval(issue_data);

			issueSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the issue search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByIssueName(value){
	var conditions = '\'Issue.name LIKE\' => \'%' + value + '%\'';
	store_issues.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshIssueData() {
	store_issues.reload();
}


if(center_panel.find('id', 'issue-tab') != "") {
	var p = center_panel.findById('issue-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Issues'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'issue-tab',
		xtype: 'grid',
		store: store_issues,
		columns: [
			{header: "<?php __('Ticket'); ?>", dataIndex: 'ticket', sortable: true},
			{header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true},
			{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Issues" : "Issue"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewIssue2(Ext.getCmp('issue-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [ '<?php __('Print Ticket'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							<?php $st = false;foreach ($tickets as $item){if($st) echo ",
							";?>['<?php echo $item['Ticket']['id']; ?>' ,'<?php echo $item['Ticket']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
                                                IssueTicket(combo.getValue());
						
						}
					}
				},'-', ' ','<?php __('Filter Ticket'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($tickets as $item){if($st) echo ",
							";?>['<?php echo $item['Ticket']['id']; ?>' ,'<?php echo $item['Ticket']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_issues.reload({
								params: {
									start: 0,
									limit: list_size,
									ticket_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'issue_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByIssueName(Ext.getCmp('issue_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'issue_go_button',
					handler: function(){
						SearchByIssueName(Ext.getCmp('issue_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchIssue();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_issues,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-issue').enable();
		p.getTopToolbar().findById('delete-issue').enable();
		p.getTopToolbar().findById('view-issue').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-issue').disable();
			p.getTopToolbar().findById('view-issue').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-issue').disable();
			p.getTopToolbar().findById('view-issue').disable();
			p.getTopToolbar().findById('delete-issue').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-issue').enable();
			p.getTopToolbar().findById('view-issue').enable();
			p.getTopToolbar().findById('delete-issue').enable();
		}
		else{
			p.getTopToolbar().findById('edit-issue').disable();
			p.getTopToolbar().findById('view-issue').disable();
			p.getTopToolbar().findById('delete-issue').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_issues.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
