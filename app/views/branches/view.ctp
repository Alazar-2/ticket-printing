//<script>
    var store_branch_users = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','username','email','is_active','created','modified'        
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'list_data', $branch['Branch']['id'])); ?>'    })
    });
    var store_key_fromBranches = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','from_branch','to_branch','key','tt_direction','amount_range','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'list_data2', $branch['Branch']['id'], 'outgoing')); ?>'	}),
	groupField: 'amount_range'
    });
    var store_key_toBranches = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','from_branch','to_branch','key','tt_direction','amount_range','created','modified'		]
	}),
	proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'list_data2', $branch['Branch']['id'], 'incoming')); ?>'	}),
	groupField: 'amount_range'
    });
    <?php
    $branch_html = "<table cellspacing=3>" . "<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $branch['Branch']['name'] . "</b></td></tr>" .
            "<tr><th align=right>" . __('Branch Code', true) . ":</th><td><b>" . $branch['Branch']['list_order'] . "</b></td></tr>" .
            "<tr><th align=right>" . __('FC Code', true) . ":</th><td><b>" . $branch['Branch']['fc_code'] . "</b></td></tr>" .
            "<tr><th align=right>" . __('Bank', true) . ":</th><td><b>" . $branch['Bank']['name'] . "</b></td></tr>" .
            "<tr><th align=right>" . __('Date Created', true) . ":</th><td><b>" . $branch['Branch']['created'] . "</b></td></tr>" .
            "<tr><th align=right>" . __('Date Modified', true) . ":</th><td><b>" . $branch['Branch']['modified'] . "</b></td></tr>" .
            "</table>";
    ?>
        var branch_view_panel_1 = {
            html : '<?php echo $branch_html; ?>',
            frame : true,
            height: 80
        }
        var branch_view_panel_2 = new Ext.TabPanel({
            activeTab: 0,
            anchor: '100%',
            height:190,
            plain:true,
            defaults:{autoScroll: true},
            items:[{
                    xtype: 'grid',
                    loadMask: true,
                    stripeRows: true,
                    store: store_branch_users,
                    title: '<?php __('Users'); ?>',
                    enableColumnMove: false,
                    listeners: {
                        activate: function(){
                            if(store_branch_users.getCount() == '')
                                store_branch_users.reload();
                        }
                    },
                    columns: [
                        {header: "<?php __('Username'); ?>", dataIndex: 'username', sortable: true},
                        {header: "<?php __('Email'); ?>", dataIndex: 'email', sortable: true},
                        {header: "<?php __('Date Created'); ?>", dataIndex: 'created', sortable: true},
                        {header: "<?php __('Date Modified'); ?>", dataIndex: 'modified', sortable: true}
		
                    ],
                    viewConfig: {
                        forceFit: true
                    },
                    bbar: new Ext.PagingToolbar({
                        pageSize: view_list_size,
                        store: store_branch_users,
                        displayInfo: true,
                        displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                        beforePageText: '<?php __('Page'); ?>',
                        afterPageText: '<?php __('of'); ?> {0}',
                        emptyMsg: '<?php __('No data to display'); ?>'
                    })
                }   , 
                {
                    xtype: 'grid',
                    loadMask: true,
                    stripeRows: true,
                    store: store_key_fromBranches,
                    title: '<?php __('Outgoing'); ?>',
                    enableColumnMove: false,
                    listeners: {
                        activate: function(){
                            if(store_key_fromBranches.getCount() == '')
                                store_key_fromBranches.reload();
                        }
                    },
                    columns: [
                        {header: "<?php __('To Branch'); ?>", dataIndex: 'to_branch', sortable: true}
                        ,{header: "<?php __('key'); ?>", dataIndex: 'key', sortable: true}
                        ,{header: "<?php __('Amount Range'); ?>", dataIndex: 'amount_range', sortable: true}
                        ,{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}		
                        ,{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}                            
                    ],
                   view: new Ext.grid.GroupingView({
                        forceFit: true,
                        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Keys" : "Key"]})'
                    }),
                    bbar: new Ext.PagingToolbar({
                        pageSize: view_list_size,
                        store: store_key_fromBranches,
                        displayInfo: true,
                        displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                        beforePageText: '<?php __('Page'); ?>',
                        afterPageText: '<?php __('of'); ?> {0}',
                        emptyMsg: '<?php __('No data to display'); ?>'
                    })
                },
                {
                    xtype: 'grid',
                    loadMask: true,
                    stripeRows: true,
                    store: store_key_toBranches,
                    title: '<?php __('Incoming'); ?>',
                    enableColumnMove: false,
                    listeners: {
                        activate: function(){
                            if(store_key_toBranches.getCount() == '')
                                store_key_toBranches.reload();
                        }
                    },
                    columns: [
                        {header: "<?php __('From Branch'); ?>", dataIndex: 'from_branch', sortable: true}
                        ,{header: "<?php __('key'); ?>", dataIndex: 'key', sortable: true}
                        ,{header: "<?php __('Amount Range'); ?>", dataIndex: 'amount_range', sortable: true}
                        ,{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}		
                        ,{header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true} 
		
                    ],
                    view: new Ext.grid.GroupingView({
                        forceFit: true,
                        groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Keys" : "Key"]})'
                    }),
                    bbar: new Ext.PagingToolbar({
                        pageSize: view_list_size,
                        store: store_key_toBranches,
                        displayInfo: true,
                        displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                        beforePageText: '<?php __('Page'); ?>',
                        afterPageText: '<?php __('of'); ?> {0}',
                        emptyMsg: '<?php __('No data to display'); ?>'
                    })
                }                  
            ]
        });

        var BranchViewWindow = new Ext.Window({
            title: '<?php __('View Branch'); ?>: <?php echo $branch['Branch']['name']; ?>',
            width: 500,
            height:345,
            minWidth: 500,
            minHeight: 345,
            resizable: false,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'center',
            modal: true,
            items: [ 
                branch_view_panel_1,
                branch_view_panel_2
            ],

            buttons: [{
                    text: '<?php __('Close'); ?>',
                    handler: function(btn){
                        BranchViewWindow.close();
                    }
                }]
        });
