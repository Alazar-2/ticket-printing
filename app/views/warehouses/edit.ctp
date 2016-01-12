		<?php
			$this->ExtForm->create('Warehouse');
			$this->ExtForm->defineFieldFunctions();
		?>
		var WarehouseEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $warehouse['Warehouse']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['string'];
					$this->ExtForm->input('string', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['format'];
					$this->ExtForm->input('format', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['start'];
					$this->ExtForm->input('start', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['end'];
					$this->ExtForm->input('end', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['current'];
					$this->ExtForm->input('current', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $warehouse['Warehouse']['status'];
					$this->ExtForm->input('status', $options);
				?>			]
		});
		
		var WarehouseEditWindow = new Ext.Window({
			title: '<?php __('Edit Warehouse'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: WarehouseEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					WarehouseEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Warehouse.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(WarehouseEditWindow.collapsed)
						WarehouseEditWindow.expand(true);
					else
						WarehouseEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					WarehouseEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							WarehouseEditWindow.close();
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
					WarehouseEditWindow.close();
				}
			}]
		});
