//<script>
<?php
    $this->ExtForm->create('User');
    $this->ExtForm->defineFieldFunctions();
    $this->ExtForm->create('Person');
    $this->ExtForm->defineFieldFunctions();
?>
				
var UserEditForm = new Ext.form.FormPanel({
	baseCls: 'x-plain',
	labelWidth: 130,
	labelAlign: 'right',
	url:'<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'Edit')); ?>',
	defaultType: 'textfield',
	items:{
		xtype:'tabpanel',
		activeTab: 0,
		height: 325,
		id: 'edit_user_tabs',
		tabWidth: 185,
		defaults:{ bodyStyle:'padding:10px'}, 
		items:[{
			title:'Account Information',
			layout:'form',
			defaultType: 'textfield',
			items: [
                            <?php $this->ExtForm->create('User'); ?>
                            <?php $this->ExtForm->input('id', array('hidden' => $user['User']['id'])); ?>,
                            <?php $this->ExtForm->input('person_id', array('hidden' => $user['User']['person_id'])); ?>,
                            <?php
                                $options['value'] = $user['User']['username'];
                                $this->ExtForm->input('username', $options);
                            ?>,
                            <?php 
                                $options = array();
                                $options['value'] = $user['User']['email'];
                                $this->ExtForm->input('email', $options);
                            ?>,
                            <?php 
                                $options = array();
                                $options['value'] = $user['User']['is_active'];
                                $this->ExtForm->input('is_active', $options);
                            ?>,
                            <?php 
                                $options = array();
                                $options['value'] = $user['User']['security_question'];
                                $this->ExtForm->input('security_question', $options);
                            ?>,
                            <?php 
                                $options = array();
                                $options['value'] = $user['User']['security_answer'];
                                $this->ExtForm->input('security_answer', $options);
                            ?>,
                            <?php 
                                $options = array('anchor' => '65%');
                                $options['items'] = $branches;
                                $options['value'] = $user['User']['branch_id'];
                                $this->ExtForm->input('branch_id', $options);
                            ?>,
                            new Ext.form.CheckboxGroup({
                                id:'myGroup',
                                xtype: 'checkboxgroup',
                                fieldLabel: 'Select Group',
                                itemCls: 'x-check-group-alt',
                                columns: 3,
                                items: [
<?php
                                $st = false;
                                foreach($groups as $key => $value){
                                    if($st) echo ",";
                                    $found = false;
                                    foreach($user['Group'] as $g){
                                        if($g['id'] == $key){
                                            $found = true;
                                            break;
                                        }
                                    }
?>
                                    { boxLabel: '<?php echo Inflector::humanize($value); ?>', name: '<?php echo "data[Group][" . $key . "]"; ?>', checked: <?php echo $found? 'true': 'false'; ?>}
<?php
                                    $st = true;
                                }
?>
                                ]
                            })	
			]
		},{
			title:'Personal Information',
			id: 'personal-info',
			layout:'form',
			defaultType: 'textfield',
			
			items: [
				<?php $this->ExtForm->create('Person'); ?>
				<?php $this->ExtForm->input('id', array('hidden' => $user['Person']['id'])); ?>,
				<?php
					$options = array('anchor' => '90%');
					$options['value'] = $user['Person']['first_name'];
					$this->ExtForm->input('first_name', $options);
				?>,
				<?php 
					$options = array('anchor' => '90%');
					$options['value'] = $user['Person']['middle_name'];
					$this->ExtForm->input('middle_name', $options);
				?>,
				<?php 
					$options = array('anchor' => '90%');
					$options['value'] = $user['Person']['last_name'];
					$this->ExtForm->input('last_name', $options);
				?>,
				<?php 
					$options = array('anchor' => '50%');
					$options['value'] = $user['Person']['birthdate'];
					$this->ExtForm->input('birthdate', $options);
				?>,
				<?php 
					$options = array('anchor' => '80%');
					$options['items'] = $birth_locations;
					$options['value'] = $user['Person']['birth_location_id'];
					$this->ExtForm->input('birth_location_id', $options);
				?>,
				<?php 
					$options = array('anchor' => '80%');
					$options['items'] = $residence_locations;
					$options['value'] = $user['Person']['residence_location_id'];
					$this->ExtForm->input('residence_location_id', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $user['Person']['kebele_or_farmers_association'];
					$this->ExtForm->input('kebele_or_farmers_association', $options);
				?>,
				<?php 
					$options = array('anchor' => '50%');
					$options['value'] = $user['Person']['house_number'];
					$this->ExtForm->input('house_number', $options);
				?>	
			]
		}],
		listeners: {
			tabchange: function(panel, tab) {
				if(tab.id == 'personal-info'){
					UserEditWindow.buttons[0].enable();
				}
			}
		}
	}
});

var UserEditWindow = new Ext.Window({
	title: '<?php __('Edit User'); ?>',
	width: 600,
	height:400,
	layout: 'fit',
	modal: true,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'right',
	items: UserEditForm,

	buttons: [{
		text: '<?php __('Save'); ?>',
		disabled: true,
		handler: function(btn){
			UserEditForm.getForm().submit({
				waitMsg: '<?php __('Submitting your data...'); ?>',
				waitTitle: '<?php __('Wait Please...'); ?>',
				success: function(f,a){
					Ext.Msg.show({
						title: '<?php __('Success'); ?>',
						buttons: Ext.MessageBox.OK,
						msg: a.result.msg,
						icon: Ext.MessageBox.INFO
					});
					UserEditWindow.close();
<?php if(isset($parent_id)){ ?>
					RefreshParentUserData();
<?php } else { ?>
					RefreshUserData();
<?php } ?>
				},
				failure: function(f,a){
					switch (a.failureType) {
						case Ext.form.Action.CLIENT_INVALID:
							Ext.Msg.show({
								title: '<?php __('Failure'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: 'Form fields may not be submitted with invalid values' ,
								icon: Ext.MessageBox.ERROR
							});
							break;
						case Ext.form.Action.CONNECT_FAILURE:
							Ext.Msg.show({
								title: '<?php __('Failure'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: 'Ajax communication failed' ,
								icon: Ext.MessageBox.ERROR
							});
							break;
						case Ext.form.Action.SERVER_INVALID:
							Ext.Msg.show({
								title: '<?php __('Failure'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: action.result.msg ,
								icon: Ext.MessageBox.ERROR
							});
					}
				}
			});
		}
	},{
		text: '<?php __('Reset'); ?>',
		handler: function(btn){
			UserEditForm.getForm().reset();
		}
	},{
		text: '<?php __('Cancel'); ?>',
		handler: function(btn){
			UserEditWindow.close();
		}
	}]
});
