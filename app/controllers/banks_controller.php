<?php
class BanksController extends AppController {

	var $name = 'Banks';
	
    function index() {
    }
	

    function search() {
    }
	
    function list_data($id = null) {
        $start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
        $limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
        $this->set('banks', $this->Bank->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
        $this->set('results', $this->Bank->find('count', array('conditions' => $conditions)));
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash(__('Invalid bank', true));
            $this->redirect(array('action' => 'index'));
        }
        $this->Bank->recursive = 2;
        $this->set('bank', $this->Bank->read(null, $id));
    }

    function add($id = null) {
        if (!empty($this->data)) {
            $this->Bank->create();
            $this->autoRender = false;
            if ($this->Bank->save($this->data)) {
                $this->Session->setFlash(__('The bank has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The bank could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
    }

    function edit($id = null, $parent_id = null) {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid bank', true), '');
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->data)) {
            $this->autoRender = false;
            if ($this->Bank->save($this->data)) {
                $this->Session->setFlash(__('The bank has been saved', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('The bank could not be saved. Please, try again.', true), '');
                $this->render('/elements/failure');
            }
        }
        $this->set('bank', $this->Bank->read(null, $id));


    }

    function delete($id = null) {
        $this->autoRender = false;
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for bank', true), '');
            $this->render('/elements/failure');
        }
        if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Bank->delete($i);
                }
                $this->Session->setFlash(__('Bank deleted', true), '');
                $this->render('/elements/success');
            }
            catch (Exception $e){
                $this->Session->setFlash(__('Bank was not deleted', true), '');
                $this->render('/elements/failure');
            }
        } else {
            if ($this->Bank->delete($id)) {
                $this->Session->setFlash(__('Bank deleted', true), '');
                $this->render('/elements/success');
            } else {
                $this->Session->setFlash(__('Bank was not deleted', true), '');
                $this->render('/elements/failure');
            }
        }
    }
}
?>