		<?php
			$this->ExtForm->create('Field');
			$this->ExtForm->defineFieldFunctions();
		?>
		var FieldEditForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'edit')); ?>',
			defaultType: 'textfield',

			items: [
				<?php $this->ExtForm->input('id', array('hidden' => $field['Field']['id'])); ?>,
				<?php 
					$options = array();
                                        $options = array('fieldLabel' => 'Identifier Name (no-space)'); 
					$options['value'] = $field['Field']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $field['Field']['label'];
					$this->ExtForm->input('label', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $field['Field']['length'];
					$this->ExtForm->input('length', $options);
				?>,
				<?php 
					$options = array();
                                        $options = array('xtype' => 'combo', 'fieldLabel' => 'Type');
                                        $options['items'] = array('text' => 'Text Field', 'textarea' => 'Text Area','date'=>'Date','number'=>'Number','combo'=>'Combo','editable_combo'=>'Editable Combo','hidden'=>'Hidden');
					$options['value'] = $field['Field']['type'];
					$this->ExtForm->input('type', $options);
				?>,
				<?php 
					$options = array();
                                        $options = array('fieldLabel' => 'Combo Data (Separate By Comma)'); 
					$options['value'] = $field['Field']['data'];
					$this->ExtForm->input('data', $options);
				?>,
				<?php 
					$options = array();
					if(isset($parent_id))
						$options['hidden'] = $parent_id;
					else
						$options['items'] = $tickets;
					$options['value'] = $field['Field']['ticket_id'];
					$this->ExtForm->input('ticket_id', $options);
				?>			]
		});
		
		var FieldEditWindow = new Ext.Window({
			title: '<?php __('Edit Field'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: FieldEditForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					FieldEditForm.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to modify an existing Field.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(FieldEditWindow.collapsed)
						FieldEditWindow.expand(true);
					else
						FieldEditWindow.collapse(true);
				}
			}],
			buttons: [ {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					FieldEditForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							FieldEditWindow.close();
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
					FieldEditWindow.close();
				}
			}]
		});
