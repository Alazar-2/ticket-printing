
var store_coordinates = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','x','y','length','order','ticket','field','alignment'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'x', direction: "ASC"},
	groupField: 'y'
});


function AddCoordinate() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var coordinate_data = response.responseText;
			
			eval(coordinate_data);
			
			CoordinateAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the coordinate add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditCoordinate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var coordinate_data = response.responseText;
			
			eval(coordinate_data);
			
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

function DeleteCoordinate(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Coordinate successfully deleted!'); ?>');
			RefreshCoordinateData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the coordinate add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchCoordinate(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'search')); ?>',
		success: function(response, opts){
			var coordinate_data = response.responseText;

			eval(coordinate_data);

			coordinateSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the coordinate search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByCoordinateName(value){
	var conditions = '\'Coordinate.name LIKE\' => \'%' + value + '%\'';
	store_coordinates.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshCoordinateData() {
	store_coordinates.reload();
}


if(center_panel.find('id', 'coordinate-tab') != "") {
	var p = center_panel.findById('coordinate-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Coordinates'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'coordinate-tab',
		xtype: 'grid',
		store: store_coordinates,
		columns: [
			{header: "<?php __('X'); ?>", dataIndex: 'x', sortable: true},
			{header: "<?php __('Y'); ?>", dataIndex: 'y', sortable: true},
			{header: "<?php __('Length'); ?>", dataIndex: 'length', sortable: true},
			{header: "<?php __('Order'); ?>", dataIndex: 'order', sortable: true},
			{header: "<?php __('Ticket'); ?>", dataIndex: 'ticket', sortable: true},
			{header: "<?php __('Field'); ?>", dataIndex: 'field', sortable: true},
			{header: "<?php __('Alignment'); ?>", dataIndex: 'alignment', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Coordinates" : "Coordinate"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewCoordinate(Ext.getCmp('coordinate-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Coordinates</b><br />Click here to create a new Coordinate'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddCoordinate();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-coordinate',
					tooltip:'<?php __('<b>Edit Coordinates</b><br />Click here to modify the selected Coordinate'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditCoordinate(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-coordinate',
					tooltip:'<?php __('<b>Delete Coordinates(s)</b><br />Click here to remove the selected Coordinate(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Coordinate'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteCoordinate(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Coordinate'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Coordinates'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteCoordinate(sel_ids);
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
					text: '<?php __('View Coordinate'); ?>',
					id: 'view-coordinate',
					tooltip:'<?php __('<b>View Coordinate</b><br />Click here to see details of the selected Coordinate'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewCoordinate(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('Ticket'); ?>: ', {
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
							store_coordinates.reload({
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
					id: 'coordinate_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByCoordinateName(Ext.getCmp('coordinate_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'coordinate_go_button',
					handler: function(){
						SearchByCoordinateName(Ext.getCmp('coordinate_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchCoordinate();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_coordinates,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-coordinate').enable();
		p.getTopToolbar().findById('delete-coordinate').enable();
		p.getTopToolbar().findById('view-coordinate').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-coordinate').disable();
			p.getTopToolbar().findById('view-coordinate').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-coordinate').disable();
			p.getTopToolbar().findById('view-coordinate').disable();
			p.getTopToolbar().findById('delete-coordinate').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-coordinate').enable();
			p.getTopToolbar().findById('view-coordinate').enable();
			p.getTopToolbar().findById('delete-coordinate').enable();
		}
		else{
			p.getTopToolbar().findById('edit-coordinate').disable();
			p.getTopToolbar().findById('view-coordinate').disable();
			p.getTopToolbar().findById('delete-coordinate').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_coordinates.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
