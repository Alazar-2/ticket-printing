<?php
class WarehousesController extends AppController {

	var $name = 'Warehouses';
	
	function index() {
	}
	

	function search() {
	}
	
	function list_data($id = null) {
		$start = (isset($_REQUEST['start'])) ? $_REQUEST['start'] : 0;
		$limit = (isset($_REQUEST['limit'])) ? $_REQUEST['limit'] : 5;
        $conditions = (isset($_REQUEST['conditions'])) ? $_REQUEST['conditions'] : '';

        eval("\$conditions = array( " . $conditions . " );");
		
		$this->set('warehouses', $this->Warehouse->find('all', array('conditions' => $conditions, 'limit' => $limit, 'offset' => $start)));
		$this->set('results', $this->Warehouse->find('count', array('conditions' => $conditions)));
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid warehouse', true));
			$this->redirect(array('action' => 'index'));
		}
		$this->Warehouse->recursive = 2;
		$this->set('warehouse', $this->Warehouse->read(null, $id));
	}

	function add($id = null) {
		if (!empty($this->data)) {
			$this->Warehouse->create();
			$this->autoRender = false;
			if ($this->Warehouse->save($this->data)) {
				$this->Session->setFlash(__('The warehouse has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The warehouse could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
	}

	function edit($id = null, $parent_id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid warehouse', true), '');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->autoRender = false;
			if ($this->Warehouse->save($this->data)) {
				$this->Session->setFlash(__('The warehouse has been saved', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('The warehouse could not be saved. Please, try again.', true), '');
				$this->render('/elements/failure');
			}
		}
		$this->set('warehouse', $this->Warehouse->read(null, $id));
		
			
	}

	function delete($id = null) {
		$this->autoRender = false;
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for warehouse', true), '');
			$this->render('/elements/failure');
		}
		if (stripos($id, '_') !== false) {
            $ids = explode('_', $id);
            try{
                foreach ($ids as $i) {
                    $this->Warehouse->delete($i);
                }
				$this->Session->setFlash(__('Warehouse deleted', true), '');
				$this->render('/elements/success');
            }
            catch (Exception $e){
				$this->Session->setFlash(__('Warehouse was not deleted', true), '');
				$this->render('/elements/failure');
            }
        } else {
            if ($this->Warehouse->delete($id)) {
				$this->Session->setFlash(__('Warehouse deleted', true), '');
				$this->render('/elements/success');
			} else {
				$this->Session->setFlash(__('Warehouse was not deleted', true), '');
				$this->render('/elements/failure');
			}
        }
	}
}
?>