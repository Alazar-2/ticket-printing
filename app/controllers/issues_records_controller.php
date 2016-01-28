<?php
class IssuesRecordsController extends AppController {

	var $name = 'IssuesRecords';
	
	function index() {
		$tickets = $this->IssuesRecord->Ticket->find('all');
		$this->set(compact('tickets'));
	}
	
	function index2($id = null) {
		$this->set('parent_id', $id);
	}

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
		$ticket_id = (isset($_REQUEST['ticket_id'])) ? $_REQUEST['ticket_id'] : -1;
		if($id)
			$ticket_id = ($id) ? $id : -1;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		if ($ticket_id != -1) {
            $conditions['IssuesRecord.ticket_id'] = $ticket_id;
        }
		
		$this->set('issuesRecords', $this->IssuesRecord->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->IssuesRecord->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid issues record', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->IssuesRecord->recursive = 2;
		$this->set('issuesRecord', $this->IssuesRecord->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->IssuesRecord->create();
			$this->autoRender = false;
			if ($this->IssuesRecord->save($this->data)) {
				$this->Session->setFlash(__('The issues record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The issues record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$tickets = $this->IssuesRecord->Ticket->find('list');
		$fields = $this->IssuesRecord->Field->find('list');
		$this->set(compact('tickets', 'fields'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid issues record', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->IssuesRecord->save($this->data)) {
				$this->Session->setFlash(__('The issues record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The issues record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('issues__record', $this->IssuesRecord->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$tickets = $this->IssuesRecord->Ticket->find('list');
		$fields = $this->IssuesRecord->Field->find('list');
		$this->set(compact('tickets', 'fields'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for issues record', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->IssuesRecord->delete($i);
                }
				$this->Session->setFlash(__('Issues record deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Issues record was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->IssuesRecord->delete($id)) {
				$this->Session->setFlash(__('Issues record deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Issues record was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>