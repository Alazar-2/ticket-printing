//<script>
    var store_bank_branches = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            root:'rows',
            totalProperty: 'results',
            fields: [
                'id','name','ats_code','fc_code','bank','created','modified'        
            ]
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?php echo $this->Html->url(array('controller' => 'branches', 'action' => 'list_data', $bank['Bank']['id'])); ?>'    
        }),
        remoteSort: true
    });

<?php $bank_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $bank['Bank']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Ats Code', true) . ":</th><td><b>" . $bank['Bank']['ats_code'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('BIC', true) . ":</th><td><b>" . $bank['Bank']['BIC'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $bank['Bank']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $bank['Bank']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
        var bank_view_panel_1 = {
            html : '<?php echo $bank_html; ?>',
            frame : true,
            height: 80
        }
        var bank_view_panel_2 = new Ext.TabPanel({
            activeTab: 0,
            anchor: '100%',
            height:190,
            plain:true,
            defaults:{autoScroll: true},
            items:[
            {
                    xtype: 'grid',
                    loadMask: true,
                    stripeRows: true,
                    store: store_bank_branches,
                    title: '<?php __('Branches'); ?>',
                    enableColumnMove: false,
                    listeners: {
                        activate: function(){
                            if(store_bank_branches.getCount() == '')
                                store_bank_branches.reload();
                        }
                    },
                    columns: [
                        {header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,                        {header: "<?php __('Ats Code'); ?>", dataIndex: 'ats_code', sortable: true}
,                        {header: "<?php __('Fc Code'); ?>", dataIndex: 'fc_code', sortable: true}
,                        {header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
,                        {header: "<?php __('Modified'); ?>", dataIndex: 'modified', sortable: true}
		
                    ],
                    viewConfig: {
                        forceFit: true
                    },
                    bbar: new Ext.PagingToolbar({
                        pageSize: view_list_size,
                        store: store_bank_branches,
                        displayInfo: true,
                        displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
                        beforePageText: '<?php __('Page'); ?>',
                        afterPageText: '<?php __('of'); ?> {0}',
                        emptyMsg: '<?php __('No data to display'); ?>'
                    })
            }                ]
        });

        var BankViewWindow = new Ext.Window({
            title: '<?php __('View Bank'); ?>: <?php echo $bank['Bank']['name']; ?>',
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
                bank_view_panel_1,
                bank_view_panel_2
            ],

            buttons: [{
                text: '<?php __('Close'); ?>',
                handler: function(btn){
                    BankViewWindow.close();
                }
            }]
        });
