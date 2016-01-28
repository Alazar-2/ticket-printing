<?php
class IssuesFieldsRecordsController extends AppController {

	var $name = 'IssuesFieldsRecords';
	
	function index() {
		$tickets = $this->IssuesFieldsRecord->Ticket->find('all');
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
            $conditions['IssuesFieldsRecord.ticket_id'] = $ticket_id;
        }
		
		$this->set('issuesFieldsRecords', $this->IssuesFieldsRecord->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->IssuesFieldsRecord->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid issues fields record', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->IssuesFieldsRecord->recursive = 2;
		$this->set('issuesFieldsRecord', $this->IssuesFieldsRecord->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->IssuesFieldsRecord->create();
			$this->autoRender = false;
			if ($this->IssuesFieldsRecord->save($this->data)) {
				$this->Session->setFlash(__('The issues fields record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The issues fields record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$tickets = $this->IssuesFieldsRecord->Ticket->find('list');
		$this->set(compact('tickets'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid issues fields record', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->IssuesFieldsRecord->save($this->data)) {
				$this->Session->setFlash(__('The issues fields record has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The issues fields record could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('issues__fields__record', $this->IssuesFieldsRecord->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$tickets = $this->IssuesFieldsRecord->Ticket->find('list');
		$this->set(compact('tickets'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for issues fields record', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->IssuesFieldsRecord->delete($i);
                }
				$this->Session->setFlash(__('Issues fields record deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Issues fields record was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->IssuesFieldsRecord->delete($id)) {
				$this->Session->setFlash(__('Issues fields record deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Issues fields record was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>