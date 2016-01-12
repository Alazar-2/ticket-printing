//<script>
    var store_keys = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','from_branch','to_branch','key','amount_range'		]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'list_data')); ?>'
	}),	
        sortInfo:{field: 'to_branch', direction: "ASC"},
	groupField: 'from_branch'
    });


    function AddKey() {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'add')); ?>',
            success: function(response, opts) {
                var key_data = response.responseText;
			
                eval(key_data);
			
                KeyAddWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the key add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function EditKey(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'edit')); ?>/'+id,
            success: function(response, opts) {
                var key_data = response.responseText;
			
                eval(key_data);
			
                KeyEditWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the key edit form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function ViewKey(id) {
        Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'view')); ?>/'+id,
            success: function(response, opts) {
                var key_data = response.responseText;

                eval(key_data);

                KeyViewWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the key view form. Error code'); ?>: ' + response.status);
            }
        });
    }

    function DeleteKey(id) {
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'delete')); ?>/'+id,
            success: function(response, opts) {
                Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Key successfully deleted!'); ?>');
                RefreshKeyData();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the key add form. Error code'); ?>: ' + response.status);
            }
	});
    }

    function SearchKey(){
	Ext.Ajax.request({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'search')); ?>',
            success: function(response, opts){
                var key_data = response.responseText;

                eval(key_data);

                keySearchWindow.show();
            },
            failure: function(response, opts) {
                Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the key search form. Error Code'); ?>: ' + response.status);
            }
	});
    }

    function SearchByKeyName(value){
	var conditions = '\'Key.name LIKE\' => \'%' + value + '%\'';
	store_keys.reload({
            params: {
                start: 0,
                limit: list_size,
                conditions: conditions
	    }
	});
    }

    function RefreshKeyData() {
	store_keys.reload();
    }


    if(center_panel.find('id', 'key-tab') != "") {
	var p = center_panel.findById('key-tab');
	center_panel.setActiveTab(p);
    } else {
	var p = center_panel.add({
            title: '<?php __('Keys'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'key-tab',
            xtype: 'grid',
            store: store_keys,
            columns: [
                {header: "<?php __('From Branch'); ?>", dataIndex: 'from_branch', sortable: true},
                {header: "<?php __('To Branch'); ?>", dataIndex: 'to_branch', sortable: true},
                {header: "<?php __('Key'); ?>", dataIndex: 'key', sortable: true},
                {header: "<?php __('Amount Range'); ?>", dataIndex: 'amount_range', sortable: true}
            ],
		
            tbar:[
                {
                    text: 'Excel',
                    icon: 'img/download.gif',
                    handler: function(){
                        window.open('keys/printxl');
                    }
                }
            ],
            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Keys" : "Key"]})'
            })
            ,
            listeners: {
                celldblclick: function(){
                    ViewKey(Ext.getCmp('key-tab').getSelectionModel().getSelected().data.id);
                }
            },
            sm: new Ext.grid.RowSelectionModel({
                singleSelect: false
            }),
           
            bbar: new Ext.PagingToolbar({
                pageSize: 100,
                store: store_keys,
                displayInfo: true,
                displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
                beforePageText: '<?php __('Page'); ?>',
                afterPageText: '<?php __('of {0}'); ?>',
                emptyMsg: '<?php __('No data to display'); ?>'
            })
        });
        p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
            p.getTopToolbar().findById('edit-key').enable();
            p.getTopToolbar().findById('delete-key').enable();
            p.getTopToolbar().findById('view-key').enable();
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-key').disable();
                p.getTopToolbar().findById('view-key').disable();
            }
        });
        p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
            if(this.getSelections().length > 1){
                p.getTopToolbar().findById('edit-key').disable();
                p.getTopToolbar().findById('view-key').disable();
                p.getTopToolbar().findById('delete-key').enable();
            }
            else if(this.getSelections().length == 1){
                p.getTopToolbar().findById('edit-key').enable();
                p.getTopToolbar().findById('view-key').enable();
                p.getTopToolbar().findById('delete-key').enable();
            }
            else{
                p.getTopToolbar().findById('edit-key').disable();
                p.getTopToolbar().findById('view-key').disable();
                p.getTopToolbar().findById('delete-key').disable();
            }
        });
        center_panel.setActiveTab(p);
	
        store_keys.load({
            params: {
                start: 0,          
                limit: 100
            }
        });
	
    }
