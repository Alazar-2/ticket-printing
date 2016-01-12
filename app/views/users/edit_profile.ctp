<?php
    $this->ExtForm->create('User');
    $this->ExtForm->defineFieldFunctions();
    $this->ExtForm->create('Person');
    $this->ExtForm->defineFieldFunctions();
?>

function ConfirmationPasswords(val){
    msg = "";

    new_pwd = Ext.getCmp('data[User][new_password]').getValue();
    con_pwd = Ext.getCmp('data[User][confirm_password]').getValue();

    if(new_pwd != con_pwd)
        msg += 'New password and its confirmation must match.<br />';

    if(msg == '') {
        Ext.getCmp('data[User][new_password]').clearInvalid();
        Ext.getCmp('data[User][confirm_password]').clearInvalid();
        return true; 
    }
    else
        return msg; 
}

var UserEditProfileForm = new Ext.form.FormPanel({
    baseCls: 'x-plain',
    labelWidth: 130,
    labelAlign: 'right',
    url: "<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'edit_profile')); ?>",
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
                <?php $this->ExtForm->input('is_active', array('xtype' => 'hidden', 'value' => $user['User']['is_active']? 'on': 'off')); ?>,
                <?php
                    $options = array();
                    $options['value'] = $user['User']['username'];
                    $options['disabled'] = 'true';
                    $this->ExtForm->input('username', $options);
                ?>,
                <?php 
                    $options = array();
                    $options['value'] = $user['User']['email'];
                    $this->ExtForm->input('email', $options);
                ?>,
                <?php 
                    $options = array('inputType' => 'password', 'anchor' => '70%');
                    $this->ExtForm->input('old_password', $options);
                ?>,
                <?php 
                    $options = array('inputType' => 'password', 'anchor' => '70%');
                    $options['validator'] = 'ConfirmationPasswords';
                    $options['id'] = 'data[User][new_password]';
                    $this->ExtForm->input('new_password', $options);
                ?>,
                <?php 
                    $options = array('inputType' => 'password', 'anchor' => '70%');
                    $options['validator'] = 'ConfirmationPasswords';
                    $options['id'] = 'data[User][confirm_password]';
                    $this->ExtForm->input('confirm_password', $options);
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
                ?>		
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
                    $options['disabled'] = 'true';
                    $this->ExtForm->input('first_name', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '90%');
                    $options['value'] = $user['Person']['middle_name'];
                    $options['disabled'] = 'true';
                    $this->ExtForm->input('middle_name', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '90%');
                    $options['value'] = $user['Person']['last_name'];
                    $options['disabled'] = 'true';
                    $this->ExtForm->input('last_name', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '50%');
                    $options['value'] = $user['Person']['birthdate'];
                    $options['disabled'] = 'true';
                    $this->ExtForm->input('birthdate', $options);
                ?>,
                <?php 
                    $options = array('anchor' => '80%');
                    $options['items'] = $birth_locations;
                    $options['value'] = $user['Person']['birth_location_id'];
                    $options['disabled'] = 'true';
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
                    UserEditProfileWindow.buttons[0].enable();
                }
            }
        }
    }
});

var UserEditProfileWindow = new Ext.Window({
    title: "<?php __('Edit My Profile'); ?>",
    width: 600,
    height:400,
    layout: 'fit',
    modal: true,
    resizable: false,
    plain:true,
    bodyStyle:'padding:5px;',
    buttonAlign:'right',
    items: UserEditProfileForm,

    buttons: [{
        text: "<?php __('Save'); ?>",
        disabled: true,
        handler: function(btn){
            UserEditProfileForm.getForm().submit({
                waitMsg: "<?php __('Submitting your data...'); ?>",
                waitTitle: "<?php __('Wait Please...'); ?>",
                success: function(f,a){
                    Ext.Msg.alert("<?php __('Success'); ?>", "'<?php __('Your profile saved successfully!'); ?>");
                    UserEditProfileWindow.close();
                },
                failure: function(f,a){
                    Ext.Msg.alert("<?php __('Warning'); ?>", a.result.errormsg);
                }
            });
        }
    },{
        text: "<?php __('Reset'); ?>",
        handler: function(btn){
            UserEditProfileForm.getForm().reset();
        }
    },{
        text: "<?php __('Cancel'); ?>",
        handler: function(btn){
            UserEditProfileWindow.close();
        }
    }]
});
