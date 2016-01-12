//<script>
    var store_banks = new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','ats_code','BIC','created','modified'        ]
        }),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'list_data')); ?>'
	}),	
        sortInfo:{field: "name", direction: "ASC"},
	groupField: "ats_code",
        remoteSort: true
    });


    function AddBank() {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var bank_data = response.responseText;
			
                eval(bank_data);
			
                BankAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the bank add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function EditBank(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'edit')); ?>/'+id,
            success: function(response, opts) {
                var bank_data = response.responseText;
			
                eval(bank_data);
			
                BankEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the bank edit form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function ViewBank(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var bank_data = response.responseText;

                eval(bank_data);

                BankViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the bank view form. Error code'); ?>: ' + response.status);
            }
        });
    }
    function ViewParentBranches(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'index2')); ?>/'+id,
            success: function(response, opts) {
                var parent_branches_data = response.responseText;

                eval(parent_branches_data);

                parentBranchesViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the branches view form. Error code'); ?>: ' + response.status);
            }
        });
    }


    function DeleteBank(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Bank successfully deleted!'); ?>');
                RefreshBankData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the bank add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function SearchBank(){
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'search')); ?>',
            success: function(response, opts){
                var bank_data = response.responseText;

                eval(bank_data);

                bankSearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the bank search form. Error Code'); ?>: ' + response.status);
            }
	});
    }

    function SearchByBankName(value){
	var conditions = '\'Bank.name LIKE\' => \'%' + value + '%\'';
	store_banks.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
	    }
	});
    }

    function RefreshBankData() {
	store_banks.reload();
    }


    if(center_panel.find('id', 'bank-tab') != "") {
	var p = center_panel.findById('bank-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add({
            title: '<?php __('Banks'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'bank-tab',
            xtype: 'grid',
            store: store_banks,
            columns: [
                {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
                {header: "<?php __('Ats Code'); ?>", dataIndex: 'ats_code', sortable: true},
                {header: "<?php __('BIC'); ?>", dataIndex: 'BIC', sortable: true},
                {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
                {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
            ],
		
            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Banks" : "Bank"]})'
            })
            ,
            listeners: {
                celldblclick: function(){
                    ViewBank(Ext.getCmp('bank-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
            tbar: new Ext.Toolbar({
			
                items: [{
                        xtype: 'tbbutton',
                        text: '<?php __('Add'); ?>',
                        tooltip:'<?php __('<b>Add Banks</b><br />Click here to create a new Bank'); ?>',
                        icon: 'img/table_add.png',
                        cls: 'x-btn-text-icon',
                        handler: function(btn) {
                            AddBank();
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Edit'); ?>',
                        id: 'edit-bank',
                        tooltip:'<?php __('<b>Edit Banks</b><br />Click here to modify the selected Bank'); ?>',
                        icon: 'img/table_edit.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()){
                                EditBank(sel.data.id);
                            };
                        }
                    }, ' ', '-', ' ', {
                        xtype: 'tbbutton',
                        text: '<?php __('Delete'); ?>',
                        id: 'delete-bank',
                        tooltip:'<?php __('<b>Delete Banks(s)</b><br />Click here to remove the selected Bank(s)'); ?>',
                        icon: 'img/table_delete.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelections();
                            if (sm.hasSelection()){
                                if(sel.length==1){
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Bank'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                DeleteBank(sel[0].data.id);
                                            }
                                        }
                                    });
                                }else{
                                    Ext.Msg.show({
                                        title: '<?php __('Remove Bank'); ?>',
                                        buttons: Ext.MessageBox.YESNO,
                                        msg: '<?php __('Remove the selected Banks'); ?>?',
                                        icon: Ext.MessageBox.QUESTION,
                                        fn: function(btn){
                                            if (btn == 'yes'){
                                                var sel_ids = '';
                                                for(i=0;i<sel.length;i++){
                                                    if(i>0)
                                                        sel_ids += '_';
                                                    sel_ids += sel[i].data.id;
                                                }
                                                DeleteBank(sel_ids);
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
                        text: '<?php __('View Bank'); ?>',
                        id: 'view-bank',
                        tooltip:'<?php __('<b>View Bank</b><br />Click here to see details of the selected Bank'); ?>',
                        icon: 'img/table_view.png',
                        cls: 'x-btn-text-icon',
                        disabled: true,
                        handler: function(btn) {
                            var sm = p.getSelectionModel();
                            var sel = sm.getSelected();
                            if (sm.hasSelection()){
                                ViewBank(sel.data.id);
                            };
                        },
                        menu : {
                            items: [
                                {
                                    text: '<?php __('View Branches'); ?>',
                                    icon: 'img/table_view.png',
                                    cls: 'x-btn-text-icon',
                                    handler: function(btn) {
                                        var sm = p.getSelectionModel();
                                        var sel = sm.getSelected();
                                        if (sm.hasSelection()){
                                            ViewParentBranches(sel.data.id);
                                        };
                                    }
                                }
                            ]
                        }
                    }, ' ', '-',  '->', {
                        xtype: 'textfield',
                        emptyText: '<?php __('[Search By Name]'); ?>',
                        id: 'bank_search_field',
                        listeners: {
                            specialkey: function(field, e){
                                if (e.getKey() == e.ENTER) {
                                    SearchByBankName(Ext.getCmp('bank_search_field').getValue());
                                }
                            }
                        }
                    }, {
                        xtype: 'tbbutton',
                        icon: 'img/search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('GO'); ?>',
                        tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
                        id: 'bank_go_button',
                        handler: function(){
                            SearchByBankName(Ext.getCmp('bank_search_field').getValue());
                        }
                    }, '-', {
                        xtype: 'tbbutton',
                        icon: 'img/table_search.png',
                        cls: 'x-btn-text-icon',
                        text: '<?php __('Advanced Search'); ?>',
                        tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
                        handler: function(){
                            SearchBank();
                        }
                    }
		]}),
            bbar: new Ext.PagingToolbar({
                pageSize: list_size,
                store: store_banks,
                lastOptions: store_banks.lastOptions,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-bank').enable();
            p.getTopToolbar().findById('delete-bank').enable();
            p.getTopToolbar().findById('view-bank').enable();
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-bank').disable();
                p.getTopToolbar().findById('view-bank').disable();
            }
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-bank').disable();
                p.getTopToolbar().findById('view-bank').disable();
                p.getTopToolbar().findById('delete-bank').enable();
            }
            else if(this.getSelections().length == 1){
                p.getTopToolbar().findById('edit-bank').enable();
                p.getTopToolbar().findById('view-bank').enable();
                p.getTopToolbar().findById('delete-bank').enable();
            }
            else{
                p.getTopToolbar().findById('edit-bank').disable();
                p.getTopToolbar().findById('view-bank').disable();
                p.getTopToolbar().findById('delete-bank').disable();
            }
	});
	center_panel.setActiveTab(p);
	
	store_banks.load({
            params: {
                start: 0,          
                limit: list_size
            }
	});
	
    }
