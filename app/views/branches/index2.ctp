//<script>
    var store_parent_branches = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','ats_code','fc_code','bank','created','modified'	
            ]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'list_data', $parent_id)); ?>'	
        }),
        remoteSort: true
    });


    function AddParentBranch() {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'add', $parent_id)); ?>',
            success: function(response, opts) {
                var parent_branch_data = response.responseText;
        
                eval(parent_branch_data);
        
                BranchAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the branch add form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function EditParentBranch(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
            success: function(response, opts) {
                var parent_branch_data = response.responseText;
			
                eval(parent_branch_data);
			
                BranchEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the branch edit form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function ViewBranch(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var branch_data = response.responseText;

                eval(branch_data);

                BranchViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the branch view form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function ViewBranchUsers(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var parent_users_data = response.responseText;

                eval(parent_users_data);

                parentUsersViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
            }
	});
    }


    function DeleteParentBranch(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Branch(s) successfully deleted!'); ?>');
                RefreshParentBranchData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the branch to be deleted. Error code'); ?>: ' + response.status);
            }
	});
    }

    function SearchByParentBranchName(value){
	var conditions = '\'Branch.name LIKE\' => \'%' + value + '%\'';
	store_parent_branches.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
	    }
	});
    }

    function RefreshParentBranchData() {
	store_parent_branches.reload();
    }



    var g = new Ext.grid.GridPanel({
	title: '<?php __('Branches'); ?>',
	store: store_parent_branches,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
        id: 'branchGrid',
	columns: [
            {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
            {header: "<?php __('Ats Code'); ?>", dataIndex: 'ats_code', sortable: true},
            {header: "<?php __('Fc Code'); ?>", dataIndex: 'fc_code', sortable: true},
            {header:"<?php __('bank'); ?>", dataIndex: 'bank', sortable: true},
            {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
            {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
            singleSelect: false
	}),
	viewConfig: {
            forceFit: true
	},
        listeners: {
            celldblclick: function(){
                ViewBranch(Ext.getCmp('branchGrid').getSelectionModel().getSelected().data.id);
            }
        },
	tbar: new Ext.Toolbar({
            items: [{
                    xtype: 'tbbutton',
                    text: '<?php __('Add'); ?>',
                    tooltip:'<?php __('<b>Add Branch</b><br />Click here to create a new Branch'); ?>',
                    icon: 'img/table_add.png',
                    cls: 'x-btn-text-icon',
                    handler: function(btn) {
                        AddParentBranch();
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Edit'); ?>',
                    id: 'edit-parent-branch',
                    tooltip:'<?php __('<b>Edit Branch</b><br />Click here to modify the selected Branch'); ?>',
                    icon: 'img/table_edit.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            EditParentBranch(sel.data.id);
                        };
                    }
                }, ' ', '-', ' ', {
                    xtype: 'tbbutton',
                    text: '<?php __('Delete'); ?>',
                    id: 'delete-parent-branch',
                    tooltip:'<?php __('<b>Delete Branch(s)</b><br />Click here to remove the selected Branch(s)'); ?>',
                    icon: 'img/table_delete.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sm.hasSelection()){
                            if(sel.length==1){
                                Ext.Msg.show({
                                    title: '<?php __('Remove Branch'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            DeleteParentBranch(sel[0].data.id);
                                        }
                                    }
                                });
                            } else {
                                Ext.Msg.show({
                                    title: '<?php __('Remove Branch'); ?>',
                                    buttons: Ext.MessageBox.YESNOCANCEL,
                                    msg: '<?php __('Remove the selected Branch'); ?>?',
                                    icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
                                        if (btn == 'yes'){
                                            var sel_ids = '';
                                            for(i=0;i<sel.length;i++){
                                                if(i>0)
                                                    sel_ids += '_';
                                                sel_ids += sel[i].data.id;
                                            }
                                            DeleteParentBranch(sel_ids);
                                        }
                                    }
                                });
                            }
                        } else {
                            Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
                        };
                    }
                }, ' ','-',' ', {
                    xtype: 'tbsplit',
                    text: '<?php __('View Branch'); ?>',
                    id: 'view-branch2',
                    tooltip:'<?php __('<b>View Branch</b><br />Click here to see details of the selected Branch'); ?>',
                    icon: 'img/table_view.png',
                    cls: 'x-btn-text-icon',
                    disabled: true,
                    handler: function(btn) {
                        var sm = g.getSelectionModel();
                        var sel = sm.getSelected();
                        if (sm.hasSelection()){
                            ViewBranch(sel.data.id);
                        };
                    },
                    menu : {
                        items: [
                            {
                                text: '<?php __('View Users'); ?>',
                                icon: 'img/table_view.png',
                                cls: 'x-btn-text-icon',
                                handler: function(btn) {
                                    var sm = g.getSelectionModel();
                                    var sel = sm.getSelected();
                                    if (sm.hasSelection()){
                                        ViewBranchUsers(sel.data.id);
                                    };
                                }
                            }
                        ]
                    }

                }, ' ', '->', {
                    xtype: 'textfield',
                    emptyText: '<?php __('[Search By Name]'); ?>',
                    id: 'parent_branch_search_field',
                    listeners: {
                        specialkey: function(field, e){
                            if (e.getKey() == e.ENTER) {
                                SearchByParentBranchName(Ext.getCmp('parent_branch_search_field').getValue());
                            }
                        }

                    }
                }, {
                    xtype: 'tbbutton',
                    icon: 'img/search.png',
                    cls: 'x-btn-text-icon',
                    text: 'GO',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                    id: 'parent_branch_go_button',
                    handler: function(){
                        SearchByParentBranchName(Ext.getCmp('parent_branch_search_field').getValue());
                    }
                }, ' '
            ]}),
	bbar: new Ext.PagingToolbar({
            pageSize: list_size,
            store: store_parent_branches,
            lastOptions: store_parent_branches.lastOptions,
            displayInfo: true,
            displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
            beforePageText: '<?php __('Page'); ?>',
            afterPageText: '<?php __('of {0}'); ?>',
            emptyMsg: '<?php __('No data to display'); ?>'
	})
    });
    g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-branch').enable();
	g.getTopToolbar().findById('delete-parent-branch').enable();
        g.getTopToolbar().findById('view-branch2').enable();
	if(this.getSelections().length > 1){
            g.getTopToolbar().findById('edit-parent-branch').disable();
            g.getTopToolbar().findById('view-branch2').disable();
	}
    });
    g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
            g.getTopToolbar().findById('edit-parent-branch').disable();
            g.getTopToolbar().findById('delete-parent-branch').enable();
            g.getTopToolbar().findById('view-branch2').disable();
	}
	else if(this.getSelections().length == 1){
            g.getTopToolbar().findById('edit-parent-branch').enable();
            g.getTopToolbar().findById('delete-parent-branch').enable();
            g.getTopToolbar().findById('view-branch2').enable();
	}
	else{
            g.getTopToolbar().findById('edit-parent-branch').disable();
            g.getTopToolbar().findById('delete-parent-branch').disable();
            g.getTopToolbar().findById('view-branch2').disable();
	}
    });



    var parentBranchesViewWindow = new Ext.Window({
	title: 'Branch Under the selected Item',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
            g
	],

	buttons: [{
		text: 'Close',
		handler: function(btn){
                    parentBranchesViewWindow.close();
		}
            }]
    });

    store_parent_branches.load({
        params: {
            start: 0,    
            limit: list_size
        }
    });