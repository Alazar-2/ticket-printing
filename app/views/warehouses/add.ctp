		<?php
			$this->ExtForm->create('Warehouse');
			$this->ExtForm->defineFieldFunctions();
		?>
		var WarehouseAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('string', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('format', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('start', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('end', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('current', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('status', $options);
				?>			]
		});
		
		var WarehouseAddWindow = new Ext.Window({
			title: '<?php __('Add Warehouse'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: WarehouseAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					WarehouseAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Warehouse.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(WarehouseAddWindow.collapsed)
						WarehouseAddWindow.expand(true);
					else
						WarehouseAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					WarehouseAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							WarehouseAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentWarehouseData();
<?php } else { ?>
							RefreshWarehouseData();
<?php } ?>
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			}, {
				text: '<?php __('Save & Close'); ?>',
				handler: function(btn){
					WarehouseAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							WarehouseAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentWarehouseData();
<?php } else { ?>
							RefreshWarehouseData();
<?php } ?>
						},
						failure: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Warning'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.errormsg,
                                icon: Ext.MessageBox.ERROR
							});
						}
					});
				}
			},{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					WarehouseAddWindow.close();
				}
			}]
		});
