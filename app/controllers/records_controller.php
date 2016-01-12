<?php
class RecordsController extends AppController {

	var $name = 'Records';
	
	function index() {
		$issues = $this->Record->Issue->find('all');
		$this->set(compact('issues'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$issue_id = (isset($_REQUEST['issue_id'])) ? $_REQUEST['issue_id'] : -1;
		if($id)
			$issue_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($issue_id != -1) {
            $conditions['Record.issue_id'] = $issue_id;
        }
		
		$this->set('records', $this->Record->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Record->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid record', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Record->recursive = 2;
		$this->set('record', $this->Record->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Record->create();
			$this->autoRender = false;
			if ($this->Record->save($this->data)) {
				$this->Session->setFlash(__('The record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$issues = $this->Record->Issue->find('list');
		$fields = $this->Record->Field->find('list');
		$this->set(compact('issues', 'fields'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid record', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Record->save($this->data)) {
				$this->Session->setFlash(__('The record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('record', $this->Record->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$issues = $this->Record->Issue->find('list');
		$fields = $this->Record->Field->find('list');
		$this->set(compact('issues', 'fields'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for record', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Record->delete($i);
                }
				$this->Session->setFlash(__('Record deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Record was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Record->delete($id)) {
				$this->Session->setFlash(__('Record deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Record was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>