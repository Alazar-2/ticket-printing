//<script>
    var from_store_keys = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','from_branch','to_branch','key','amount_range']
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'list_data', 'from')); ?>'
	}),	
        sortInfo:{field: 'to_branch', direction: "ASC"},
	groupField: 'amount_range',
        autoLoad: true
    });

    var to_store_keys = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','from_branch','to_branch','key','amount_range']
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'list_data', 'to')); ?>'
	}),	
        sortInfo:{field: 'to_branch', direction: "ASC"},
	groupField: 'amount_range',
        autoLoad: true
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
        var p2 = center_panel.add({
            title: '<?php __('Sending'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'key-tab2',
            xtype: 'grid',
            store: from_store_keys,
            columns: [
                {header: "<?php __('From Branch'); ?>", dataIndex: 'from_branch', sortable: true},
                {header: "<?php __('To Branch'); ?>", dataIndex: 'to_branch', sortable: true},
                {header: "<?php __('Key'); ?>", dataIndex: 'key', sortable: true},
                {header: "<?php __('Amount Range'); ?>", dataIndex: 'amount_range', sortable: true}
            ],
		
            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Keys" : "Key"]})'
            }),
            tbar:[
                {
                    text: 'Excel',
                    icon: 'img/download.gif',
                    handler: function(){
                        window.open('keys/printxl/from');
                    }
                }
            ]
        });
	var p = center_panel.add({
            title: '<?php __('Receiving'); ?>',
            closable: true,
            loadMask: true,
            stripeRows: true,
            id: 'key-tab',
            xtype: 'grid',
            store: to_store_keys,
            columns: [
                {header: "<?php __('From Branch'); ?>", dataIndex: 'from_branch', sortable: true},
                {header: "<?php __('To Branch'); ?>", dataIndex: 'to_branch', sortable: true},
                {header: "<?php __('Key'); ?>", dataIndex: 'key', sortable: true},
                {header: "<?php __('Amount Range'); ?>", dataIndex: 'amount_range', sortable: true}
            ],
		
            view: new Ext.grid.GroupingView({
                forceFit:true,
                groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Keys" : "Key"]})'
            }),
            tbar:[
                {
                    text: 'Excel',
                    icon: 'img/download.gif',
                    handler: function(){
                        window.open('keys/printxl/to');
                    }
                }
            ]
	
        });
        
    }
