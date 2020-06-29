<?php

namespace App\Controllers;

use Inf\Router\RouterRequest;

class Todos extends Controller{

	function __construct(){
		parent::__construct();
	}
	
	public function listTodos($order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordsFrom('todos',true,$page,$limit);
		

		// Perform Ordering if Descending
		$data = array_values($data);
		if(\strtolower($order)!=='asc'){
			krsort($data);
			$data = array_values($data);
		}

		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}
	
	public function listTodosByUser($id,$order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordBy('todos','userId',$id);
		$data =  $this->helper->paginate($data,$page,$limit);		

		// Perform Ordering if Descending
		$data = array_values($data);
		if(\strtolower($order)!=='asc'){
			krsort($data);
			$data = array_values($data);
		}

		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}

	
	public function getTodo($id){
		$data =  $this->helper->getRecordById('todos',$id);
		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}


	
	public function addTodo(){
		$title = RouterRequest::postData('title');
		$completed = RouterRequest::postData('completed');
		$data = [
			'userId'=>1,
			'title'=>$title,
			'completed'=>(bool)$completed,
			'createdAt'=>date('Y-m-d\TH:i:s\Z', time()),
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->addRecordTo('todos',$data);		
		if(False != $save){			
			return $this->process(['status' => 1, 'msg' => 'A new Todo added','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to add todo at the moment'],203)->send();
		}
	}
	
	public function editTodo(){
		
		$id = RouterRequest::reqData('id');
		$title = RouterRequest::reqData('title');
		$completed = RouterRequest::reqData('completed');

		$data = [
			'title'=>$title,
			'completed'=>(bool)$completed,
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->editRecordIn('todos',$data,$id);		
		if(False != $save){				
			return $this->process(['status' => 1, 'msg' => 'Todo updated','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to update todo at the moment'],203)->send();
		}
	}
	
	public function deleteTodo($id){
		$save = $this->helper->deleteRecordFrom('todos',$id);		
		if($save){			
			return $this->process(['status' => 1, 'msg' => 'Todo deleted'])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to delete todo at the moment'],203)->send();
		}
	}
}