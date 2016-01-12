var store_parent_coordinates = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','x','y','length','order','ticket','field','alignment'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentCoordinate() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_coordinate_data = response.responseText;
			
			eval(parent_coordinate_data);
			
			CoordinateAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the coordinate add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentCoordinate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_coordinate_data = response.responseText;
			
			eval(parent_coordinate_data);
			
			CoordinateEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the coordinate edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewCoordinate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var coordinate_data = response.responseText;

			eval(coordinate_data);

			CoordinateViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the coordinate view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentCoordinate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Coordinate(s) successfully deleted!'); ?>');
			RefreshParentCoordinateData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the coordinate to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentCoordinateName(value){
	var conditions = '\'Coordinate.name LIKE\' => \'%' + value + '%\'';
	store_parent_coordinates.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentCoordinateData() {
	store_parent_coordinates.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Coordinates'); ?>',
	store: store_parent_coordinates,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'coordinateGrid',
	columns: [
		{header: "<?php __('X'); ?>", dataIndex: 'x', sortable: true},
		{header: "<?php __('Y'); ?>", dataIndex: 'y', sortable: true},
		{header: "<?php __('X2'); ?>", dataIndex: 'length', sortable: true},
		{header: "<?php __('Line'); ?>", dataIndex: 'order', sortable: true},
		{header:"<?php __('ticket'); ?>", dataIndex: 'ticket', sortable: true},
		{header:"<?php __('field'); ?>", dataIndex: 'field', sortable: true},
		{header: "<?php __('Alignment'); ?>", dataIndex: 'alignment', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewCoordinate(Ext.getCmp('coordinateGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Coordinate</b><br />Click here to create a new Coordinate'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentCoordinate();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-coordinate',
				tooltip:'<?php __('<b>Edit Coordinate</b><br />Click here to modify the selected Coordinate'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentCoordinate(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-coordinate',
				tooltip:'<?php __('<b>Delete Coordinate(s)</b><br />Click here to remove the selected Coordinate(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Coordinate'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentCoordinate(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Coordinate'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Coordinate'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentCoordinate(sel_ids);
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
				text: '<?php __('View Coordinate'); ?>',
				id: 'view-coordinate2',
				tooltip:'<?php __('<b>View Coordinate</b><br />Click here to see details of the selected Coordinate'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewCoordinate(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_coordinate_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentCoordinateName(Ext.getCmp('parent_coordinate_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_coordinate_go_button',
				handler: function(){
					SearchByParentCoordinateName(Ext.getCmp('parent_coordinate_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_coordinates,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-coordinate').enable();
	g.getTopToolbar().findById('delete-parent-coordinate').enable();
        g.getTopToolbar().findById('view-coordinate2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-coordinate').disable();
                g.getTopToolbar().findById('view-coordinate2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-coordinate').disable();
		g.getTopToolbar().findById('delete-parent-coordinate').enable();
                g.getTopToolbar().findById('view-coordinate2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-coordinate').enable();
		g.getTopToolbar().findById('delete-parent-coordinate').enable();
                g.getTopToolbar().findById('view-coordinate2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-coordinate').disable();
		g.getTopToolbar().findById('delete-parent-coordinate').disable();
                g.getTopToolbar().findById('view-coordinate2').disable();
	}
});



var parentCoordinatesViewWindow = new Ext.Window({
	title: 'Coordinate Under the selected Item',
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
			parentCoordinatesViewWindow.close();
		}
	}]
});

store_parent_coordinates.load({
    params: {
        start: 0,    
        limit: list_size
    }
});