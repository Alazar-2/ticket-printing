<?php
class TicketsController extends AppController {

	var $name = 'Tickets';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('tickets', $this->Ticket->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Ticket->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid ticket', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Ticket->recursive = 2;
		$this->set('ticket', $this->Ticket->read(null, $id));
	}
        
        function issue($id = null) {
            if (!empty($this->data)) {
                        $this->Ticket->Issue->create();
			$this->autoRender = false;
                        $this->data['Issue']['ticket_id']=$this->data['Ticket']['id'];
                        $this->data['Issue']['user_id']=$this->Session->read('Auth.User.id');
                        if ($this->Ticket->Issue->save($this->data)) {
                            $this->loadModel('Record');
                            foreach($this->data['Field'] as $key => $field){
                                 $this->Record->create();
                                 $this->data2['Record']['issue_id']=$this->Session->getLastInsertId();;
                                 $this->data2['Record']['field_id']=$key;
                                 $this->data2['Record']['value']=$field;
                                 $this->Record->save($this->data2);
                            }
				$this->Session->setFlash(__('The ticket has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The ticket could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			};
            }else{
		if (!$id) {
			$this->Session->setFlash(__('Invalid ticket', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Ticket->recursive = 2;
		$this->set('ticket', $this->Ticket->read(null, $id));
            }
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Ticket->create();
			$this->autoRender = false;
			if ($this->Ticket->save($this->data)) {
				$this->Session->setFlash(__('The ticket has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The ticket could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid ticket', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Ticket->save($this->data)) {
				$this->Session->setFlash(__('The ticket has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The ticket could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('ticket', $this->Ticket->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for ticket', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Ticket->delete($i);
                }
				$this->Session->setFlash(__('Ticket deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Ticket was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Ticket->delete($id)) {
				$this->Session->setFlash(__('Ticket deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Ticket was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>