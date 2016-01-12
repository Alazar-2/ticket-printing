//<script>
		<?php
			$this->ExtForm->create('Bank');
			$this->ExtForm->defineFieldFunctions();
		?>
    var BankEditForm = new Ext.form.FormPanel({
            baseCls: 'x-plain',
            labelWidth: 100,
            labelAlign: 'right',
            url:'<?php echo $this->Html->url(array('controller' => 'banks', 'action' => 'edit')); ?>',
            defaultType: 'textfield',

            items: [
				<?php $this->ExtForm->input('id', array('hidden' => $bank['Bank']['id'])); ?>,
				<?php 
					$options = array();
					$options['value'] = $bank['Bank']['name'];
					$this->ExtForm->input('name', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $bank['Bank']['ats_code'];
					$this->ExtForm->input('ats_code', $options);
				?>,
				<?php 
					$options = array();
					$options['value'] = $bank['Bank']['BIC'];
					$this->ExtForm->input('BIC', $options);
				?>            ]
    });

    var BankEditWindow = new Ext.Window({
            title: '<?php __('Edit Bank'); ?>',
            width: 400,
            minWidth: 400,
            autoHeight: true,
            layout: 'fit',
            modal: true,
            resizable: true,
            plain:true,
            bodyStyle:'padding:5px;',
            buttonAlign:'right',
            items: BankEditForm,
            tools: [{
                    id: 'refresh',
                    qtip: 'Reset',
                    handler: function () {
                            BankEditForm.getForm().reset();
                    },
                    scope: this
            }, {
                    id: 'help',
                    qtip: 'Help',
                    handler: function () {
                            Ext.Msg.show({
                                    title: 'Help',
                                    buttons: Ext.MessageBox.OK,
                                    msg: 'This form is used to modify an existing Bank.',
                                    icon: Ext.MessageBox.INFO
                            });
                    }
            }, {
                    id: 'toggle',
                    qtip: 'Collapse / Expand',
                    handler: function () {
                            if(BankEditWindow.collapsed)
                                    BankEditWindow.expand(true);
                            else
                                    BankEditWindow.collapse(true);
                    }
            }],
            buttons: [ {
                    text: '<?php __('Save'); ?>',
                    handler: function(btn){
                            BankEditForm.getForm().submit({
                                    waitMsg: '<?php __('Submitting your data...'); ?>',
                                    waitTitle: '<?php __('Wait Please...'); ?>',
                                    success: function(f,a){
                                            Ext.Msg.show({
                                                    title: '<?php __('Success'); ?>',
                                                    buttons: Ext.MessageBox.OK,
                                                    msg: a.result.msg,
                    icon: Ext.MessageBox.INFO
                                            });
                                            BankEditWindow.close();
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
                            BankEditWindow.close();
                    }
            }]
    });
