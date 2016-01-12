		<?php
			$this->ExtForm->create('Field');
			$this->ExtForm->defineFieldFunctions();
		?>
		var FieldAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'add')); ?>',
			defaultType: 'textfield',

			items: [
				<?php 
					$options = array();
                                        $options = array('fieldLabel' => 'Identifier Name (no-space)'); 
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('label', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('length', $options);
				?>,
				<?php 
					$options = array();
                                        $options = array('xtype' => 'combo', 'fieldLabel' => 'Type', 'value' => 'text');
                                        $options['items'] = array('text' => 'Text Field', 'textarea' => 'Text Area','date'=>'Date','number'=>'Number','combo'=>'Combo','editable_combo'=>'Editable Combo','hidden'=>'Hidden');
					$this->ExtForm->input('type', $options);
				?>,
				<?php 
					$options = array();
                                        $options = array('fieldLabel' => 'Combo Data (Separate By Comma)'); 
					$this->ExtForm->input('data', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$this->ExtForm->input('ticket_id', $options);
				?>			]
		});
		
		var FieldAddWindow = new Ext.Window({
			title: '<?php __('Add Field'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: FieldAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					FieldAddForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Field.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(FieldAddWindow.collapsed)
						FieldAddWindow.expand(true);
					else
						FieldAddWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					FieldAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							FieldAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
							RefreshParentFieldData();
<?php } else { ?>
							RefreshFieldData();
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
					FieldAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							FieldAddWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentFieldData();
<?php } else { ?>
							RefreshFieldData();
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
					FieldAddWindow.close();
				}
			}]
		});
