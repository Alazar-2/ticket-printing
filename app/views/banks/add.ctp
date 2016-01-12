//<script>
		<?php
			$this->ExtForm->create('Bank');
			$this->ExtForm->defineFieldFunctions();
		?>
    var BankAddForm = new Ext.form.FormPanel({
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'right',
            url:'<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'add')); ?>',
            defaultType: 'textfield',

            items: [
				<?php 
					$options = array();
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('ats_code', $options);
				?>,
				<?php 
					$options = array();
					$this->ExtForm->input('BIC', $options);
				?>            ]
    });

    var BankAddWindow = new Ext.Window({
            title: '<?php __('Add Bank'); ?>',
            width: 400,
            minWidth: 400,
            autoHeight: true,
            layout: 'fit',
            modal: true,
            resizable: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'right',
            items: BankAddForm,
            tools: [{
                    id: 'refresh',
                    qtip: 'Reset',
                    handler: function () {
                            BankAddForm.getForm().reset();
                    },
                    scope: this
            }, {
                    id: 'help',
                    qtip: 'Help',
                    handler: function () {
                            Ext.Msg.show({
                                    title: 'Help',
                                    buttons: Ext.MessageBox.OK,
                                    msg: 'This form is used to insert a new Bank.',
                                    icon: Ext.MessageBox.INFO
                            });
                    }
            }, {
                    id: 'toggle',
                    qtip: 'Collapse / Expand',
                    handler: function () {
                            if(BankAddWindow.collapsed)
                                    BankAddWindow.expand(true);
                            else
                                    BankAddWindow.collapse(true);
                    }
            }],
            buttons: [  {
                    text: '<?php __('Save'); ?>',
                    handler: function(btn){
                            BankAddForm.getForm().submit({
                                    waitMsg: '<?php __('Submitting your data...'); ?>',
                                    waitTitle: '<?php __('Wait Please...'); ?>',
                                    success: function(f,a){
                                            Ext.Msg.show({
                                                    title: '<?php __('Success'); ?>',
                                                    buttons: Ext.MessageBox.OK,
                                                    msg: a.result.msg,
                    icon: Ext.MessageBox.INFO
                                            });
                                            BankAddForm.getForm().reset();
<?php if(isset($parent_id)){ ?>
                                            RefreshParentBankData();
<?php } else { ?>
                                            RefreshBankData();
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
                            BankAddForm.getForm().submit({
                                    waitMsg: '<?php __('Submitting your data...'); ?>',
                                    waitTitle: '<?php __('Wait Please...'); ?>',
                                    success: function(f,a){
                                            Ext.Msg.show({
                                                    title: '<?php __('Success'); ?>',
                                                    buttons: Ext.MessageBox.OK,
                                                    msg: a.result.msg,
                    icon: Ext.MessageBox.INFO
                                            });
                                            BankAddWindow.close();
<?php if(isset($parent_id)){ ?>
                                            RefreshParentBankData();
<?php } else { ?>
                                            RefreshBankData();
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
                            BankAddWindow.close();
                    }
            }]
    });
