//<script>
<?php
    $this->ExtForm->create('User');
    $this->ExtForm->defineFieldFunctions();
    $this->ExtForm->create('Person');
    $this->ExtForm->defineFieldFunctions();
?>

var UserAddForm = new Ext.form.FormPanel({
    baseCls: 'x-plain',
    labelWidth: 130,
    labelAlign: 'right',
    url:'<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'Add')); ?>',
    items: {
        xtype:'tabpanel',
        activeTab: 0,
        height: 325,
        id: 'add_user_tabs',
        tabWidth: 185,
        defaults:{ bodyStyle:'padding:10px'}, 
        items:[{
            title:'Account Information',
            layout:'form',
            defaultType: 'textfield',
            items: [
                <?php 
                    $this->ExtForm->create('User');
                    $options = array('anchor' => '70%');
                    $this->ExtForm->input('username', $options);
                ?>,
                <?php 
                    $options = array('inputType' => 'password', 'anchor' => '70%');
                    $this->ExtForm->input('password', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '90%');
                    $this->ExtForm->input('email', $options);
                ?>,
                <?php 
                    $options = array();
                    $this->ExtForm->input('is_active', $options);
                ?>,
                <?php 
                    $options = array();
                    $this->ExtForm->input('security_question', $options);
                ?>,
                <?php 
                    $options = array();
                    $this->ExtForm->input('security_answer', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '65%');
                    $options['items'] = $branches;
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
?>
                        {
                            boxLabel: '<?php echo Inflector::humanize($value); ?>', 
                            name: '<?php echo "data[Group][" . $key . "]"; ?>'
                        }
<?php
                        $st = true;
                    }
?>
                    ]
                })
            ]
        }, {
            title:'Personal Information',
            id: 'personal-info',
            layout:'form',
            defaultType: 'textfield',

            items: [
                <?php 
                    $this->ExtForm->create('Person');
                    $options = array('anchor' => '90%', 'fieldLabel' => 'Name');
                    $this->ExtForm->input('first_name', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '90%', 'fieldLabel' => 'Father Name');
                    $this->ExtForm->input('middle_name', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '90%', 'fieldLabel' => 'G/Father Name');
                    $this->ExtForm->input('last_name', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '50%');
                    $this->ExtForm->input('birthdate', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '80%');
                    $options['items'] = $birth_locations;
                    $this->ExtForm->input('birth_location_id', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '80%');
                    $options['items'] = $residence_locations;
                    $this->ExtForm->input('residence_location_id', $options);
                ?>,
                <?php 
                    $options = array();
                    $this->ExtForm->input('kebele_or_farmers_association', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '50%');
                    $this->ExtForm->input('house_number', $options);
                ?>	
            ]
        }],
        listeners: {
            tabchange: function(panel, tab) {
                if(tab.id == 'personal-info'){
                    UserAddWindow.buttons[0].enable();
                    UserAddWindow.buttons[1].enable();
                }
            }
        }
    }
});

var UserAddWindow = new Ext.Window({
    title: '<?php __('Add User'); ?>',
    width: 600,
    height:400,
    layout: 'fit',
    modal: true,
    resizable: false,
    plain:true,
    bodyStyle:'padding:5px;',
    buttonAlign:'right',
    items: UserAddForm,

    buttons: [{
        text: '<?php __('Save'); ?>',
        disabled: true,
        handler: function(btn){
            UserAddForm.getForm().submit({
                waitMsg: '<?php __('Submitting your data...'); ?>',
                waitTitle: '<?php __('Wait Please...'); ?>',
                success: function(f,a){
                    Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Successfully saved!'); ?>');
                    UserAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
                    RefreshParentUserData();
<?php } else { ?>
                    RefreshUserData();
<?php } ?>
                },
                failure: function(f,a){
                    Ext.Msg.alert('<?php __('Warning'); ?>', a.result.errormsg);
                }
            });
        }
    },{
        text: '<?php __('Save & Close'); ?>',
        disabled: true,
        handler: function(btn){
            UserAddForm.getForm().submit({
                waitMsg: '<?php __('Submitting your data...'); ?>',
                waitTitle: '<?php __('Wait Please...'); ?>',
                success: function(f,a){
                    Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Successfully saved!'); ?>');
                    UserAddWindow.close();
<?php if(isset($parent_id)){ ?>
                    RefreshParentUserData();
<?php } else { ?>
                    RefreshUserData();
<?php } ?>
                },
                failure: function(f,a){
                    Ext.Msg.alert('<?php __('Warning'); ?>', a.result.errormsg);
                }
            });
        }
    },{
        text: '<?php __('Reset'); ?>',
        handler: function(btn){
            UserAddForm.getForm().reset();
        }
    },{
        text: '<?php __('Cancel'); ?>',
        handler: function(btn){
            UserAddWindow.close();
        }
    }]
});
