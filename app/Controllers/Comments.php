<?php

namespace App\Controllers;

use Inf\Router\RouterRequest;

class Comments extends Controller{

	function __construct(){
		parent::__construct();
	}
	
	public function listComments($order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');

		$data =  $this->helper->getRecordsFrom('comments',true,$page,$limit);
		

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
	
	public function listCommentsByPost($id,$order = 'desc'){
		$page = RouterRequest::getData('page');
		$limit = RouterRequest::getData('limit');
		$data =  $this->helper->getRecordBy('comments','postId',$id);
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

	
	public function getComment($id){
		$data =  $this->helper->getRecordById('comments',$id);
		if($data){
			return $this->process($data)->send();
		}else{
			return $this->process($data,204,'text')->send();
		}
	}


	public function addComment(){
		$name = RouterRequest::postData('name');
		$email = RouterRequest::postData('email');
		$body = RouterRequest::postData('body');
		$data = [
			'postId'=>1,
			'name'=>$name,
			'email'=>$email,
			'body'=>$body,
			'createdAt'=>date('Y-m-d\TH:i:s\Z', time()),
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->addRecordTo('comments',$data);		
		if(False != $save){			
			return $this->process(['status' => 1, 'msg' => 'A new Comment added','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to add comment at the moment'],203)->send();
		}
	}
	
	public function editComment(){
		
		$id = RouterRequest::reqData('id');
		$name = RouterRequest::reqData('name');
		$email = RouterRequest::reqData('email');
		$body = RouterRequest::reqData('body');

		$data = [
			'name'=>$name,
			'email'=>$email,
			'body'=>$body,
			'updatedAt'=>date('Y-m-d\TH:i:s\Z', time()),
		];
		$save = $this->helper->editRecordIn('comments',$data,$id);		
		if(False != $save){				
			return $this->process(['status' => 1, 'msg' => 'Comment updated','data'=>$save])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to update comment at the moment'],203)->send();
		}
	}
	
	public function deleteComment($id){
		$save = $this->helper->deleteRecordFrom('comments',$id);		
		if($save){			
			return $this->process(['status' => 1, 'msg' => 'Comment deleted'])->send();
		}else{
			return $this->process(['status' => 0, 'msg' => 'Unable to delete comment at the moment'],203)->send();
		}
	}

}