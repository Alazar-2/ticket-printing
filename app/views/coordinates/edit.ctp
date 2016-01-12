		<?php
			$this->ExtForm->create('Coordinate');
			$this->ExtForm->defineFieldFunctions();
		?>
		var CoordinateEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $coordinate['Coordinate']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $coordinate['Coordinate']['x'];
					$this->ExtForm->input('x', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $coordinate['Coordinate']['y'];
					$this->ExtForm->input('y', $options);
				?>,
				<?php 
					$options = array();
                                          $options = array('fieldLabel' => 'x2');
					$options['value'] = $coordinate['Coordinate']['length'];
					$this->ExtForm->input('length', $options);
				?>,
				<?php 
					$options = array();
                                          $options = array('fieldLabel' => 'Line');
					$options['value'] = $coordinate['Coordinate']['order'];
					$this->ExtForm->input('order', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$options['value'] = $coordinate['Coordinate']['ticket_id'];
					$this->ExtForm->input('ticket_id', $options);
				?>,
				<?php 
					$options = array();
					
						$options['items'] = $fields;
					$options['value'] = $coordinate['Coordinate']['field_id'];
					$this->ExtForm->input('field_id', $options);
				?>,
				<?php 
					$options = array();
                                        $options = array('xtype' => 'combo', 'fieldLabel' => 'Alignment');
                                        $options['items'] = array('left' => 'Left', 'right' => 'Right');
					$options['value'] = $coordinate['Coordinate']['alignment'];
					$this->ExtForm->input('alignment', $options);
				?>			]
		});
		
		var CoordinateEditWindow = new Ext.Window({
			title: '<?php __('Edit Coordinate'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: CoordinateEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					CoordinateEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Coordinate.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(CoordinateEditWindow.collapsed)
						CoordinateEditWindow.expand(true);
					else
						CoordinateEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					CoordinateEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							CoordinateEditWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentCoordinateData();
<?php } else { ?>
							RefreshCoordinateData();
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
					CoordinateEditWindow.close();
				}
			}]
		});
