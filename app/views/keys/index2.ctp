var store_parent_keys = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','from_branch','to_branch','key','tt_direction','amount_range']
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentKey() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_key_data = response.responseText;
			
			eval(parent_key_data);
			
			KeyAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the key add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentKey(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_key_data = response.responseText;
			
			eval(parent_key_data);
			
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


function DeleteParentKey(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'keys', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Key(s) successfully deleted!'); ?>');
			RefreshParentKeyData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the key to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentKeyName(value){
	var conditions = '\'Key.name LIKE\' => \'%' + value + '%\'';
	store_parent_keys.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentKeyData() {
	store_parent_keys.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Keys'); ?>',
	store: store_parent_keys,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'keyGrid',
	columns: [
		{header:"<?php __('from_branch'); ?>", dataIndex: 'from_branch', sortable: true},
		{header:"<?php __('to_branch'); ?>", dataIndex: 'to_branch', sortable: true},
		{header: "<?php __('Key'); ?>", dataIndex: 'key', sortable: true},
		{header: "<?php __('Tt Direction'); ?>", dataIndex: 'tt_direction', sortable: true},
		{header: "<?php __('Amount Range'); ?>", dataIndex: 'amount_range', sortable: true}
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewKey(Ext.getCmp('keyGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Key</b><br />Click here to create a new Key'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentKey();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-key',
				tooltip:'<?php __('<b>Edit Key</b><br />Click here to modify the selected Key'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentKey(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-key',
				tooltip:'<?php __('<b>Delete Key(s)</b><br />Click here to remove the selected Key(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Key'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentKey(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Key'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Key'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentKey(sel_ids);
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
				text: '<?php __('View Key'); ?>',
				id: 'view-key2',
				tooltip:'<?php __('<b>View Key</b><br />Click here to see details of the selected Key'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewKey(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_key_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentKeyName(Ext.getCmp('parent_key_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_key_go_button',
				handler: function(){
					SearchByParentKeyName(Ext.getCmp('parent_key_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_keys,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-key').enable();
	g.getTopToolbar().findById('delete-parent-key').enable();
        g.getTopToolbar().findById('view-key2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-key').disable();
                g.getTopToolbar().findById('view-key2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-key').disable();
		g.getTopToolbar().findById('delete-parent-key').enable();
                g.getTopToolbar().findById('view-key2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-key').enable();
		g.getTopToolbar().findById('delete-parent-key').enable();
                g.getTopToolbar().findById('view-key2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-key').disable();
		g.getTopToolbar().findById('delete-parent-key').disable();
                g.getTopToolbar().findById('view-key2').disable();
	}
});



var parentKeysViewWindow = new Ext.Window({
	title: 'Key Under the selected Item',
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
			parentKeysViewWindow.close();
		}
	}]
});

store_parent_keys.load({
    params: {
        start: 0,    
        limit: list_size
    }
});