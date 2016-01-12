		<?php 
			$this->ExtForm->create('Ticket');
			$this->ExtForm->defineFieldFunctions();
		?>
		var TicketAddForm = new Ext.form.FormPanel({
			baseCls: 'x-plain',
			labelWidth: 100,
			labelAlign: 'right',
			url:'<?php echo $this->Html->url(array('controller' => 'tickets', 'action' => 'issue')); ?>',
			defaultType: 'textfield',

			items: [
				<?php   
                                        foreach($issue as $field){
                                            if($field['Field']['type']=='text'){
											$field['Record']['value'] = str_replace("'","",$field['Record']['value']);
                                                echo " \n { xtype: 'textfield',value:'".$field['Record']['value']."', fieldLabel: '".$field['Field']['label']."', \n name: 'data[Field][".$field['Field']['id']."]', \n id: 'data[Field][".$field['Field']['id']."]',maxLength: ".$field['Field']['length']." },";
                                            }
                                            elseif($field['Field']['type']=='textarea'){
											$field['Record']['value'] = addslashes($field['Record']['value']);
											$field['Record']['value'] = str_replace(array("\n","\r"),array("\\n","\\r"),$field['Record']['value']);
											$field['Record']['value'] = str_replace("'","",$field['Record']['value']);
                                                echo " \n { xtype: 'textarea',value:'".$field['Record']['value']."',\n fieldLabel: '".$field['Field']['label']."', \n name: 'data[Field][".$field['Field']['id']."]', \n id: 'data[Field][".$field['Field']['id']."]',maxLength: ".$field['Field']['length'].",anchor: '99%',	height: 100, },";
                                            }
                                              elseif($field['Field']['type']=='date'){
                                               echo " \n { xtype: 'datefield',value:'".$field['Record']['value']."',\n fieldLabel: '".$field['Field']['label']."', \n name: 'data[Field][".$field['Field']['id']."]', \n id: 'data[Field][".$field['Field']['id']."]'},";
                                                
                                            } elseif($field['Field']['type']=='number'){
                                               echo " \n { xtype: 'numberfield',value:'".$field['Record']['value']."',\n fieldLabel: '".$field['Field']['label']."', \n name: 'data[Field][".$field['Field']['id']."]', \n id: 'data[Field][".$field['Field']['id']."]',allowBlank: false,},";
                                                
                                            } elseif($field['Field']['type']=='combo'){
                                                  echo " \n {";
                                                 if($field['Field']['data']!=''){
                                                     $lists = explode(',', $field['Field']['data']);
                                                        echo "store: new Ext.data.ArrayStore({
                                                                            id: 0,
                                                                            fields: ['id', 'name' ],
                                                                            data: [";
                                                        foreach($lists as $list){
                                                            echo "['".$list."','".$list."'],";
                                                        }
                                                        echo "  ]}), \n ";
                                                    }
                                               echo " xtype: 'combo',value:'".$field['Record']['value']."',\n fieldLabel: '".$field['Field']['label']."',valueField: 'id',displayField: 'name', hiddenName:'data[Field][".$field['Field']['id']."]',typeAhead: true,emptyText: 'Select',editable: false,triggerAction: 'all',mode: 'local',blankText: 'Your input is invalid.' },";
                                            } elseif($field['Field']['type']=='editable_combo'){
                                                echo " \n {";
                                                 if($field['Field']['data']!=''){
                                                     $lists = explode(',', $field['Field']['data']);
                                                        echo "store: new Ext.data.ArrayStore({
                                                                            id: 0,
                                                                            fields: ['id', 'name' ],
                                                                            data: [";
                                                        foreach($lists as $list){
                                                            echo "['".$list."','".$list."'],";
                                                        }
                                                        echo "  ]}), \n ";
                                                    }
                                                    echo "xtype: 'combo',value:'".$field['Record']['value']."',hiddenName: 'data[Field][".$field['Field']['id']."]',forceSelection: true, emptyText: '',triggerAction: 'all',lazyRender: true, mode: 'local',valueField: 'id',displayField: 'name', blankText: 'Your input is invalid.', fieldLabel: '".$field['Field']['label']."',width:200,hideTrigger:true, },";
                                                
                                            }
                                            else{
                                                
                                            }
                                        }?>	]
		});
		
		var IssueView2Window = new Ext.Window({
			title: '<?php __('View Issue'); ?>',
			width: 400,
			minWidth: 400,
			autoHeight: true,
			layout: 'fit',
			modal: true,
			resizable: true,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'right',
			items: TicketAddForm,
                        buttons: [  {
				text: '<?php __('Print'); ?>',
				handler: function(btn){
                                var url='<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'printv')); ?>/<?php echo $issue[0]['Issue']['id'];?>';
                                 window.open(url,'_blank');
				}}],
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
			}]
		});
