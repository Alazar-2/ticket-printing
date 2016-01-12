<?php

class KeysController extends AppController {

    var $name = 'Keys';

    function index() {
        $from_branches = $this->Key->FromBranch->find('all');
        $this->set(compact('from_branches'));
    }

    function index3() {
        $from_branches = $this->Key->FromBranch->find('all');
        $this->set(compact('from_branches'));
    }

    function index2($id = null) {
        $this->set('parent_id', $id);
    }

    function search() {
        
    }

    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $frombranch_id = (isset($_REQUEST['frombranch_id'])) ? $_REQUEST['frombranch_id'] : -1;
        if ($id)
            $frombranch_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");

        if ($id == 'from') {
            $conditions['Key.from_branch_id'] = $this->Session->read('Auth.User.branch_id');
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            $this->set('keys', $from_branches);
        } elseif ($id == 'to') {
            $conditions['Key.to_branch_id'] = $this->Session->read('Auth.User.branch_id');
            $to_branches = $this->Key->find('all', array('conditions' => $conditions));
            $this->set('keys', $to_branches);
        } else {
            $this->set('keys', $this->Key->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        }
        $this->set('results', $this->Key->find('count', array('conditions' => $conditions)));
    }

    function list_data2($id = null, $type = 'outgoing') {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 20;
        if ($id) {
            if ($type == 'outgoing') {
                $frombranch_id = ($id) ? $id : -1;
                $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

                eval("\$conditions = array( " . $conditions . " );");
                if ($frombranch_id != -1) {
                    $conditions['Key.from_branch_id'] = $frombranch_id;
                }
            }
            if ($type == 'incoming') {
                $tobranch_id = ($id) ? $id : -1;
                $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

                eval("\$conditions = array( " . $conditions . " );");
                if ($tobranch_id != -1) {
                    $conditions['Key.to_branch_id'] = $tobranch_id;
                }
            }
        }
        $this->set('keys', $this->Key->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Key->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid key', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Key->recursive = 2;
        $this->set('key', $this->Key->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Key->create();
            $this->autoRender = false;
            if ($this->Key->save($this->data)) {
                $this->Session->setFlash(__('The key has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The key could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        if ($id)
            $this->set('parent_id', $id);
        $from_branches = $this->Key->FromBranch->find('list');
        $to_branches = $this->Key->ToBranch->find('list');
        $this->set(compact('from_branches', 'to_branches'));
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid key', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Key->save($this->data)) {
                $this->Session->setFlash(__('The key has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The key could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('key', $this->Key->read(null, $id));

        if ($parent_id) {
            $this->set('parent_id', $parent_id);
        }

        $from_branches = $this->Key->FromBranch->find('list');
        $to_branches = $this->Key->ToBranch->find('list');
        $this->set(compact('from_branches', 'to_branches'));
    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for key', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try {
                foreach ($ids as $i) {
                    $this->Key->delete($i);
                }
                $this->Session->setFlash(__('Key deleted', true), '');
                $this->render('/elements/success');
            } catch (Exception $e) {
                $this->Session->setFlash(__('Key was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Key->delete($id)) {
                $this->Session->setFlash(__('Key deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Key was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function generate_keys() {
        /*
INSERT INTO `publickey`.`random` (
`id` ,
`random`
)
VALUES (
NULL , FLOOR(2000 + (RAND() * 7000))
);

try {
  $this->Model->query('INSERT INTO model WHERE id=invalid');
} catch (exception $ex) {
  // exception never happens
}
         * 
         */
        //$this->Key->query("TRUNCATE TABLE keys");
        $this->Key->deleteAll('1 = 1', false);
        $this->autoRender = false;
        $this->loadModel('Branch');
        $this->Branch->recursive = -1;
        $outgoings = $this->Branch->find('all');
        $donebranches = array();
        foreach ($outgoings as $outgoing) {
            $donebranches = array_merge((array) $outgoing['Branch']['name'], (array) $donebranches);
            $incomings = $this->Branch->find('all');
            foreach ($incomings as $incoming) {
                if ($outgoing['Branch']['name'] != $incoming['Branch']['name']) {

                    $listorder = $outgoing['Branch']['list_order'] * $incoming['Branch']['list_order'];

                    $maxfrom = 0;
                    for ($i = 0; $i < strlen($outgoing['Branch']['name']); $i++) {
                        if ($outgoing['Branch']['name']{$i} != ' ')
                            $x = ord(strtoupper($outgoing['Branch']['name']{$i}));
                        if ($x > $maxfrom)
                            $maxfrom = $x;
                    }
                    $maxto = 0;
                    for ($i = 0; $i < strlen($incoming['Branch']['name']); $i++) {
                        if ($incoming['Branch']['name']{$i} !== ' ')
                            $x = ord(strtoupper($incoming['Branch']['name']{$i}));
                        if ($x > $maxto)
                            $maxto = $x;
                    }

                    if (array_search($incoming['Branch']['name'], $donebranches)) {
                        //<=5000                    
                        //echo 'Less in key: ' . $outgoing['Branch']['name'] . '<->' . $incoming['Branch']['name'] . ' ' . $listorder * ($maxfrom - 34) * ($maxto - 34) . ' ';
                        $key = $listorder * ($maxfrom - 58) * ($maxto - 58);
                        $storebelow = array('from_branch_id' => $outgoing['Branch']['id'], 'to_branch_id' => $incoming['Branch']['id'], 'key' => $key, 'tt_direction' => 'incoming', 'amount_range' => 'Less');
                        $this->Key->create();
                        $this->Key->save($storebelow);
                        //>5000
                        //echo 'More in key: ' . $outgoing['Branch']['name'] . '<->' . $incoming['Branch']['name'] . ' ' . $listorder * ($maxfrom - 31) * ($maxto - 31) . ' ';
                        $key = $listorder * ($maxfrom - 56) * ($maxto - 56);
                        $storeabove = array('from_branch_id' => $outgoing['Branch']['id'], 'to_branch_id' => $incoming['Branch']['id'], 'key' => $key, 'tt_direction' => 'incoming', 'amount_range' => 'Above');
                        $this->Key->create();
                        $this->Key->save($storeabove);
                    } else {
                        //<=5000                    
                        //echo 'Less out key: ' . $outgoing['Branch']['name'] . '<->' . $incoming['Branch']['name'] . ' ' . $listorder * ($maxfrom - 64) * ($maxto - 64) . ' ';
                        $key = $listorder * ($maxfrom - 64) * ($maxto - 64);
                        $storebelow = array('from_branch_id' => $outgoing['Branch']['id'], 'to_branch_id' => $incoming['Branch']['id'], 'key' => $key, 'tt_direction' => 'outgoing', 'amount_range' => 'Less');
                        $this->Key->create();
                        $this->Key->save($storebelow);
                        //>5000
                        //echo 'More Out key: ' . $outgoing['Branch']['name'] . '<->' . $incoming['Branch']['name'] . ' ' . $listorder * ($maxfrom - 61) * ($maxto - 61) . ' ';
                        $key = $listorder * ($maxfrom - 61) * ($maxto - 61);
                        $storeabove = array('from_branch_id' => $outgoing['Branch']['id'], 'to_branch_id' => $incoming['Branch']['id'], 'key' => $key, 'tt_direction' => 'outgoing', 'amount_range' => 'Above');
                        $this->Key->create();
                        $this->Key->save($storeabove);
                    }
                }
            }
        }
        // $this->Session->setFlash(__('Invalid id for key', true), '');
    }

    function printxl($id = null) {
        // echo $id;
         header('Content-type: application/vnd.ms-excel');
         echo 'From Branch,To Branch,Key,Amount Range'."\n\n";
        if ($id == 'from') {
            header('Content-Disposition: attachment; filename="Sending.csv"');
            $conditions['Key.from_branch_id'] = $this->Session->read('Auth.User.branch_id');
            $conditions['Key.amount_range'] = 'Less than or = 5000';
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            foreach ($from_branches as $from_branche) {
                echo $from_branche['FromBranch']['name'] . ',' . $from_branche['ToBranch']['name'] . ',' . $from_branche['Key']['key'] . ',Less than 5000' . "\n";
            }
            echo "\n";
            $conditions['Key.amount_range'] = 'Above 5000';
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            foreach ($from_branches as $from_branche) {
                echo $from_branche['FromBranch']['name'] . ',' . $from_branche['ToBranch']['name'] . ',' . $from_branche['Key']['key'] . ',Above 5000' . "\n";
            }
            //$this->set('keys', $from_branches);
        } elseif ($id == 'to') {
            header('Content-Disposition: attachment; filename="Receiving.csv"');
            $conditions['Key.to_branch_id'] = $this->Session->read('Auth.User.branch_id');
            $conditions['Key.amount_range'] = 'Less than or = 5000';
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            foreach ($from_branches as $from_branche) {
                echo $from_branche['FromBranch']['name'] . ',' . $from_branche['ToBranch']['name'] . ',' . $from_branche['Key']['key'] . ',Less than 5000' . "\n";
            }
            echo "\n";
            $conditions['Key.amount_range'] = 'Above 5000';
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            foreach ($from_branches as $from_branche) {
                echo $from_branche['FromBranch']['name'] . ',' . $from_branche['ToBranch']['name'] . ',' . $from_branche['Key']['key'] . ',Above 5000' . "\n";
            }
            // $this->set('keys', $to_branches);
        } else {
            header('Content-Disposition: attachment; filename="All_keys.csv"');
            $conditions['Key.amount_range'] = 'Less than or = 5000';
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            foreach ($from_branches as $from_branche) {
                echo $from_branche['FromBranch']['name'] . ',' . $from_branche['ToBranch']['name'] . ',' . $from_branche['Key']['key'] . ',Less than 5000' . "\n";
            }
            echo "\n";
            $conditions['Key.amount_range'] = 'Above 5000';
            $from_branches = $this->Key->find('all', array('conditions' => $conditions));
            foreach ($from_branches as $from_branche) {
                echo $from_branche['FromBranch']['name'] . ',' . $from_branche['ToBranch']['name'] . ',' . $from_branche['Key']['key'] . ',Above 5000' . "\n";
            }
        }
        exit();
    }

}

?>