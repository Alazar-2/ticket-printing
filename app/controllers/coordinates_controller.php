<?php
class CoordinatesController extends AppController {

	var $name = 'Coordinates';
	
	function index() {
		$tickets = $this->Coordinate->Ticket->find('all');
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
            $conditions['Coordinate.ticket_id'] = $ticket_id;
        }
		
		$this->set('coordinates', $this->Coordinate->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Coordinate->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid coordinate', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Coordinate->recursive = 2;
		$this->set('coordinate', $this->Coordinate->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Coordinate->create();
			$this->autoRender = false;
			if ($this->Coordinate->save($this->data)) {
				$this->Session->setFlash(__('The coordinate has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The coordinate could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		if($id)
			$this->set('parent_id', $id);
		$tickets = $this->Coordinate->Ticket->find('list');
                $conditions['Field.ticket_id'] = $id;
		$fields = $this->Coordinate->Field->find('list',array('conditions'=>$conditions));
		$this->set(compact('tickets', 'fields'));
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid coordinate', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Coordinate->save($this->data)) {
				$this->Session->setFlash(__('The coordinate has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The coordinate could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('coordinate', $this->Coordinate->read(null, $id));
		
		if($parent_id) {
			$this->set('parent_id', $parent_id);
		}
			
		$tickets = $this->Coordinate->Ticket->find('list');
		  $conditions['Field.ticket_id'] = $parent_id;
		$fields = $this->Coordinate->Field->find('list',array('conditions'=>$conditions));
		$this->set(compact('tickets', 'fields'));
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for coordinate', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Coordinate->delete($i);
                }
				$this->Session->setFlash(__('Coordinate deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Coordinate was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Coordinate->delete($id)) {
				$this->Session->setFlash(__('Coordinate deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Coordinate was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>