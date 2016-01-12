<?php

class BatchesController extends AppController {

    var $name = 'Batches';

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('import_batch_ftp', 'format_outgoing_chr_file', 'import_incoming_batch', 'save_status'));
    }

    function index_make_outgoing() {
        
    }

    function index_check_outgoing() {
        
    }

    function index_incoming() {
        
    }

    function format_outgoing_chr_file($xml_file_name) {
        $this->layout = 'message_layout';

        App::import('BehXmlParser');

        BehXmlParser::setStartingValues();

        $xml_parser = xml_parser_create();
        xml_set_element_handler($xml_parser, "BehXmlParser::startElement", "BehXmlParser::endElement");
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_character_data_handler($xml_parser, "BehXmlParser::characterData");
        if (!($fp = fopen($xml_file_name . '.cake', "r"))) {
            $this->set('msg', 'File ' . $xml_file_name . '.cake' . ' could not open for XML input.');
            $this->log('File "' . $xml_file_name . '.cake' . '" could not open for XML input. [function: batches::format_outgoing_chr_file()]');
            return;
        }

        while ($data = fread($fp, 4096)) {
            $data = str_replace('&amp;', 'AND', $data);
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                $this->set('msg', sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
                $this->log(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)) . ' [function: batches::import_incoming_batch()]');
                return;
            }
        }
        xml_parser_free($xml_parser);

        $obj->xml = BehXmlParser::getXmlObject();
        $header = $obj->xml->Document->FIToFIPmtStsRpt->GrpHdr;
        $orgnl_info = $obj->xml->Document->FIToFIPmtStsRpt->OrgnlGrpInfAndSts;
        $tx_info = $obj->xml->Document->FIToFIPmtStsRpt->TxInfAndSts;

        $handle = fopen(FILES_DIR . 'cheque_templates' . DS . 'outgoing_chr_template.xml', "r");
        $outgoing_chr_data = fread($handle, filesize(FILES_DIR . 'cheque_templates' . DS . 'outgoing_chr_template.xml'));
        fclose($handle);
		
		$x = explode("-", $orgnl_info->OrgnlMsgId->data);
		$org_msg_id = $x[0];

        $result = str_replace('{GrpHdr.MsgId}', $header->MsgId->data, $outgoing_chr_data);
        $result = str_replace('{GrpHdr.CreDtTm}', date('Y-m-d\TH:i:s') . '+03:00', $result);
        $result = str_replace('{GrpHdr.InstgAgt.FinInstnId.BIC}', $header->InstgAgt->FinInstnId->BIC->data, $result);

        $result = str_replace('{OrgnlGrpInfAndSts.OrgnlMsgId}', $org_msg_id, $result);
        $result = str_replace('{OrgnlGrpInfAndSts.OrgnlMsgNmId}', $orgnl_info->OrgnlMsgNmId->data, $result);

        $result = str_replace('{TxInfAndSts.StsId}', $tx_info->StsId->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlEndToEndId}', $tx_info->OrgnlEndToEndId->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxId}', $tx_info->OrgnlTxId->data, $result);
        $result = str_replace('{TxInfAndSts.StsRsnInf.Orgtr.Id.OrgId.BICOrBEI}', $tx_info->StsRsnInf->Orgtr->Id->OrgId->BICOrBEI->data, $result);
        $result = str_replace('{TxInfAndSts.StsRsnInf.Rsn.Cd}', $tx_info->StsRsnInf->Rsn->Cd->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.IntrBkSttlmAmt}', number_format($tx_info->OrgnlTxRef->IntrBkSttlmAmt->data, 2, '.', ''), $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.IntrBkSttlmDt}', date('Y-m-d') . '+03:00', $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.SttlmInf.SttlmMtd}', $tx_info->OrgnlTxRef->SttlmInf->SttlmMtd->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.SttlmInf.ClrSys.Prtry}', $tx_info->OrgnlTxRef->SttlmInf->ClrSys->Prtry->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.PmtTpInf.SvcLvl.Cd}', $tx_info->OrgnlTxRef->PmtTpInf->SvcLvl->Cd->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.PmtTpInf.LclInstrm.Cd}', $tx_info->OrgnlTxRef->PmtTpInf->LclInstrm->Cd->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.Dbtr.Nm}', $tx_info->OrgnlTxRef->Dbtr->Nm->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.Dbtr.Id.OrgId.BICOrBEI}', $tx_info->OrgnlTxRef->Dbtr->Id->OrgId->BICOrBEI->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.DbtrAcct.Id.IBAN}', $tx_info->OrgnlTxRef->DbtrAcct->Id->IBAN->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.DbtrAgt.FinInstnId.BIC}', $tx_info->OrgnlTxRef->DbtrAgt->FinInstnId->BIC->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.CdtrAgt.FinInstnId.BIC}', $tx_info->OrgnlTxRef->CdtrAgt->FinInstnId->BIC->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.Cdtr.Nm}', $tx_info->OrgnlTxRef->Cdtr->Nm->data, $result);
        $result = str_replace('{TxInfAndSts.OrgnlTxRef.CdtrAcct.Id.IBAN}', $tx_info->OrgnlTxRef->CdtrAcct->Id->IBAN->data, $result);

        $handle_xml = fopen(substr($xml_file_name, 0, strlen($xml_file_name)- 5) . '.xml', 'a');
        if ($handle_xml !== FALSE) {
            fwrite($handle_xml, $result);
            fclose($handle_xml);
            
			@unlink($xml_file_name . '.cake');
			
            $this->set('msg', 'OK');
        } else {
            $this->set('msg', 'Cannot save the file ' . substr($xml_file_name, 0, strlen($xml_file_name)- 5));
        }
        return;
    }

    function list_data_for_make_outgoing() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;

        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        $conditions['Batch.maker_id'] = $this->Session->read('Auth.User.id');

        $conditions['Batch.mode'] = 'outgoing';

        if (!isset($conditions['Batch.created >=']))
            $conditions['Batch.created LIKE'] = date('Y-m-d') . '%';

        $this->set('batches', $this->Batch->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'Batch.created DESC')));
        $this->set('results', $this->Batch->find('count', array('conditions' => $conditions)));
    }

    function list_data_for_check_outgoing() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;

        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        $conditions['Batch.maker_id <>'] = $this->Session->read('Auth.User.id');
        $conditions['Maker.branch_id'] = $this->Session->read('Auth.User.branch_id');

        if (!isset($conditions['Batch.created >=']))
            $conditions['Batch.created LIKE'] = date('Y-m-d') . '%';

        $conditions['Batch.mode'] = 'outgoing';
        $conditions['Batch.posted'] = true;

        $this->set('batches', $this->Batch->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'Batch.created DESC')));
        $this->set('results', $this->Batch->find('count', array('conditions' => $conditions)));
    }

    function list_data_for_incoming() {
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $conditions['Batch.mode'] = 'incoming';
        if (!isset($conditions['Batch.created >=']))
            $conditions['Batch.created LIKE'] = date('Y-m-d') . '%';

        $user_batches = array();
        $this->loadModel('Branch');
        $this->loadModel('Cheque');

        $branch = $this->Branch->read(null, $this->Session->read('Auth.User.branch_id'));
        if ($branch) {
            $batches = $this->Batch->find('all', array('conditions' => $conditions /* , 'limit' => $limit, 'offset' => $start */, 'order' => 'Batch.created DESC'));
            foreach ($batches as $batch) {
                $user_batch = $this->Cheque->find('count', array('conditions' => array('Cheque.batch_id' => $batch['Batch']['id'], 'Cheque.branch_code' => $branch['Branch']['ats_code'])));
                if ($user_batch > 0 || $branch['Branch']['ats_code'] == '--')
                    $user_batches[] = $batch;
            }
        }
        $this->set('batches', $user_batches);
        $this->set('results', count($user_batches));
    }

    function list_data_for_exception_incoming() {
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        $conditions['Batch.mode'] = 'incoming';
        if (!isset($conditions['Batch.created >=']))
            $conditions['Batch.created LIKE'] = date('Y-m-d') . '%';

        $user_batches = array();
        $this->loadModel('Branch');
        $this->loadModel('Cheque');

        $branch = $this->Branch->read(null, $this->Session->read('Auth.User.branch_id'));
        if ($branch) {
            $this->Batch->recursive = 2;

            $batches = $this->Batch->find('all', array('conditions' => $conditions /* , 'limit' => $limit, 'offset' => $start */, 'order' => 'Batch.created DESC'));
            //pr($batches);
            foreach ($batches as $batch) {
                $user_batch = $this->Cheque->find('count', array('conditions' => array('Cheque.batch_id' => $batch['Batch']['id'], 'Cheque.branch_code' => $branch['Branch']['ats_code'])));
                if ($user_batch > 0 || $branch['Branch']['ats_code'] == '--') {
                    foreach ($batch['Cheque'] as $cheque) {
                        if (count($cheque['ChequeException']) > 0) {
                            $user_batches[] = $batch;
                            break;
                        }
                    }
                }
            }
        }
        $this->set('batches', $user_batches);
        $this->set('results', count($user_batches));
    }

    function list_files() {
        $handle_f = opendir(EATS_OUT_DIR);
        $files = array();
        /* This is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle_f))) {
            if ($file != '.' && $file != '..' && strpos($file, '.zip') > 0)
                $files[] = array(
                    'file_name' => $file,
                    'date_received' => date("F d Y H:i:s.", filemtime(EATS_OUT_DIR . DS . $file))
                );
        }
        closedir($handle_f);

        $this->set('incoming_files', $files);
    }

    function list_data_incoming() {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;

        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        //$conditions['Batch.maker_id'] = $this->Session->read('Auth.User.id');
        $conditions['Batch.mode'] = 'incoming';

        $this->set('batches', $this->Batch->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'Batch.created DESC')));
        $this->set('results', $this->Batch->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        $this->Batch->recursive = 2;
        $this->set('batch', $this->Batch->read(null, $id));
    }

    function repair($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            $this->data['Batch']['modifiable'] = true;
            $this->data['Batch']['posted'] = false;
            if ($this->Batch->save($this->data)) {
                $this->Session->setFlash(__('The Batch has been set to modification queue', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The Batch could not be set to modification queue. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('batch', $this->Batch->read(null, $id));
    }

    function reject($id = null) {
        if ($id) {
            $this->Cheque->read(null, $id);
            $this->Cheque->set(array(
                'rejected' => true
            ));
            $this->Cheque->save();
        }
    }

    /**
     * Function called when the checker user clicks on Approve Button 
     * from the top toolbar.
     * @param integer $id 
     */
    function approve($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        $this->Batch->recursive = 2;
        $this->set('batch', $this->Batch->read(null, $id));
    }

    function post_batch($id = null) {
        if ($id) {
            $this->Batch->read(null, $id);
            $this->Batch->set(array(
                'maker_id' => $this->Session->read('Auth.User.id'),
                'posted' => true,
                'modifiable' => false,
                'date_posted' => date('Y-m-d H:i:s')
            ));
            $this->Batch->save();
        }
    }

    function approve_incoming($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        $this->Batch->recursive = 2;
        $this->set('batch', $this->Batch->read(null, $id));
    }

    function approve_incoming_exception($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        $this->Batch->recursive = 2;
        $this->set('batch', $this->Batch->read(null, $id));
    }

    function import_batch_file() {
        $this->layout = 'add_form_layout';
        if (!empty($this->data)) {
            $number_of_cheques = 0;
            $this->loadModel('Cheque');

            $file = $this->data['Batch']['zip_file'];
            $ext = substr($file['name'], strripos($file['name'], '.') + 1);

            $file_name_only = substr($file['name'], 0, (strlen($file['name']) - (strlen($ext) + 1)));
            $file_name_only = str_replace('.', '_', $file_name_only);

            $file_name = $file_name_only . '.' . $ext;
            if (move_uploaded_file($file['tmp_name'], FILES_DIR . $file_name)) {

                // open the file
                $zip = zip_open(FILES_DIR . $file_name);
                $out_dir = FILES_DIR . 'outgoings' . DS . $file_name_only;

                if (!is_dir($out_dir))
                    mkdir($out_dir, 0777);

                if ($zip) {
                    while ($zip_entry = zip_read($zip)) {
                        $fp = fopen($out_dir . DS . zip_entry_name($zip_entry), "w");
                        if (zip_entry_open($zip, $zip_entry, "r")) {
                            $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                            fwrite($fp, "$buf");
                            zip_entry_close($zip_entry);
                            fclose($fp);
                        }
                    }
                    zip_close($zip);
                    unlink(FILES_DIR . $file_name);
                }

                $dh = opendir($out_dir . DS);
                if ($dh) {
                    // Create Batch Record
                    $batch_data = array('Batch' => array(
                            'name' => $file_name_only,
                            'maker_id' => $this->Session->read('Auth.User.id'),
                            'checker_id' => 0,
                            'authorized' => 0,
                            'mode' => 'outgoing',
                            'date_sent' => date('Y-m-d H:i:s'),
                            'date_posted' => date('Y-m-d H:i:s'),
                            'sent' => 0
                            ));
                    $this->Batch->create();
                    $this->Batch->save($batch_data);

                    // End of Create Batch Record

                    while (($file = readdir($dh)) !== false) {
                        if ($file == '.' || $file == '..')
                            continue;
                        $pos = strrpos($file, ".chequeItem");
                        if ($pos === false) {
                            /* if(strrpos($file, ".chequeFrontImage") === FALSE)
                              copy($out_dir . DS . $file, $out_dir . DS . substr($file, 0, 1) . '_back.gif');
                              else
                              copy($out_dir . DS . $file, $out_dir . DS . substr($file, 0, 1) . '_front.gif'); */
                            continue;
                        } else {
                            // read each line
                            $handle = fopen($out_dir . DS . $file, "r");
                            if ($handle) {
                                $data_line = array('Cheque' => array());
                                while (!feof($handle)) {
                                    $line = fgets($handle, 4096);
                                    if ($line == "")
                                        continue;

                                    $line = str_replace("\\ ", " ", $line);
                                    $line = str_replace("\r\n", "", $line);
                                    $line = str_replace("\n", "", $line);
                                    $line = str_replace("\r", "", $line);

                                    $comment_pos = strpos($line, '#');

                                    if ($comment_pos === FALSE) {
                                        // this is not a comment line
                                        $pieces = explode("=", $line);
                                        $pieces[1] = str_replace('&', 'AND', $pieces[1]);
                                        $pieces[1] = str_replace('$', 'DOLLAR', $pieces[1]);

                                        switch ($pieces[0]) {
                                            case "Transaction Code":
                                                $data_line['Cheque']['transaction_code'] = (Configure::read("TT" + substr($pieces[1], 0, 1)) == '') ? 'CH' : Configure::read("TT" + substr($pieces[1], 0, 1));
                                                break;
                                            case "Branch Code":
                                                $data_line['Cheque']['branch_code'] = $pieces[1];
                                                break;
                                            case "Creation Date":
                                                $data_line['Cheque']['creation_date'] = str_replace('\\:', ':', $pieces[1]);
                                                break;
                                            case "Beneficiary Account":
                                                $data_line['Cheque']['beneficiary_account'] = $pieces[1];
                                                break;
                                            case "Cheque Number":
                                                $data_line['Cheque']['cheque_number'] = $pieces[1];
                                                break;
                                            case "Cheque Index":
                                                $data_line['Cheque']['cheque_index'] = $pieces[1];
                                                break;
                                            case "Remittance Information":
                                                $data_line['Cheque']['remitance_information'] = $pieces[1];
                                                break;
                                            case "Bank Code":
                                                $data_line['Cheque']['bank_code'] = $pieces[1];
                                                $data_line['Cheque']['issuer_bank_bic'] = Configure::read($pieces[1]);
                                                break;
                                            case "Amount":
                                                $data_line['Cheque']['amount'] = (($pieces[1]) / 100);
                                                break;
                                            case "Payer Name":
                                                $data_line['Cheque']['payer_name'] = $pieces[1];
                                                break;
                                            case "Endorsement Number":
                                                $data_line['Cheque']['endorsement_number'] = $pieces[1];
                                                break;
                                            case "Currency Code":
                                                $data_line['Cheque']['currency'] = (Configure::read("CC" + substr($pieces[1], 0, 1)) == '') ? 'ETB' : Configure::read("CC" + substr($pieces[1], 0, 1));
                                                break;
                                            case "Beneficiary Name":
                                                $data_line['Cheque']['beneficiary_name'] = $pieces[1];
                                                break;
                                            case "Payer Account":
                                                $data_line['Cheque']['payer_account'] = $pieces[1];
                                                break;
                                            default:
                                                break;
                                        }
                                        //$this->log('Data Line::: ' . print_r($data_line, true));
                                    }
                                }
                                fclose($handle);
                                // insert the current line into the database.
                                $data_line['Cheque']['batch_id'] = $this->Batch->id;
                                $data_line['Cheque']['transaction_id'] = $data_line['Cheque']['cheque_number'];
                                $data_line['Cheque']['microcode'] = 'NO_MICROCODE';

                                $this->Cheque->create();
                                if (!$this->Cheque->save($data_line)) {
                                    $this->log('Cannot save ' . $data_line['Cheque']['cheque_number']);
                                    $errors = $this->Cheque->invalidFields();
                                    $this->log('Errors ' . print_r($errors, true));
                                }
                                $number_of_cheques++;
                            }
                        }
                    }
                    closedir($dh);
                }
            }
            $this->Session->setFlash('The batch with ' . $number_of_cheques . ' cheques has been imported', '');
            $this->render('/elements/success2');
        }
    }

    function import_batch_ftp($filename = '') {
        $this->layout = 'message_layout';

        if (!file_exists(FILES_DIR . $filename)) {
            $this->set('msg', 'File ' . $filename . ' does not exist.');
            $this->log('File "' . $filename . '" does not exist. [function: batches::import_batch_ftp()]');
            return;
        }
        $this->loadModel('Cheque');
        $ext = substr($filename, strripos($filename, '.') + 1);

        $file_name_only = substr($filename, 0, (strlen($filename) - (strlen($ext) + 1)));
        $file_name_only = str_replace('.', '_', $file_name_only);

        $file_name = $filename;

        // open the file
        $zip = zip_open(FILES_DIR . $file_name);
        $out_dir = FILES_DIR . 'outgoings' . DS . $file_name_only;

        if (!is_dir($out_dir))
            mkdir($out_dir, 0777);

        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
                $fp = fopen($out_dir . DS . zip_entry_name($zip_entry), "w");
                if (zip_entry_open($zip, $zip_entry, "r")) {
                    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    fwrite($fp, "$buf");
                    zip_entry_close($zip_entry);
                    fclose($fp);
                }
            }
            zip_close($zip);
            unlink(FILES_DIR . $file_name);
        }

        $dh = opendir($out_dir . DS);
        if ($dh) {
            // Create Batch Record
            $batch_data = array('Batch' => array(
                    'name' => $file_name_only,
                    'maker_id' => 0,
                    'checker_id' => 0,
                    'authorized' => 0,
                    'mode' => 'outgoing',
                    'date_sent' => date('Y-m-d H:i:s'),
                    'date_posted' => date('Y-m-d H:i:s'),
                    'sent' => 0
                    ));
            $this->Batch->create();
            $this->Batch->save($batch_data);

            // End of Create Batch Record

            while (($file = readdir($dh)) !== false) {
                if ($file == '.' || $file == '..')
                    continue;
                $pos = strrpos($file, ".chequeItem");
                if ($pos === false) {
                    continue;
                } else {
                    // read each line
                    $handle = fopen($out_dir . DS . $file, "r");
                    if ($handle) {
                        $data_line = array('Cheque' => array());
                        while (!feof($handle)) {
                            $line = fgets($handle, 4096);
                            if ($line == "")
                                continue;

                            $line = str_replace("\\ ", " ", $line);
                            $line = str_replace("\r\n", "", $line);
                            $line = str_replace("\n", "", $line);
                            $line = str_replace("\r", "", $line);

                            $comment_pos = strpos($line, '#');

                            if ($comment_pos === FALSE) {
                                // this is not a comment line
                                $pieces = explode("=", $line);
                                $pieces[1] = str_replace('&', 'AND', $pieces[1]);
                                $pieces[1] = str_replace('$', 'DOLLAR', $pieces[1]);

                                switch ($pieces[0]) {
                                    case "Transaction Code":
                                        $data_line['Cheque']['transaction_code'] = (Configure::read("TT" + substr($pieces[1], 0, 1)) == '') ? 'CH' : Configure::read("TT" + substr($pieces[1], 0, 1));
                                        break;
                                    case "Branch Code":
                                        $data_line['Cheque']['branch_code'] = $pieces[1];
                                        break;
                                    case "Creation Date":
                                        $data_line['Cheque']['creation_date'] = str_replace('\\:', ':', $pieces[1]);
                                        break;
                                    case "Beneficiary Account":
                                        $data_line['Cheque']['beneficiary_account'] = $pieces[1];
                                        break;
                                    case "Cheque Number":
                                        $data_line['Cheque']['cheque_number'] = $pieces[1];
                                        break;
                                    case "Cheque Index":
                                        $data_line['Cheque']['cheque_index'] = $pieces[1];
                                        break;
                                    case "Remittance Information":
                                        $data_line['Cheque']['remitance_information'] = $pieces[1];
                                        break;
                                    case "Bank Code":
                                        $data_line['Cheque']['bank_code'] = $pieces[1];
                                        $data_line['Cheque']['issuer_bank_bic'] = Configure::read($pieces[1]);
                                        break;
                                    case "Amount":
                                        $data_line['Cheque']['amount'] = (($pieces[1]) / 100);
                                        break;
                                    case "Payer Name":
                                        $data_line['Cheque']['payer_name'] = $pieces[1];
                                        break;
                                    case "Endorsement Number":
                                        $data_line['Cheque']['endorsement_number'] = $pieces[1];
                                        break;
                                    case "Currency Code":
                                        $data_line['Cheque']['currency'] = (Configure::read("CC" + substr($pieces[1], 0, 1)) == '') ? 'ETB' : Configure::read("CC" + substr($pieces[1], 0, 1));
                                        break;
                                    case "Beneficiary Name":
                                        $data_line['Cheque']['beneficiary_name'] = $pieces[1];
                                        break;
                                    case "Payer Account":
                                        $data_line['Cheque']['payer_account'] = $pieces[1];
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                        fclose($handle);
                        // insert the current line into the database.
                        $data_line['Cheque']['batch_id'] = $this->Batch->id;
                        $data_line['Cheque']['transaction_id'] = date('Ymd', strtotime($data_line['Cheque']['creation_date'])) . $data_line['Cheque']['cheque_number'];
                        $data_line['Cheque']['microcode'] = 'NO_MICROCODE';

                        $this->Cheque->create();
                        if (!$this->Cheque->save($data_line)) {
                            $this->log('Cannot save ' . $data_line['Cheque']['cheque_number']);
                            $errors = $this->Cheque->invalidFields();
                            $this->log('Errors ' . print_r($errors, true));
                        }
                    }
                }
            }
            closedir($dh);
            $this->set('msg', 'OK');
            return;
        }
        $this->set('msg', 'OK');
    }

    function import_incoming_batch($file_name = '') {
        $this->layout = 'message_layout';

        if (!file_exists(FILES_DIR . 'incomings' . DS . $file_name) || $file_name == '') {
            $this->set('msg', 'File ' . $file_name . ' does not exist.');
            $this->log('File "' . $file_name . '" does not exist. [function: batches::import_incoming_batch()]');
            return;
        }

        $file_name_only = substr($file_name, 0, strrpos($file_name, "."));

        // extract the zip file
        $zip = zip_open(FILES_DIR . 'incomings' . DS . $file_name);
        $out_dir = FILES_DIR . 'incomings' . DS . $file_name_only;
        $xml_file_name = '';

        if (!is_dir($out_dir))
            mkdir($out_dir, 0777); // create the directory with full access to the PHP user

        if ($zip) {
            $file_found = false;
            while ($zip_entry = zip_read($zip)) {
                $fp = fopen($out_dir . DS . zip_entry_name($zip_entry), "w");
                if (zip_entry_open($zip, $zip_entry, "r")) {
                    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    fwrite($fp, "$buf");
                    zip_entry_close($zip_entry);
                    fclose($fp);
                    $file_found = true;
                }
                if (strpos(zip_entry_name($zip_entry), '.xml') !== FALSE) {
                    $xml_file_name = zip_entry_name($zip_entry);
                }
                /* if (strpos(zip_entry_name($zip_entry), '_back.tiff') !== FALSE) {
                  $back_file_name = zip_entry_name($zip_entry);
                  $f_name_only = substr($back_file_name, 0, strrpos($back_file_name, "."));
                  $origin = $out_dir . DS . $back_file_name ;
                  $destin = $out_dir . DS . $f_name_only . '.jpg';
                  $exec = 'convert ' . $origin . ' ' . $destin . ' 2>&1';
                  @exec($exec, $exec_output, $exec_retval);
                  unlink($origin);
                  copy($destin, $origin);
                  unlink($destin);
                  } */
            }
            if (!$file_found) {
                // put the file on the FC IN folder as an xml file: because it is not a zip file.
                copy(FILES_DIR . 'incomings' . DS . $file_name, FC_IN_DIR . DS . $file_name_only . '.xml');
                $this->set('msg', 'File ' . $file_name . ' is not a pacs005 message.');
                $this->log('File ' . $file_name . ' is not a pacs005 message. But it is sent to Flex -> ACH >> IN folder.');
                return;
            }
            zip_close($zip);
            unlink(FILES_DIR . 'incomings' . DS . $file_name);
        } else {
            // put the file on the FC IN folder as an xml file: because it is not a zip file.
            copy(FILES_DIR . 'incomings' . DS . $file_name, FC_IN_DIR . DS . $file_name_only . '.xml');
            $this->set('msg', 'File ' . $file_name . ' is not a pacs005 message.');
            $this->log('File ' . $file_name . ' is not a pacs005 message. But it is sent to Flex -> ACH >> IN folder.');
            unlink(FILES_DIR . 'incomings' . DS . $file_name);
            return;
        }

        // re-check if the xml_file is found in the zip or not.
        if ($xml_file_name == '') {
            $dh = opendir($out_dir . DS);
            while (($file = readdir($dh)) !== false) {
                if (strpos($file, '.xml') !== FALSE) {
                    $xml_file_name = $file;
                    break;
                }
            }
        }


        $xml_file_name_only = $xml_file_name;
        $xml_file_name = $out_dir . DS . $xml_file_name;

        // put the xml file on the FC IN folder
        //copy($xml_file_name, FC_IN_DIR . DS . $xml_file_name_only);

        App::import('BehXmlParser');

        BehXmlParser::setStartingValues();

        $xml_parser = xml_parser_create();
        xml_set_element_handler($xml_parser, "BehXmlParser::startElement", "BehXmlParser::endElement");
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_character_data_handler($xml_parser, "BehXmlParser::characterData");
        if (!($fp = fopen($xml_file_name, "r"))) {
            $this->set('msg', 'File ' . $xml_file_name . ' could not open for XML input.');
            $this->log('File "' . $xml_file_name . ' in ' . $file_name . '" could not open for XML input. [function: batches::import_incoming_batch()]');
            return;
        }

        while ($data = fread($fp, 4096)) {
            $data = str_replace('&amp;', 'AND', $data);
			$data = str_replace("'", ' ', $data);
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                $this->set('msg', sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
                $this->log(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)) . ' [function: batches::import_incoming_batch()]');
                return;
            }
        }
        xml_parser_free($xml_parser);

        $obj->xml = BehXmlParser::getXmlObject();
        $cheques = $obj->xml->Document->BlkChq->Chq;
        $header = $obj->xml->Document->BlkChq->GrpHdr;

        $batch_data = array('Batch' => array(
                'name' => $file_name_only,
                'message_id' => $header->MsgId->data,
				'cre_dt_tm' => $header->CreDtTm->data,
				'intr_bk_sttlm_dt' => $header->IntrBkSttlmDt->data,
                'maker_id' => 0,
                'checker_id' => 0,
                'authorized' => 1,
                'mode' => 'incoming',
                'date_sent' => date('Y-m-d H:i:s'),
                'sent' => 1
            ));

        $this->Batch->create();
        $this->Batch->save($batch_data);

        $batch = $this->Batch->read(null, $this->Batch->id);

        $this->loadModel('ChequeException');

        // Generate the xml file
        $result = '';
        $total_amount = 0;
        // 1. bring a cheque template file
        $handle = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming3.xml', "r");
        $cheque_template = fread($handle, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming3.xml'));
        fclose($handle);
        $number_of_approved_cheques = 0;

        $i = 1;
        if (is_array($cheques)) { // if there are more than one cheque in the Batch
            foreach ($cheques as $cheque) {
                $cheque_data = array('Cheque' => array(
                        'batch_id' => $this->Batch->id,
                        'rejected' => 0,
                        'transaction_code' => 'CH',
                        'instrument_id' => isset($cheque->PmtId->InstrId) ? $cheque->PmtId->InstrId->data : '',
                        'end_to_end_id' => $cheque->PmtId->EndToEndId->data,
                        'transaction_id' => $cheque->PmtId->TxId->data,
                        'microcode' => 'NO_MICROCODE',
                        'branch_code' => $cheque->ChequeTx->BranchCode->data,
                        'creation_date' => date('Y-m-d H:i:s'),
                        'beneficiary_account' => $cheque->DbtrAcct->Id->IBAN->data,
                        'cheque_number' => $cheque->ChequeTx->ChkNmbr->data,
                        'cheque_index' => $i++,
                        'remittance_information' => (isset($cheque->RmtInf)) ? $cheque->RmtInf->Ustrd->data : '',
                        'bank_code' => $cheque->ChequeTx->BankCode->data,
                        'issuer_bank_bic' => $cheque->CdtrAgt->FinInstnId->BIC->data,
                        'amount' => $cheque->IntrBkSttlmAmt->data,
                        'payer_name' => $cheque->Cdtr->Nm->data,
                        'endorsement_number' => '',
                        'currency' => $cheque->IntrBkSttlmAmt->attr['Ccy'],
                        'beneficiary_name' => $cheque->Dbtr->Nm->data,
                        'payer_account' => $cheque->CdtrAcct->Id->IBAN->data
                    ));
                $this->Batch->Cheque->create();
                if (!$this->Batch->Cheque->save($cheque_data)) {
                    $this->log('Cannot save ' . print_r($cheque_data, true));
                    $this->log('Cannot save cheque object ' . print_r($cheque, true));
                    $errors = $this->Batch->Cheque->invalidFields();
                    $this->log('Errors ' . print_r($errors, true));
                }

                // Check whether the cheque is correct or not
                $exception_cheque = false;
                $exception_message = ''; // to hold the error messages
                // check if the account number is available in Flex
                //  CPOs come with Account Number as CASH, CPO, C.P.O., or 2020225, in this case 
                //  I just pass the record, without any checking.
                if ($cheque->DbtrAcct->Id->IBAN->data != 'C.P.O' && $cheque->DbtrAcct->Id->IBAN->data != 'CPO'
                        && $cheque->DbtrAcct->Id->IBAN->data != '2020225' && $cheque->DbtrAcct->Id->IBAN->data != 'CASH' && !$this->_isAccountNumberAvailable($cheque->DbtrAcct->Id->IBAN->data)) {
                    $exception_message .= 'Account Number ' . $cheque->DbtrAcct->Id->IBAN->data . ' is not avaibale in FLEXCUBE.<br/>';
                    $exception_cheque = true;
                }
                // check if the account is having enough balance.
                if ($cheque->DbtrAcct->Id->IBAN->data != 'C.P.O' && $cheque->DbtrAcct->Id->IBAN->data != 'CPO'
                        && $cheque->DbtrAcct->Id->IBAN->data != '2020225' && $cheque->DbtrAcct->Id->IBAN->data != 'CASH' &&
                        !$exception_cheque &&
                        !$this->_isAccountHavingEnoughBalance($cheque->DbtrAcct->Id->IBAN->data, $cheque->IntrBkSttlmAmt->data)) {
                    $exception_message .= 'Account ' . $cheque->DbtrAcct->Id->IBAN->data . ' is not having enough balance.<br/>';
                    $exception_cheque = true;
                }
                // check if the branch code is correct
                if (!$this->_isBranchCodeCorrect($cheque->ChequeTx->BranchCode->data)) {
                    $exception_message .= 'Branch code ' . $cheque->ChequeTx->BranchCode->data . ' is not avaibale in the list of branches.<br/>';
                    $exception_cheque = true;
                }
                // check if the branch system date is today or not
                if (!$this->_isBranchSystemDateToday($cheque->ChequeTx->BranchCode->data)) {
                    $exception_message .= 'Branch System date for branch ' . $cheque->ChequeTx->BranchCode->data . ' is not on today.<br/>';
                    $exception_cheque = true;
                }

                // if one of the above cases are occurred ... don't send the cheque to FC.
                if ($exception_cheque) {
                    // rather create an exception record for the cheque.
                    $cheque_exception['ChequeException'] = array();
                    $cheque_exception['ChequeException']['cheque_id'] = $this->Batch->Cheque->id;
                    $cheque_exception['ChequeException']['message'] = $exception_message;
                    $cheque_exception['ChequeException']['status'] = 'C';

                    $this->ChequeException->create();
                    $this->ChequeException->save($cheque_exception);
                } else {
                    if (!isset($cheque->PmtId->InstrId) && !isset($cheque->RmtInf)) {
                        $handlex = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_all.xml', "r");
                        $cheque_template = fread($handlex, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_all.xml'));
                        fclose($handlex);
                    } elseif (!isset($cheque->PmtId->InstrId)) {
                        $handlex = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_instr.xml', "r");
                        $cheque_template = fread($handlex, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_instr.xml'));
                        fclose($handlex);
                    } elseif (!isset($cheque->RmtInf)) {
                        $handlex = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_rmtinf.xml', "r");
                        $cheque_template = fread($handlex, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_rmtinf.xml'));
                        fclose($handlex);
                    }
                    // cheque is correct and ready to foreward to FC
                    $chk = str_replace('{PmtId.InstrId}', isset($cheque->PmtId->InstrId) ? $cheque->PmtId->InstrId->data : $cheque->PmtId->TxId->data, $cheque_template);
                    $chk = str_replace('{PmtId.EndToEndId}', $cheque->PmtId->EndToEndId->data, $chk);
                    $chk = str_replace('{PmtId.TxId}', $cheque->PmtId->TxId->data, $chk);
                    $chk = str_replace('{PmtTpInf.SvcLvl.Cd}', $cheque->PmtTpInf->SvcLvl->Cd->data, $chk);
                    $chk = str_replace('{PmtTpInf.LclInstrm.Cd}', $cheque->PmtTpInf->LclInstrm->Cd->data, $chk);
                    $chk = str_replace('{IntrBkSttlmAmt}', $cheque->IntrBkSttlmAmt->data, $chk);
                    $chk = str_replace('{ChrgBr}', $cheque->ChrgBr->data, $chk);
                    $chk = str_replace('{ChequeTx.ChkNmbr}', $cheque->ChequeTx->ChkNmbr->data, $chk);
                    $chk = str_replace('{ChequeTx.AccNo}', $cheque->ChequeTx->AccNo->data, $chk);
                    $chk = str_replace('{ChequeTx.Microcode}', $cheque->ChequeTx->Microcode->data, $chk);
                    $chk = str_replace('{ChequeTx.BankCode}', $cheque->ChequeTx->BankCode->data, $chk);
                    $chk = str_replace('{ChequeTx.BranchCode}', $cheque->ChequeTx->BranchCode->data, $chk);
                    $chk = str_replace('{Cdtr.Nm}', $cheque->Cdtr->Nm->data, $chk);
                    $chk = str_replace('{CdtrAcct.Id.IBAN}', $cheque->CdtrAcct->Id->IBAN->data, $chk);
                    $chk = str_replace('{CdtrAgt.FinInstnId.BIC}', $cheque->CdtrAgt->FinInstnId->BIC->data, $chk);
                    $chk = str_replace('{Dbtr.Nm}', $cheque->Dbtr->Nm->data, $chk);
                    $chk = str_replace('{Dbtr.Id.OrgId.BICOrBEI}', $cheque->Dbtr->Id->OrgId->BICOrBEI->data, $chk);
                    $chk = str_replace('{DbtrAcct.Id.IBAN}', $cheque->DbtrAcct->Id->IBAN->data, $chk);
                    $chk = str_replace('{DbtrAgt.FinInstnId.BIC}', $cheque->DbtrAgt->FinInstnId->BIC->data, $chk);
                    $chk = str_replace('{RmtInf.Ustrd}', isset($cheque->RmtInf) ? $cheque->RmtInf->Ustrd->data : '', $chk);

                    $result .= $chk . "\r\n";
                    $number_of_approved_cheques++;
                    $total_amount += $cheque->IntrBkSttlmAmt->data;
                }
            }
            if ($number_of_approved_cheques > 0) {
                // Generate the xml message from the xml template file
                $handle2 = fopen(FILES_DIR . 'cheque_templates' . DS . 'container_incoming3.xml', "r");
                $xml_template = fread($handle2, filesize(FILES_DIR . 'cheque_templates' . DS . 'container_incoming3.xml'));
                fclose($handle2);

                $msgid = $header->MsgId->data;
                $xml_template = str_replace('{MsgId}', $msgid, $xml_template);
                $xml_template = str_replace('{CreDtTm}', $header->CreDtTm->data, $xml_template);
                $xml_template = str_replace('{NbOfTxs}', $number_of_approved_cheques, $xml_template);
                $xml_template = str_replace('{TtlIntrBkSttlmAmt}', $total_amount, $xml_template);
                $xml_template = str_replace('{IntrBkSttlmDt}', $header->IntrBkSttlmDt->data, $xml_template);
                $xml_template = str_replace('{SttlmInf.SttlmMtd}', $header->SttlmInf->SttlmMtd->data, $xml_template);
                $xml_template = str_replace('{SttlmInf.ClrSys.Prtry}', $header->SttlmInf->ClrSys->Prtry->data, $xml_template);
                $xml_template = str_replace('{InstdAgt.FinInstnId.BIC}', $header->InstdAgt->FinInstnId->BIC->data, $xml_template);
                $xml_template = str_replace('{Cheques}', $result, $xml_template);

                $xml_message = $xml_template;

                $handle_xml = fopen(FC_IN_DIR . DS . $batch['Batch']['name'] . '.xml', 'a');
                if ($handle_xml !== FALSE) {
                    fwrite($handle_xml, $xml_message);
                    fclose($handle_xml);
                }
            }
            $this->set('msg', 'OK');
            return;
        } else { // if the batch has only one cheque
            $cheque_data = array('Cheque' => array(
                    'batch_id' => $this->Batch->id,
                    'rejected' => 0,
                    'transaction_code' => 'CH',
                    'transaction_id' => $cheques->PmtId->TxId->data,
                    'instrument_id' => isset($cheques->PmtId->InstrId) ? $cheques->PmtId->InstrId->data : '',
                    'end_to_end_id' => $cheques->PmtId->EndToEndId->data,
                    'microcode' => 'NO_MICROCODE',
                    'branch_code' => $cheques->ChequeTx->BranchCode->data,
                    'creation_date' => date('Y-m-d H:i:s'),
                    'beneficiary_account' => $cheques->DbtrAcct->Id->IBAN->data,
                    'cheque_number' => $cheques->ChequeTx->ChkNmbr->data,
                    'cheque_index' => $i++,
                    'remittance_information' => (isset($cheques->RmtInf)) ? $cheques->RmtInf->Ustrd->data : '',
                    'bank_code' => $cheques->ChequeTx->BankCode->data,
                    'issuer_bank_bic' => $cheques->CdtrAgt->FinInstnId->BIC->data,
                    'amount' => $cheques->IntrBkSttlmAmt->data,
                    'payer_name' => $cheques->Cdtr->Nm->data,
                    'endorsement_number' => '',
                    'currency' => $cheques->IntrBkSttlmAmt->attr['Ccy'],
                    'beneficiary_name' => $cheques->Dbtr->Nm->data,
                    'payer_account' => $cheques->CdtrAcct->Id->IBAN->data
                    ));

            $this->Batch->Cheque->create();
            if (!$this->Batch->Cheque->save($cheque_data)) {
                $this->log('Cannot save ' . print_r($cheque_data, true));
                $this->log('Cannot save cheque object ' . print_r($cheques, true));
                $errors = $this->Batch->Cheque->invalidFields();
                $this->log('Errors ' . print_r($errors, true));
            }

            // Check whether the cheque is correct or not
            $exception_cheque = false;
            $exception_message = '';
            if ($cheques->DbtrAcct->Id->IBAN->data != 'C.P.O' && $cheques->DbtrAcct->Id->IBAN->data != 'CPO'
                    && $cheques->DbtrAcct->Id->IBAN->data != '2020225' && $cheques->DbtrAcct->Id->IBAN->data != 'CASH'
                    && !$this->_isAccountNumberAvailable($cheques->DbtrAcct->Id->IBAN->data)) {
                $exception_message .= 'Account Number ' . $cheques->DbtrAcct->Id->IBAN->data . ' is not avaibale in FLEXCUBE.<br/>';
                $exception_cheque = true;
            }
            if ($cheques->DbtrAcct->Id->IBAN->data != 'C.P.O' && $cheques->DbtrAcct->Id->IBAN->data != 'CPO'
                    && $cheques->DbtrAcct->Id->IBAN->data != '2020225' && $cheques->DbtrAcct->Id->IBAN->data != 'CASH' &&
                    !$exception_cheque && !$this->_isAccountHavingEnoughBalance($cheques->DbtrAcct->Id->IBAN->data, $cheques->IntrBkSttlmAmt->data)) {
                $exception_message .= 'Account ' . $cheques->DbtrAcct->Id->IBAN->data . ' is not having enough balance.<br/>';
                $exception_cheque = true;
            }
            if (!$this->_isBranchCodeCorrect($cheques->ChequeTx->BranchCode->data)) {
                $exception_message .= 'Branch code ' . $cheques->ChequeTx->BranchCode->data . ' is not avaibale in the list of branches.<br/>';
                $exception_cheque = true;
            }
            if (!$this->_isBranchSystemDateToday($cheques->ChequeTx->BranchCode->data)) {
                $exception_message .= 'Branch System date for branch ' . $cheques->ChequeTx->BranchCode->data . ' is not on today.<br/>';
                $exception_cheque = true;
            }

            // TODO: Add if clause to check whether the Cheque # is available for the account holder.
            // 
            // if one of the above cases are occurred ... don't send the cheque to FC.
            if ($exception_cheque) {
                $cheque_exception = array();
                $cheque_exception['ChequeException'] = array();
                $cheque_exception['ChequeException']['cheque_id'] = $this->Batch->Cheque->id;
                $cheque_exception['ChequeException']['message'] = $exception_message;
                $cheque_exception['ChequeException']['status'] = 'C';

                $this->ChequeException->create();
                $this->ChequeException->save($cheque_exception);
            } else {
                if (!isset($cheque->PmtId->InstrId) && !isset($cheque->RmtInf)) {
                    $handlex = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_all.xml', "r");
                    $cheque_template = fread($handlex, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_all.xml'));
                    fclose($handlex);
                } elseif (!isset($cheque->PmtId->InstrId)) {
                    $handlex = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_instr.xml', "r");
                    $cheque_template = fread($handlex, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_instr.xml'));
                    fclose($handlex);
                } elseif (!isset($cheque->RmtInf)) {
                    $handlex = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_rmtinf.xml', "r");
                    $cheque_template = fread($handlex, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming_no_rmtinf.xml'));
                    fclose($handlex);
                }
                // cheque is correct and ready to foreward to FC
                $chk = str_replace('{PmtId.InstrId}', isset($cheques->PmtId->InstrId) ? $cheques->PmtId->InstrId->data : '', $cheque_template);
                $chk = str_replace('{PmtId.EndToEndId}', $cheques->PmtId->EndToEndId->data, $chk);
                $chk = str_replace('{PmtId.TxId}', $cheques->PmtId->TxId->data, $chk);
                $chk = str_replace('{PmtTpInf.SvcLvl.Cd}', $cheques->PmtTpInf->SvcLvl->Cd->data, $chk);
                $chk = str_replace('{PmtTpInf.LclInstrm.Cd}', $cheques->PmtTpInf->LclInstrm->Cd->data, $chk);
                $chk = str_replace('{IntrBkSttlmAmt}', $cheques->IntrBkSttlmAmt->data, $chk);
                $chk = str_replace('{ChrgBr}', $cheques->ChrgBr->data, $chk);
                $chk = str_replace('{ChequeTx.ChkNmbr}', $cheques->ChequeTx->ChkNmbr->data, $chk);
                $chk = str_replace('{ChequeTx.AccNo}', $cheques->ChequeTx->AccNo->data, $chk);
                $chk = str_replace('{ChequeTx.Microcode}', $cheques->ChequeTx->Microcode->data, $chk);
                $chk = str_replace('{ChequeTx.BankCode}', $cheques->ChequeTx->BankCode->data, $chk);
                $chk = str_replace('{ChequeTx.BranchCode}', $cheques->ChequeTx->BranchCode->data, $chk);
                $chk = str_replace('{Cdtr.Nm}', $cheques->Cdtr->Nm->data, $chk);
                $chk = str_replace('{CdtrAcct.Id.IBAN}', $cheques->CdtrAcct->Id->IBAN->data, $chk);
                $chk = str_replace('{CdtrAgt.FinInstnId.BIC}', $cheques->CdtrAgt->FinInstnId->BIC->data, $chk);
                $chk = str_replace('{Dbtr.Nm}', $cheques->Dbtr->Nm->data, $chk);
                $chk = str_replace('{Dbtr.Id.OrgId.BICOrBEI}', $cheques->Dbtr->Id->OrgId->BICOrBEI->data, $chk);
                $chk = str_replace('{DbtrAcct.Id.IBAN}', $cheques->DbtrAcct->Id->IBAN->data, $chk);
                $chk = str_replace('{DbtrAgt.FinInstnId.BIC}', $cheques->DbtrAgt->FinInstnId->BIC->data, $chk);
                $chk = str_replace('{RmtInf.Ustrd}', isset($cheques->RmtInf) ? $cheques->RmtInf->Ustrd->data : '', $chk);

                $result .= $chk . "\r\n";
                $number_of_approved_cheques++;
                $total_amount += $cheques->IntrBkSttlmAmt->data;
            }

            if ($number_of_approved_cheques > 0) {
                // Generate the xml message from the xml template file
                $handle2 = fopen(FILES_DIR . 'cheque_templates' . DS . 'container_incoming3.xml', "r");
                $xml_template = fread($handle2, filesize(FILES_DIR . 'cheque_templates' . DS . 'container_incoming3.xml'));
                fclose($handle2);

                $msgid = $header->MsgId->data;
                $xml_template = str_replace('{MsgId}', $msgid, $xml_template);
                $xml_template = str_replace('{CreDtTm}', $header->CreDtTm->data, $xml_template);
                $xml_template = str_replace('{NbOfTxs}', $number_of_approved_cheques, $xml_template);
                $xml_template = str_replace('{TtlIntrBkSttlmAmt}', $total_amount, $xml_template);
                $xml_template = str_replace('{IntrBkSttlmDt}', $header->IntrBkSttlmDt->data, $xml_template);
                $xml_template = str_replace('{SttlmInf.SttlmMtd}', $header->SttlmInf->SttlmMtd->data, $xml_template);
                $xml_template = str_replace('{SttlmInf.ClrSys.Prtry}', $header->SttlmInf->ClrSys->Prtry->data, $xml_template);
                $xml_template = str_replace('{InstdAgt.FinInstnId.BIC}', $header->InstdAgt->FinInstnId->BIC->data, $xml_template);
                $xml_template = str_replace('{Cheques}', $result, $xml_template);

                $xml_message = $xml_template;

                $handle_xml = fopen(FC_IN_DIR . DS . $batch['Batch']['name'] . '.xml', 'a');
                if ($handle_xml !== FALSE) {
                    fwrite($handle_xml, $xml_message);
                    fclose($handle_xml);
                }
            }
            $this->set('msg', 'OK');
            return;
        }
        $this->set('msg', 'Unknown error is occured. [function: batches::import_incoming_batch()]');
        return;
    }

    function _isAccountNumberAvailable($account_number) {
        //$account_number = '1021010001059011';
        $query = "SELECT BRANCH_CODE, CUST_AC_NO, AC_DESC, ACY_AVL_BAL, ACY_CURR_BALANCE 
                        FROM STTM_CUST_ACCOUNT 
                    WHERE CUST_AC_NO='$account_number'";
        $this->loadModel('FlexCube');

        $account = $this->FlexCube->query($query);

        return (count($account) > 0 && isset($account[0][0]) && $account[0][0]['CUST_AC_NO'] == $account_number);
    }

    function _isBranchCodeCorrect($branch_code) {
        $this->loadModel('Branch');

        $branch = $this->Branch->find('all', array('conditions' => array('Branch.ats_code' => $branch_code)));

        return (count($branch) == 1) && ($branch[0]['Branch']['bank_id'] == 1);
    }

    function _isAccountHavingEnoughBalance($account_number, $amount) {
        if ($this->_isAccountNumberAvailable($account_number)) {
            // and if 
            $query = "SELECT BRANCH_CODE, CUST_AC_NO, AC_DESC, ACY_AVL_BAL, ACY_CURR_BALANCE 
                        FROM STTM_CUST_ACCOUNT
                    WHERE CUST_AC_NO='$account_number'";
            $this->loadModel('FlexCube');

            $account = $this->FlexCube->query($query);
            if ($amount <= $account[0][0]['ACY_AVL_BAL'])
                return true;
            return false;
        }
        return false;
    }

    function _isBranchSystemDateToday($branch_code) {
        if ($this->_isBranchCodeCorrect($branch_code)) {
            $this->loadModel('Branch');

            $branch = $this->Branch->find('all', array('conditions' => array('Branch.ats_code' => $branch_code)));
            $branch_code = $branch[0]['Branch']['fc_code'];
            $query = "SELECT A.branch_code, A.branch_name, A.currentpostingdate, B.TODAY 
				FROM Fbtm_Branch_Info A JOIN sttm_dates B ON(A.BRANCH_CODE=B.BRANCH_CODE) 
			WHERE A.BRANCH_CODE=$branch_code";
            $this->loadModel('FlexCube');
            $br = $this->FlexCube->query($query);

            if (date('d-M-Y', strtotime($br[0]['B']['today'])) == date('d-M-Y'))
                return true;
            return false;
        }
        return false;
    }

    function import_incomings() {
        
    }

    function import_file($file_name = '') {
        if ($file_name == '') {
            $this->Session->setFlash(__('Invalid incoming batch file', true));
            $this->render('/elements/failure');
        }

        $file_name_only = substr($file_name, 0, strrpos($file_name, "."));

        // extract the zip file
        $zip = zip_open(EATS_OUT_DIR . DS . $file_name);
        $out_dir = FILES_DIR . 'incomings' . DS . $file_name_only;
        $xml_file_name = '';

        if (!is_dir($out_dir))
            mkdir($out_dir, 0777);

        if ($zip) {
            while ($zip_entry = zip_read($zip)) {
                $fp = fopen($out_dir . DS . zip_entry_name($zip_entry), "w");
                if (zip_entry_open($zip, $zip_entry, "r")) {
                    $buf = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                    fwrite($fp, "$buf");
                    zip_entry_close($zip_entry);
                    fclose($fp);
                }
                if (strpos(zip_entry_name($zip_entry), '.xml') !== FALSE) {
                    $xml_file_name = zip_entry_name($zip_entry);
                }
            }
            zip_close($zip);
            unlink(EATS_OUT_DIR . DS . $file_name);
        }

        if ($xml_file_name == '') {
            $dh = opendir($out_dir . DS);
            while (($file = readdir($dh)) !== false) {
                if (strpos($file, '.xml') !== FALSE) {
                    $xml_file_name = $file;
                    break;
                }
            }
        }

        $xml_file_name = $out_dir . DS . $xml_file_name;

        App::import('BehXmlParser');

        BehXmlParser::setStartingValues();

        $xml_parser = xml_parser_create();
        xml_set_element_handler($xml_parser, "BehXmlParser::startElement", "BehXmlParser::endElement");
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_character_data_handler($xml_parser, "BehXmlParser::characterData");
        if (!($fp = fopen($xml_file_name, "r"))) {
            die("could not open XML input");
        }

        while ($data = fread($fp, 4096)) {
            $data = str_replace('&amp;', 'AND', $data);
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                die(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
            }
        }
        xml_parser_free($xml_parser);

        $obj->xml = BehXmlParser::getXmlObject();
        $cheques = $obj->xml->Document->BlkChq->Chq;
        $header = $obj->xml->Document->BlkChq->GrpHdr;

        $batch_data = array('Batch' => array(
                'name' => $file_name_only,
                'message_id' => $header->MsgId->data,
                'maker_id' => $this->Session->read('Auth.User.id'),
                'checker_id' => 0,
                'authorized' => 0,
                'mode' => 'incoming',
                'date_sent' => date('Y-m-d H:i:s'),
                'sent' => 0
                ));

        $this->Batch->create();
        $this->Batch->save($batch_data);

        $i = 1;
        if (is_array($cheques)) {
            foreach ($cheques as $cheque) {
                $cheque_data = array('Cheque' => array(
                        'batch_id' => $this->Batch->id,
                        'rejected' => 0,
                        'transaction_code' => 'CH',
                        'transaction_id' => $cheque->PmtId->TxId->data,
                        'instrument_id' => $cheque->PmtId->InstrId->data,
                        'end_to_end_id' => $cheque->PmtId->EndToEndId->data,
                        'microcode' => 'NO_MICROCODE',
                        'branch_code' => $cheque->ChequeTx->BranchCode->data,
                        'creation_date' => date('Y-m-d H:i:s'),
                        'beneficiary_account' => $cheque->DbtrAcct->Id->IBAN->data,
                        'cheque_number' => $cheque->ChequeTx->ChkNmbr->data,
                        'cheque_index' => $i++,
                        'remittance_information' => (isset($cheque->RmtInf)) ? $cheque->RmtInf->Ustrd->data : '',
                        'bank_code' => $cheque->ChequeTx->BankCode->data,
                        'issuer_bank_bic' => $cheque->CdtrAgt->FinInstnId->BIC->data,
                        'amount' => $cheque->IntrBkSttlmAmt->data,
                        'payer_name' => $cheque->Cdtr->Nm->data,
                        'endorsement_number' => '',
                        'currency' => $cheque->IntrBkSttlmAmt->attr['Ccy'],
                        'beneficiary_name' => $cheque->Dbtr->Nm->data,
                        'payer_account' => $cheque->CdtrAcct->Id->IBAN->data
                        ));

                $this->Batch->Cheque->create();
                if (!$this->Batch->Cheque->save($cheque_data)) {
                    $this->log('Cannot save ' . print_r($cheque_data, true));
                    $this->log('Cannot save cheque object ' . print_r($cheque, true));
                    $errors = $this->Batch->Cheque->invalidFields();
                    $this->log('Errors ' . print_r($errors, true));
                }
            }
        } else {
            $cheque_data = array('Cheque' => array(
                    'batch_id' => $this->Batch->id,
                    'rejected' => 0,
                    'transaction_code' => 'CH',
                    'transaction_id' => $cheques->PmtId->TxId->data,
                    'instrument_id' => $cheques->PmtId->InstrId->data,
                    'end_to_end_id' => $cheques->PmtId->EndToEndId->data,
                    'microcode' => 'NO_MICROCODE',
                    'branch_code' => $cheques->ChequeTx->BranchCode->data,
                    'creation_date' => date('Y-m-d H:i:s'),
                    'beneficiary_account' => $cheques->DbtrAcct->Id->IBAN->data,
                    'cheque_number' => $cheques->ChequeTx->ChkNmbr->data,
                    'cheque_index' => $i++,
                    'remittance_information' => (isset($cheques->RmtInf)) ? $cheques->RmtInf->Ustrd->data : '',
                    'bank_code' => $cheques->ChequeTx->BankCode->data,
                    'issuer_bank_bic' => $cheques->CdtrAgt->FinInstnId->BIC->data,
                    'amount' => $cheques->IntrBkSttlmAmt->data,
                    'payer_name' => $cheques->Cdtr->Nm->data,
                    'endorsement_number' => '',
                    'currency' => $cheques->IntrBkSttlmAmt->attr['Ccy'],
                    'beneficiary_name' => $cheques->Dbtr->Nm->data,
                    'payer_account' => $cheques->CdtrAcct->Id->IBAN->data
                    ));

            $this->Batch->Cheque->create();
            if (!$this->Batch->Cheque->save($cheque_data)) {
                $this->log('Cannot save ' . print_r($cheque_data, true));
                $this->log('Cannot save cheque object ' . print_r($cheque, true));
                $errors = $this->Batch->Cheque->invalidFields();
                $this->log('Errors ' . print_r($errors, true));
            }
        }
    }

    /**
     * Function called from the approve action view, when the checker user clicks on the 
     * Approve and Send button.
     * @param integer $id 
     */
    function send($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        $this->Batch->recursive = 2;
        $batch = $this->Batch->read(null, $id);

        // Generate the xml file
        $result = '';
        $total_amount = 0;
        // 1. bring a cheque template file
        $handle = fopen(FILES_DIR . 'cheque_templates' . DS . 'cheque.xml', "r");
        $cheque_template = fread($handle, filesize(FILES_DIR . 'cheque_templates' . DS . 'cheque.xml'));
        fclose($handle);
        $number_of_approved_cheques = 0;
        mkdir(FILES_DIR . $batch['Batch']['name'] . '_o');
        // 2. generate a cheque block for each cheque in the batch which is not rejected
        foreach ($batch['Cheque'] as $cheque) {
            if ($cheque['rejected'] == 1)
                continue;
            $chk = str_replace('{ChkNmbr}', $cheque['cheque_number'], $cheque_template);
            $chk = str_replace('{TxId}', $cheque['transaction_id'], $chk);
            $chk = str_replace('{Amount}', $cheque['amount'], $chk);
            $chk = str_replace('{AccNo}', $cheque['payer_account'], $chk);
            $chk = str_replace('{BankCode}', $cheque['bank_code'], $chk);
            $chk = str_replace('{BranchCode}', $cheque['branch_code'], $chk);
            $chk = str_replace('{CreditorName}', $cheque['beneficiary_name'], $chk);
            $chk = str_replace('{CreditorAcct}', $cheque['beneficiary_account'], $chk);
            $chk = str_replace('{DebitorName}', $cheque['payer_name'], $chk);
            $chk = str_replace('{DebitorAgentBIC}', Configure::read($cheque['bank_code']), $chk);
            $chk = str_replace('{DebitorAcct}', $cheque['payer_account'], $chk);
            $chk = str_replace('{Ccy}', $cheque['currency'], $chk);

            copy(FILES_DIR . 'outgoings' . DS . $batch['Batch']['name'] . DS . $cheque['cheque_index'] . '.chequeFrontImage', FILES_DIR . $batch['Batch']['name'] . '_o' . DS . $cheque['transaction_id'] . '_front.tiff');
            copy(FILES_DIR . 'outgoings' . DS . $batch['Batch']['name'] . DS . $cheque['cheque_index'] . '.chequeRearImage', FILES_DIR . $batch['Batch']['name'] . '_o' . DS . $cheque['transaction_id'] . '_back.tiff');

            $result .= $chk . "\r\n";
            $number_of_approved_cheques++;
            $total_amount += $cheque['amount'];
        }
        // 3. generate the xml message from the xml template file
        $handle2 = fopen(FILES_DIR . 'cheque_templates' . DS . 'container.xml', "r");
        $xml_template = fread($handle2, filesize(FILES_DIR . 'cheque_templates' . DS . 'container.xml'));
        fclose($handle2);

        $msgid = 'IQF' . date('ymdHis', strtotime($batch['Batch']['created'])) . '000000000001';
        $xml_template = str_replace('{MsgId}', $msgid, $xml_template);
        $xml_template = str_replace('{YYYY-MM-DD}', date('Y-m-d', strtotime($batch['Batch']['created'])), $xml_template);
        $xml_template = str_replace('{YYYY-MM-DD+2}', date('Y-m-d', strtotime($batch['Batch']['created'] . ((date('l', strtotime($batch['Batch']['created'])) == 'Friday') ? ' +4 days' : ' +2 days'))), $xml_template);
        $xml_template = str_replace('{HH:MM:SS}', date('H:i:s', strtotime($batch['Batch']['created'])), $xml_template);
        $xml_template = str_replace('{NumberOfTxs}', $number_of_approved_cheques, $xml_template);
        $xml_template = str_replace('{TotalAmount}', number_format($total_amount, 2, '.', ''), $xml_template);
        $xml_template = str_replace('{Cheques}', $result, $xml_template);
        $xml_template = str_replace('{Ccy}', $batch['Cheque'][0]['currency'], $xml_template);

        $xml_message = $xml_template;

        $handle_xml = fopen(FILES_DIR . $batch['Batch']['name'] . '_o' . DS . 'cheque.xml', 'a');
        fwrite($handle_xml, $xml_message);
        fclose($handle_xml);

        // 4. collect and zip all the image files (renaming them to tiff::Already done in the foreach loop) with the xml file
        $handle_f = opendir(FILES_DIR . $batch['Batch']['name'] . '_o');
        $files_to_zip = array();
        /* This is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle_f))) {
            if ($file != '.' && $file != '..')
                $files_to_zip[$file] = FILES_DIR . $batch['Batch']['name'] . '_o' . DS . $file;
        }
        closedir($handle_f);

        $zip_file = FILES_DIR . $batch['Batch']['name'] . '_o' . '.zip';
        $result = $this->create_zip($files_to_zip, $zip_file);
        if (!$result) {
            echo "Cannot create the zip file";
        } else {
            // 5. put the zip file on the EATS IN folder with extension of .chk
            copy($zip_file, EATS_IN_DIR . DS . $batch['Batch']['name'] . '.chk');

            // 6. put the xml file on the FC IN folder
            copy(FILES_DIR . $batch['Batch']['name'] . '_o' . DS . 'cheque.xml', FC_IN_DIR . DS . $batch['Batch']['name'] . '.xml');

            // clear all
            copy($zip_file, FILES_DIR . 'backups' . DS . $batch['Batch']['name'] . '.zip');
            unlink($zip_file);

            $dfiles = scandir(FILES_DIR . $batch['Batch']['name'] . '_o');
            foreach ($dfiles as $df) {
                if ($df == '.' || $df == '..')
                    continue;
                unlink(FILES_DIR . $batch['Batch']['name'] . '_o' . DS . $df);
            }
            rmdir(FILES_DIR . $batch['Batch']['name'] . '_o');

            // 7 Make corrections on the batch record
            $this->Batch->read(null, $batch['Batch']['id']);
            $this->Batch->set(array(
                'message_id' => $msgid,
                'authorized' => true,
                'sent' => true,
                'date_sent' => date('Y-m-d H:i:s'),
                'checker_id' => $this->Session->read('Auth.User.id'),
                'status' => 'S'
            ));
            $this->Batch->save();
        }
    }

    function send_incoming($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid batch', true));
            $this->render('/elements/failure');
        }
        $this->Batch->recursive = 2;
        $batch = $this->Batch->read(null, $id);

        // Generate the xml file
        $result = '';
        $total_amount = 0;
        // 1. bring a cheque template file
        $ct_file = FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming.xml';
        $ct_file2 = FILES_DIR . 'cheque_templates' . DS . 'cheque_incoming3.xml';
        $handle = fopen($ct_file, "r");
        $handle2 = fopen($ct_file2, "r");
        $cheque_template = fread($handle, filesize($ct_file));
        $cheque_template2 = fread($handle2, filesize($ct_file2));

        fclose($handle);
        $number_of_approved_cheques = 0;
        mkdir(FILES_DIR . $batch['Batch']['name'] . '_i');
        // 2. generate a cheque block for each cheque in the batch which is not rejected
        foreach ($batch['Cheque'] as $cheque) {
            if ($cheque['rejected'] == 1)
                continue;
            if ($cheque['remittance_information'] == '')
                $chk = str_replace('{ChkNmbr}', $cheque['cheque_number'], $cheque_template2);
            else
                $chk = str_replace('{ChkNmbr}', $cheque['cheque_number'], $cheque_template);

            $cheque_data = array('Cheque' => array(
                    'batch_id' => $this->Batch->id,
                    'rejected' => 0,
                    'transaction_code' => 'CH',
                    'transaction_id' => $cheque->PmtId->TxId->data,
                    'microcode' => 'NO_MICROCODE',
                    'branch_code' => $cheque->ChequeTx->BranchCode->data,
                    'creation_date' => date('Y-m-d H:i:s'),
                    'beneficiary_account' => $cheque->DbtrAcct->Id->IBAN->data,
                    'cheque_number' => $cheque->ChequeTx->ChkNmbr->data,
                    'cheque_index' => $i++,
                    'remittance_information' => (isset($cheque->RmtInf)) ? $cheque->RmtInf->Ustrd->data : '',
                    'bank_code' => $cheque->ChequeTx->BankCode->data,
                    'issuer_bank_bic' => $cheque->CdtrAgt->FinInstnId->BIC->data,
                    'amount' => $cheque->IntrBkSttlmAmt->data,
                    'payer_name' => $cheque->Cdtr->Nm->data,
                    'endorsement_number' => '',
                    'currency' => $cheque->IntrBkSttlmAmt->attr['Ccy'],
                    'beneficiary_name' => $cheque->Dbtr->Nm->data,
                    'payer_account' => $cheque->CdtrAcct->Id->IBAN->data
                    ));

            $chk = str_replace('{PmtId.InstrId}', $cheque['instrument_id'], $chk);
            $chk = str_replace('{PmtId.TxId}', $cheque['transaction_id'], $chk);
            $chk = str_replace('{PmtId.EndToEndId}', $cheque['end_to_end_id'], $chk);
            $chk = str_replace('{PmtTpInf.SvcLvl.Cd}', 'SEPA', $chk);
            $chk = str_replace('{PmtTpInf.LclInstrm.Cd}', 'CORE', $chk);
            $chk = str_replace('{Amount}', $cheque['amount'], $chk);
            $chk = str_replace('{AccNo}', $cheque['payer_account'], $chk);
            $chk = str_replace('{BankCode}', $cheque['bank_code'], $chk);
            $chk = str_replace('{BranchCode}', $cheque['branch_code'], $chk);
            $chk = str_replace('{CreditorName}', $cheque['beneficiary_name'], $chk);
            $chk = str_replace('{CreditorAcct}', $cheque['beneficiary_account'], $chk);
            $chk = str_replace('{DebitorName}', $cheque['payer_name'], $chk);
            $chk = str_replace('{DebitorAgentBIC}', $cheque['issuer_bank_bic'], $chk);
            $chk = str_replace('{DebitorAcct}', $cheque['payer_account'], $chk);
            $chk = str_replace('{Ccy}', $cheque['currency'], $chk);
            $chk = str_replace('{RmtInf}', $cheque['remittance_information'], $chk);

            //copy(FILES_DIR . 'incomings' . DS . $batch['Batch']['name'] . DS . $cheque['cheque_index'] . '.chequeFrontImage', FILES_DIR . $batch['Batch']['name'] . '_i' . DS . $cheque['transaction_id'] . '_front.tiff');
            //copy(FILES_DIR . 'incomings' . DS . $batch['Batch']['name'] . DS . $cheque['cheque_index'] . '.chequeRearImage', FILES_DIR . $batch['Batch']['name'] . '_i' . DS . $cheque['transaction_id'] . '_back.tiff');

            $result .= $chk . "\r\n";
            $number_of_approved_cheques++;
            $total_amount += $cheque['amount'];
        }
        // 3. generate the xml message from the xml template file
        $cont_file = FILES_DIR . 'cheque_templates' . DS . 'container_incoming.xml';
        $handle2 = fopen($cont_file, "r");
        $xml_template = fread($handle2, filesize($cont_file));
        fclose($handle2);

        // NOTE: the messageId is changed, so in this way
        //  FC should not reject the cheques for any reason
        //  because NBE will not recognize the original message id
        //  that FC uses!!!
        $msgid = $batch['Batch']['message_id'] . '_' . time();
        $xml_template = str_replace('{MsgId}', $batch['Batch']['message_id'], $xml_template);
        $xml_template = str_replace('{YYYY-MM-DD2}', date('Y-m-d', strtotime($batch['Batch']['created'] . ' +2 days')), $xml_template);
        $xml_template = str_replace('{YYYY-MM-DD}', date('Y-m-d', strtotime($batch['Batch']['created'])), $xml_template);
        $xml_template = str_replace('{HH:MM:SS}', date('H:i:s', strtotime($batch['Batch']['created'])), $xml_template);
        $xml_template = str_replace('{NumberOfTxs}', $number_of_approved_cheques, $xml_template);
        $xml_template = str_replace('{TotalAmount}', number_format($total_amount, 2, '.', ''), $xml_template);
        $xml_template = str_replace('{Cheques}', $result, $xml_template);
        $xml_template = str_replace('{Ccy}', $batch['Cheque'][0]['currency'], $xml_template);

        $xml_message = $xml_template;

        $handle_xml = fopen(FILES_DIR . $batch['Batch']['name'] . '_i' . DS . $batch['Batch']['message_id'] . '.xml', 'a');
        fwrite($handle_xml, $xml_message);
        fclose($handle_xml);

        // 4. collect and zip all the image files (renaming them to tiff::Already done in the foreach loop) with the xml file
        $handle_f = opendir(FILES_DIR . $batch['Batch']['name'] . '_i');
        $files_to_zip = array();
        /* This is the correct way to loop over the directory. */
        while (false !== ($file = readdir($handle_f))) {
            if ($file != '.' && $file != '..')
                $files_to_zip[$file] = FILES_DIR . $batch['Batch']['name'] . '_i' . DS . $file;
        }
        closedir($handle_f);

        $zip_file = FILES_DIR . $batch['Batch']['name'] . '_i' . '.zip';
        $result = $this->create_zip($files_to_zip, $zip_file);
        if (!$result) {
            echo "Cannot create the zip file";
        } else {
            // 6. put the xml file on the FC IN folder
            copy(FILES_DIR . $batch['Batch']['name'] . '_i' . DS . $batch['Batch']['message_id'] . '.xml', FC_IN_DIR . DS . $batch['Batch']['message_id'] . '.xml');

            // clear all
            copy($zip_file, FILES_DIR . 'backups' . DS . $batch['Batch']['name'] . '.zip');
            unlink($zip_file);

            $dfiles = scandir(FILES_DIR . $batch['Batch']['name'] . '_i');
            foreach ($dfiles as $df) {
                if ($df == '.' || $df == '..')
                    continue;
                unlink(FILES_DIR . $batch['Batch']['name'] . '_i' . DS . $df);
            }
            rmdir(FILES_DIR . $batch['Batch']['name'] . '_i');

            // 7 Make corrections on the batch record
            $this->Batch->read(null, $batch['Batch']['id']);
            $this->Batch->set(array(
                'message_id' => $msgid,
                'authorized' => true,
                'sent' => true,
                'date_sent' => date('Y-m-d H:i:s'),
                'checker_id' => $this->Session->read('Auth.User.id')
            ));
            $this->Batch->save();
        }
    }

    function save_status($filename = null) {
        $this->layout = 'message_layout';

        if (!file_exists(FILES_DIR . $filename)) {
            $this->set('msg', 'File ' . $filename . ' does not exist.');
            $this->log('File "' . $filename . '" does not exist. [function: batches::save_status()]');
            return;
        }

        // read the xml file
        $xml_file_name = FILES_DIR . $filename;

        App::import('BehXmlParser');

        BehXmlParser::setStartingValues();

        $xml_parser = xml_parser_create();
        xml_set_element_handler($xml_parser, "BehXmlParser::startElement", "BehXmlParser::endElement");
        xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_character_data_handler($xml_parser, "BehXmlParser::characterData");
        if (!($fp = fopen($xml_file_name, "r"))) {
            $this->set('msg', 'could not open XML input');
            return;
        }

        while ($data = fread($fp, 4096)) {
            $data = str_replace('&amp;', 'AND', $data);
            if (!xml_parse($xml_parser, $data, feof($fp))) {
                $this->set('msg', sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
                return;
            }
        }
        xml_parser_free($xml_parser);

        $obj = new stdClass();
        $obj->xml = BehXmlParser::getXmlObject();

        if (isset($obj->xml->Document->FIToFIPmtStsRpt->OrgnlGrpInfAndSts->OrgnlMsgId)) {
            $message_id = $obj->xml->Document->FIToFIPmtStsRpt->OrgnlGrpInfAndSts->OrgnlMsgId->data;
            $status = $obj->xml->Document->FIToFIPmtStsRpt->OrgnlGrpInfAndSts->GrpSts->data;

            $batch = $this->Batch->find('first', array('conditions' => array('Batch.message_id' => $message_id)));

            $batch['Batch']['status'] = ($status == 'ACCP') ? 'A' : 'R';
            if ($this->Batch->save($batch)) {
                $this->set('msg', 'OK');
                @unlink(FILES_DIR . $filename);
                return;
            } else {
                $this->set('msg', 'Cannot save the Batch with message id: ' . $message_id);
                return;
            }
        } else {
            $this->set('msg', 'XML Document is not a confirmation message.');
            return;
        }
    }

    function create_zip($files = array(), $destination = '', $overwrite = false) {
        //if the zip file already exists and overwrite is false, return false
        if (file_exists($destination) && !$overwrite) {
            return false;
        }
        //vars
        $valid_files = array();
        //if files were passed in...
        if (is_array($files)) {
            //cycle through each file
            foreach ($files as $key => $file) {
                //make sure the file exists
                if (file_exists($file)) {
                    $valid_files[$key] = $file;
                }
            }
        }
        //if we have good files...
        if (count($valid_files)) {
            //create the archive
            $zip = new ZipArchive();
            if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
                return false;
            }
            //add the files
            foreach ($valid_files as $key => $file) {
                $zip->addFile($file, $key);
            }
            //debug
            //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
            //close the zip -- done!
            $zip->close();

            //check to make sure the file exists
            return file_exists($destination);
        } else {
            return false;
        }
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for batch', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Batch->delete($i);
                }
                $this->Session->setFlash(__('Batch deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Batch was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Batch->delete($id)) {
                $this->Session->setFlash(__('Batch deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Batch was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function delete2($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for batch', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Batch->delete($i);
                }
                $this->Session->setFlash(__('Batch deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Batch was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Batch->delete($id)) {
                $this->Session->setFlash(__('Batch deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Batch was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

}

?>