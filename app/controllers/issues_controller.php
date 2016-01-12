<?php

class IssuesController extends AppController {

    var $name = 'Issues';

    function index() {
        $tickets = $this->Issue->Ticket->find('all');
        $this->set(compact('tickets'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function index3() {
        $tickets = $this->Issue->Ticket->find('all');
        $this->set(compact('tickets'));
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $ticket_id = (isset($_REQUEST['ticket_id'])) ? $_REQUEST['ticket_id'] : -1;
        if ($id)
            $ticket_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
        if ($ticket_id != -1) {
            $conditions['Issue.ticket_id'] = $ticket_id;
        }

        if ($this->Session->read('Auth.User.id') != 1) {  //if not admin
            $conditionsu['User.is_active'] = '1';
            $conditionsu['User.branch_id'] = $this->Session->read('Auth.User.branch_id');
            $this->loadModel('User');
            $user_ids = $this->User->find('list', array('conditions' => $conditionsu, 'fields' => array('User.id')));
            $conditions = array_merge(array("OR" => array("Issue.user_id" => $user_ids)), $conditions);
        }
        $this->set('issues', $this->Issue->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start, 'order' => 'Issue.created DESC')));
        $this->set('results', $this->Issue->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid issue', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Issue->recursive = 2;
        $xx = $this->Issue->read(null, $id);
        print_r($xx);
        $this->set('issue', $this->Issue->read(null, $id));
    }

    function view2($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid issue', true));
            $this->redirect(array('action' => 'index'));
        }
        //$this->autoRender = false;
        $this->loadModel('Record');
        $conditions['Record.issue_id'] = $id;
        $xx = $this->Record->find('all', array('conditions' => $conditions));
        $this->set('issue', $xx);
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Issue->create();
            $this->autoRender = false;
            if ($this->Issue->save($this->data)) {
                $this->Session->setFlash(__('The issue has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The issue could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $tickets = $this->Issue->Ticket->find('list');
        $users = $this->Issue->User->find('list');
        $this->set(compact('tickets', 'users'));
    }

    function convert_number_to_words($number) {

        $hyphen = ' ';
        $conjunction = ' ';
        $separator = ' ';
        $negative = 'negative ';
        $decimal = ' and ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'fourty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . $this->convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            //$string .= implode(' ', $words);
            $string .= $fraction . '/100';
        }

        return $string;
    }

	function strip_word_html($text, $allowed_tags = '') 
    { 
        mb_regex_encoding('UTF-8'); 
        //replace MS special characters first 
        $search = array('/&lsquo;/u', '/&rsquo;/u', '/&ldquo;/u', '/&rdquo;/u', '/&mdash;/u'); 
        $replace = array('\'', '\'', '"', '"', '-'); 
        $text = preg_replace($search, $replace, $text); 
        //make sure _all_ html entities are converted to the plain ascii equivalents - it appears 
        //in some MS headers, some html entities are encoded and some aren't 
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8'); 
        //try to strip out any C style comments first, since these, embedded in html comments, seem to 
        //prevent strip_tags from removing html comments (MS Word introduced combination) 
        if(mb_stripos($text, '/*') !== FALSE){ 
            $text = mb_eregi_replace('#/\*.*?\*/#s', '', $text, 'm'); 
        } 
        //introduce a space into any arithmetic expressions that could be caught by strip_tags so that they won't be 
        //'<1' becomes '< 1'(note: somewhat application specific) 
        $text = preg_replace(array('/<([0-9]+)/'), array('< $1'), $text); 
        $text = strip_tags($text, $allowed_tags); 
        //eliminate extraneous whitespace from start and end of line, or anywhere there are two or more spaces, convert it to one 
        $text = preg_replace(array('/^\s\s+/', '/\s\s+$/', '/\s\s+/u'), array('', '', ' '), $text); 
        //strip out inline css and simplify style tags 
        $search = array('#<(strong|b)[^>]*>(.*?)</(strong|b)>#isu', '#<(em|i)[^>]*>(.*?)</(em|i)>#isu', '#<u[^>]*>(.*?)</u>#isu'); 
        $replace = array('<b>$2</b>', '<i>$2</i>', '<u>$1</u>'); 
        $text = preg_replace($search, $replace, $text); 
        //on some of the ?newer MS Word exports, where you get conditionals of the form 'if gte mso 9', etc., it appears 
        //that whatever is in one of the html comments prevents strip_tags from eradicating the html comment that contains 
        //some MS Style Definitions - this last bit gets rid of any leftover comments */ 
        $num_matches = preg_match_all("/\<!--/u", $text, $matches); 
        if($num_matches){ 
              $text = preg_replace('/\<!--(.)*--\>/isu', '', $text); 
        } 
        return $text; 
    } 
    function issue($id = null) {

        if (!empty($this->data)) {

            $this->loadModel('Setting');
            $conditionss['Setting.setting_key'] = 'currency';
            $xxs = $this->Setting->find('first', array('conditions' => $conditionss));
            if (is_array($xxs))
                $currency = $xxs['Setting']['setting_value'];
            else
                $currency = 'birr';

            $this->Issue->create();
            $this->autoRender = false;
            $this->data['Issue']['ticket_id'] = $this->data['Ticket']['id'];
            $this->data['Issue']['user_id'] = $this->Session->read('Auth.User.id');
            if ($this->Issue->save($this->data)) {
                $issue_id = $this->Issue->getLastInsertId();
                $this->loadModel('Record');
                foreach ($this->data['Field'] as $key => $field) {

                    $xpl = explode('_', $field);
                    if (count($xpl) > 0 && $xpl[0] == 'amountinwords') {
                        $field = $this->convert_number_to_words(str_replace(',', '', $this->data['Field'][$xpl[1]])) . ' ' . $currency;
                        $field = preg_replace('/([.!?])\s*(\w)/e', "strtoupper('\\1 \\2')", ucfirst(strtolower($field)));
                    }
                    if (count($xpl) > 0 && $xpl[0] == 'warehouse') {
                        $this->loadModel('Warehouse');
                        $conditions['Warehouse.name'] = $xpl[1];
                        $xx = $this->Warehouse->find('first', array('conditions' => $conditions));
                        if ($xx['Warehouse']['end'] > $xx['Warehouse']['current']) {
                            $warehouse = $xx['Warehouse']['string'] . str_pad(($xx['Warehouse']['current'] + 1), $xx['Warehouse']['format'], '0', STR_PAD_LEFT);
                            $curr = $xx['Warehouse']['current'];
                            $wid = $xx['Warehouse']['id'];
                        } else {
                            $this->Session->setFlash(__('The ticket could not be saved. Please, contact IT Team.', true), '');
                            $this->render('/elements/failure');
                            $this->Issue->delete($issue_id);
                            return 0;
                        }

                        $field = $warehouse;
                        $this->data['Field'][$key]=$field;

                        $this->data3['Warehouse']['id'] = $wid;
                        $this->data3['Warehouse']['current'] = $curr + 1;
                        $this->Warehouse->save($this->data3);
                    }
                    
                    if (count($xpl) > 0 && $xpl[0] == 'copy') {
                        $field = $this->data['Field'][$xpl[1]];
                        $this->data['Field'][$key]=$field;
                    }
                    $this->Record->create();
                    $this->data2['Record']['issue_id'] = $this->Issue->getLastInsertId();
                    $this->data2['Record']['field_id'] = $key;
                    $this->data2['Record']['value'] = $field;
                    $this->Record->save($this->data2);
                }
                $this->Session->setFlash(__('The ticket has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The ticket could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if (!$id) {
                $this->Session->setFlash(__('Invalid ticket', true));
                $this->redirect(array('action' => 'index'));
            }
            $this->loadModel('Ticket');
			$this->Ticket->recursive = 0;
			$ticket=$this->Ticket->read(null, $id);
			$this->Ticket->Field->recursive=-1;
			$fields=$this->Ticket->Field->find('all',array('conditions' =>array('Field.ticket_id'=>$id)));
			foreach($fields as $field){
			$ticket['Field'][]=$field['Field'];
			}
            $this->set('ticket', $ticket);
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid issue', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Issue->save($this->data)) {
                $this->Session->setFlash(__('The issue has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The issue could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('issue', $this->Issue->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $tickets = $this->Issue->Ticket->find('list');
        $users = $this->Issue->User->find('list');
        $this->set(compact('tickets', 'users'));
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    function image() {
        $image_url = $_GET['image'];
        $issue_id = $_GET['issue'];
        $this->autoRender = false;
        $ext = substr($image_url, strripos($image_url, '.') + 1);
        /*$font = FILES_DIR . "rough_typewriter" . DS . "rough_typewriter.otf";
        $dimensions = imagettfbbox(14, 0, $font, "Q");
		*/
		$font = FILES_DIR . "times" . DS . "times.ttf";
		$fontsize = 13;
        $dimensions = imagettfbbox($fontsize, 0, $font, "u");
        $fontwidth = $dimensions[2];
        $source = null;
        if (in_array($ext, array('jpg', 'jpeg')))
            $source = imagecreatefromjpeg(IMAGES . 'slip_images' . DS . $image_url);
        elseif (in_array($ext, array('png')))
            $source = imagecreatefrompng(IMAGES . 'slip_images' . DS . $image_url);
        else
            $source = imagecreatefromgif(IMAGES . 'slip_images' . DS . $image_url);
        $textcolor = imagecolorallocate($source, 0, 0, 0);
        $conditions['Record.issue_id'] = $issue_id;
        $this->loadModel('Record');
        $records = $this->Record->find("all", array('conditions' => $conditions));

        foreach ($records as $record) {
            $ticket_id = $record['Issue']['ticket_id'];
            $field_id = $record['Record']['field_id'];
            $conditionsx['Coordinate.ticket_id'] = $ticket_id;
            $conditionsx['Coordinate.field_id'] = $field_id;
            $this->Issue->Ticket->Coordinate->recursive = -1;
            $coordinates = $this->Issue->Ticket->Coordinate->find("all", array('conditions' => $conditionsx, 'order' => 'order'));
            $lines = count($coordinates);
            $text = $record['Record']['value'];
			
            $textarr = null;
            for ($i = 1; $i <= $lines; $i++) {
                $x = $coordinates[$i - 1]['Coordinate']['x'];
                $y = $coordinates[$i - 1]['Coordinate']['y'];

                $num_char = ($coordinates[$i - 1]['Coordinate']['length'] - $coordinates[$i - 1]['Coordinate']['x']) / $fontwidth;
                if ($coordinates[$i - 1]['Coordinate']['alignment'] == 'right') {
                    $bbox = imagettfbbox($fontsize, 0, $font, $text);
                    $x = $coordinates[$i - 1]['Coordinate']['length'] - $bbox[2];
                }
				//$text=str_replace('\n','<p>',$text);
				//$text = trim(preg_replace('/\s\s+/', 'newline', $text));
				$text = str_replace(PHP_EOL,'newline',$text);
				$text = preg_replace('/^\s+|\n|\r|\s+$/m', 'newline', $text);
                //if (strlen($text) > $num_char) {
					$parpos=strpos($text,'newline');
					if($parpos!==FALSE){
					$space='';
					for($vv=$parpos; $vv<=$num_char; $vv++)
						$space=' '.$space;//&nbsp;
					if($space!='')
					$text=preg_replace('/newline/', $space, $text, 1);
					}
                    $text = wordwrap($text, $num_char, "-)(-");
                    $textarr = explode("-)(-", $text);
                    $text = $textarr[0];
					$text = preg_replace( "/\s+/", " ", $text );
                    imagettftext($source, $fontsize, 0, $x, $y, $textcolor, $font, $text);
					//echo $text.'<br>New Line<br>';
                    if (count($textarr) > 1) {
                        unset($textarr[0]);
                        $text = implode(' ', $textarr);
                    //}
                } else {
                    imagettftext($source, $fontsize, 0, $x, $y, $textcolor, $font, $text);
                    break;
                }
            }
        }
        if (isset($_GET['x'])) {
            $cropped = imagecreatetruecolor($_GET['width'], $_GET['height']);
            imagecopy($cropped, $source, 0, 0, $_GET['x'], $_GET['y'], $_GET['width'], $_GET['height']);
            header('Content-Type: image/png');
            imagepng($cropped);
        } else {
            header('Content-Type: image/png');
            imagepng($source);
        }
    }

    function printx($id = null, $parent_id = null) {

        $print_it = 'false';
        if (!empty($this->data)) {

            $this->loadModel('Setting');
            $conditionss['Setting.setting_key'] = 'currency';
            $xxs = $this->Setting->find('first', array('conditions' => $conditionss));
            if (is_array($xxs))
                $currency = $xxs['Setting']['setting_value'];
            else
                $currency = 'birr';

            $this->Issue->create();
            $this->autoRender = false;
            $this->data['Issue']['ticket_id'] = $this->data['Ticket']['id'];
            $this->data['Issue']['user_id'] = $this->Session->read('Auth.User.id');
            if ($this->Issue->save($this->data)) {
                $issue_id = $this->Issue->getLastInsertId();
                $this->loadModel('Record');

                foreach ($this->data['Field'] as $key => $field) {
                    $xpl = explode('_', $field);
                    if (count($xpl) > 0 && $xpl[0] == 'amountinwords') {
                        $field = $this->convert_number_to_words(str_replace(',', '', $this->data['Field'][$xpl[1]])) . ' ' . $currency;
                        $field = preg_replace('/([.!?])\s*(\w)/e', "strtoupper('\\1 \\2')", ucfirst(strtolower($field)));
                    }
                    if (count($xpl) > 0 && $xpl[0] == 'warehouse') {
                        $this->loadModel('Warehouse');
                        $conditions['Warehouse.name'] = $xpl[1];
                        $xx = $this->Warehouse->find('first', array('conditions' => $conditions));
                        if ($xx['Warehouse']['end'] > $xx['Warehouse']['current']) {
                            $warehouse = $xx['Warehouse']['string'] . str_pad(($xx['Warehouse']['current'] + 1), $xx['Warehouse']['format'], '0', STR_PAD_LEFT);
                            $curr = $xx['Warehouse']['current'];
                            $wid = $xx['Warehouse']['id'];
                        } else {
                            //$this->Session->setFlash(__('The ticket could not be saved. Please, contact IT Team.', true), '');
                            //$this->render('/elements/failure');
                            $this->Issue->delete($issue_id);
                            echo 'The ticket could not be saved. Please, contact IT Team.';
                            return 0;
                        }

                        $field = $warehouse;
                        $this->data['Field'][$key]=$field;

                        $this->data3['Warehouse']['id'] = $wid;
                        $this->data3['Warehouse']['current'] = $curr + 1;
                        $this->Warehouse->save($this->data3);
                    }
                   if (count($xpl) > 0 && $xpl[0] == 'copy') {
                        $field = $this->data['Field'][$xpl[1]];
                        $this->data['Field'][$key]=$field;
                    }
                    $this->Record->create();
                    $this->data2['Record']['issue_id'] = $this->Issue->getLastInsertId();
                    $this->data2['Record']['field_id'] = $key;
                    $this->data2['Record']['value'] = $field;
                    $this->Record->save($this->data2);
                }
                //$this->Session->setFlash(__('The ticket has been saved', true), '');
                //$this->render('/elements/success');
                $print_it = 'true';
            } else {
                $print_it = 'The ticket could not be saved. Please, try again.';
                //$this->Session->setFlash(__('The ticket could not be saved. Please, try again.', true), '');
                //$this->render('/elements/failure');
            }
        }
        if ($print_it == 'true') {
            $this->loadModel('Ticket');
            $ticket = $this->Ticket->read(null, $this->data['Ticket']['id']);
            $slips = $ticket['Slip'];
            $this->array_sort_by_column($slips, "order");
            $output = '';
            $i = 1;
            $j = 2;
            foreach ($slips as $key => $slip) {
                if ($i == 1)
                    $output = $output . '<page>';
                $size = getimagesize(IMAGES . 'slip_images' . DS . $slip['url']);

                $max = 1000;
                $diff = 1000;
                if ($size[1] > $max) {
                    for ($top = 0; $top < ($size[1] - $max); $top = $max + $top) {
                        if ($top > ($size[1] - $max))
                            $diff = $size[1] - $top;
                        $output = $output . '<img  src="http://localhost/tck_pr_sys/issues/image?image=' . $slip['url'] . '&issue=' . $issue_id . '&x=0&width=700&height=' . $diff . '&y=' . $top . '"/><br>';
                    }
                }else
                    $output = $output . '<img src="http://localhost/tck_pr_sys/issues/image?image=' . $slip['url'] . '&issue=' . $issue_id . '"/><br>';
                if ($i == 2) {
                    $i = 1;
                    $j = 2;
                    $output = $output . '</page>';
                } else {
                    if ($key < (count($slips) - 1))
                        $output = $output . '<img src="' . IMAGES . 'cut.jpg" /><br>';
                    $i++;
                    $j = 1;
                }
            }
            if ($j == 1)
                $output = $output . '</page>';
            require_once(APPLIBS . DS . 'html2pdf' . DS . 'html2pdf.class.php');
            $h2p = new HTML2PDF('P', 'A4', 'en');
            $h2p->writeHTML($output);
            $file = "issue.pdf";
            $h2p->Output($file);
        }else
            echo $print_it;
    }

    function printv($id = null) {
        $issue = $this->Issue->read(null, $id);
        $issue_id = $issue['Issue']['id'];
        $ticket_id = $issue['Ticket']['id'];
        $this->autoRender = false;
        $this->loadModel('Ticket');
        $ticket = $this->Ticket->read(null, $ticket_id);
        $slips = $ticket['Slip'];
        $this->array_sort_by_column($slips, "order");
        $output = '';
        $i = 1;
        $j = 2;
        foreach ($slips as $key => $slip) {
            if ($i == 1)
                $output = $output . '<page>';
            $size = getimagesize(IMAGES . 'slip_images' . DS . $slip['url']);

            $max = 1000;
            $diff = 1000;
            if ($size[1] > $max) {
                for ($top = 0; $top < ($size[1] - $max); $top = $max + $top) {
                    if ($top > ($size[1] - $max))
                        $diff = $size[1] - $top;
                    $output = $output . '<img  src="http://localhost/tck_pr_sys/issues/image?image=' . $slip['url'] . '&issue=' . $issue_id . '&x=0&width=700&height=' . $diff . '&y=' . $top . '"/><br>';
                }
            }else
                $output = $output . '<img src="http://localhost/tck_pr_sys/issues/image?image=' . $slip['url'] . '&issue=' . $issue_id . '"/><br>';
            if ($i == 2) {
                $i = 1;
                $j = 2;
                $output = $output . '</page>';
            } else {
                if ($key < (count($slips) - 1))
                    $output = $output . '<img src="' . IMAGES . 'cut.jpg" /><br>';
                $i++;
                $j = 1;
            }
        }
        if ($j == 1)
            $output = $output . '</page>';

        require_once(APPLIBS . DS . 'html2pdf' . DS . 'html2pdf.class.php');
        $h2p = new HTML2PDF('P', array(200, 300), 'en', true, 'UTF-8', array(0, 0, 0, 0));
        $h2p = new HTML2PDF('P', 'A4', 'en');
        $h2p->pdf->SetDisplayMode('fullpage');
        $h2p->writeHTML($output);
        $file = "issue.pdf";
        $h2p->Output($file);
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for issue', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Issue->delete($i);
                }
                $this->Session->setFlash(__('Issue deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Issue was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Issue->delete($id)) {
                $this->Session->setFlash(__('Issue deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Issue was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
	function report()
	{
		if (!empty($this->data)) {
		   $this->autoRender = false;
		print_r($this->data);
		}
		
		
		$tickets = $this->Issue->Ticket->find('list');
        $this->set(compact('tickets'));
	}

}

?>