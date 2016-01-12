		<?php   
			$this->ExtForm->create('Ticket');
			$this->ExtForm->defineFieldFunctions();
		?>
		var TicketAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'issue')); ?>',
			defaultType: 'textfield',

			items: [<?php $this->ExtForm->input('id', array('hidden' => $ticket['Ticket']['id'])); ?>,
				<?php   $fields=$ticket['Field'];
                                        foreach($fields as $field){
                                            if($field['type']=='text'){
                                                echo " \n { xtype: 'textfield',\n fieldLabel: '".$field['label']."', \n name: 'data[Field][".$field['id']."]', \n id: 'data[Field][".$field['id']."]',maxLength: ".$field['length']." },";
                                            }
                                            elseif($field['type']=='textarea'){
                                                echo " \n { xtype: 'textarea',\n fieldLabel: '".$field['label']."', \n name: 'data[Field][".$field['id']."]', \n id: 'data[Field][".$field['id']."]',maxLength: ".$field['length'].",width: '450px',	height: 100, },";
                                            }
                                              elseif($field['type']=='date'){
                                               echo " \n { xtype: 'datefield',\n fieldLabel: '".$field['label']."', \n name: 'data[Field][".$field['id']."]', \n id: 'data[Field][".$field['id']."]'},";
                                                
                                            } elseif($field['type']=='number'){
                                               echo " \n { xtype: 'numberfield',\n fieldLabel: '".$field['label']."', \n name: 'data[Field][".$field['id']."]', \n id: 'data[Field][".$field['id']."]',allowBlank: false,},";
                                                
                                            } elseif($field['type']=='combo'){
                                                  echo " \n {";
                                                 if($field['data']!=''){
                                                     $lists = explode(',', $field['data']);
                                                        echo "store: new Ext.data.ArrayStore({
                                                                            id: 0,
                                                                            fields: ['id', 'name' ],
                                                                            data: [";
                                                        foreach($lists as $list){
                                                            echo "['".$list."','".$list."'],";
                                                        }
                                                        echo "  ]}), \n ";
                                                    }
                                               echo " xtype: 'combo',\n fieldLabel: '".$field['label']."',valueField: 'id',displayField: 'name', hiddenName:'data[Field][".$field['id']."]',typeAhead: true,emptyText: 'Select',editable: false,triggerAction: 'all',mode: 'local',blankText: 'Your input is invalid.' },";
                                            } elseif($field['type']=='editable_combo'){
                                                echo " \n {";
                                                 if($field['data']!=''){
                                                     $lists = explode(',', $field['data']);
                                                        echo "store: new Ext.data.ArrayStore({
                                                                            id: 0,
                                                                            fields: ['id', 'name' ],
                                                                            data: [";
                                                        foreach($lists as $list){
                                                            echo "['".$list."','".$list."'],";
                                                        }
                                                        echo "  ]}), \n ";
                                                    }
                                                    echo "xtype: 'combo',hiddenName: 'data[Field][".$field['id']."]',forceSelection: true, emptyText: '',triggerAction: 'all',lazyRender: true, mode: 'local',valueField: 'id',displayField: 'name', blankText: 'Your input is invalid.', fieldLabel: '".$field['label']."',width:200,hideTrigger:true, },";
                                                
                                            }
                                            else{
                                                 echo " \n { xtype: 'hidden',\n fieldLabel: '".$field['label']."', \n name: 'data[Field][".$field['id']."]', \n id: 'data[Field][".$field['id']."]',value:'".$field['name']."' },";
                                            }
                                        }?>	]
		});
		
		var TicketIssueWindow = new Ext.Window({
			title: '<?php __('Print Ticket'); ?>',
			minWidth: 400,
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: TicketAddForm,
			tools: [{
				id: 'refresh',
				qtip: 'Reset',
				handler: function () {
					TicketIssueWindow.getForm().reset();
				},
				scope: this
			}, {
				id: 'help',
				qtip: 'Help',
				handler: function () {
					Ext.Msg.show({
						title: 'Help',
						buttons: Ext.MessageBox.OK,
						msg: 'This form is used to insert a new Ticket.',
						icon: Ext.MessageBox.INFO
					});
				}
			}, {
				id: 'toggle',
				qtip: 'Collapse / Expand',
				handler: function () {
					if(TicketIssueWindow.collapsed)
						TicketIssueWindow.expand(true);
					else
						TicketIssueWindow.collapse(true);
				}
			}],
			buttons: [  {
				text: '<?php __('Save'); ?>',
				handler: function(btn){
					TicketAddForm.getForm().submit({
						waitMsg: '<?php __('Submitting your data...'); ?>',
						waitTitle: '<?php __('Wait Please...'); ?>',
						success: function(f,a){
							Ext.Msg.show({
								title: '<?php __('Success'); ?>',
								buttons: Ext.MessageBox.OK,
								msg: a.result.msg,
                                icon: Ext.MessageBox.INFO
							});
							TicketIssueWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentIssueData();
<?php } else { ?>
							RefreshIssueData();
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
				text: '<?php __('Save & Print'); ?>',
				handler: function(btn){
                                var form = TicketAddForm.getForm();
					var el = form.getEl().dom;
     var target = document.createAttribute("target");
     target.nodeValue = "_blank";
     el.setAttributeNode(target);
     el.action = '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'printx')); ?>';
     el.submit();
     					TicketIssueWindow.close();
<?php if(isset($parent_id)){ ?>
							RefreshParentIssueData();
<?php } else { ?>
							RefreshIssueData();
<?php } ?>
				}
			},{
				text: '<?php __('Cancel'); ?>',
				handler: function(btn){
					TicketIssueWindow.close();
				}
			}]
		});
