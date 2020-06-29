<?php
namespace App;

use Inf\Router\RouterRequest;
class Helper{

	private $original;
	private $types = ['posts','comments','albums','photos','users','todos'];
	private $posts;
	private $comments;
	private $albums;
	private $photos;
	private $users;
	private $todos;
	
	public function __construct(){}

	public function isAcceptedType($type){
		if(in_array(trim($type),$this->types)){return true;}return false;
	}


	public function paginate($data, $page=1, $limit=20){
		$total = count( $data ); //total items in array			
		$limit = $limit <= 0 ? $total : $limit;	
		$totalPages = ceil( $total/ $limit ); //calculate total pages
		// This turned page number to 1 when $page <= 0 and page number to lastpage when $page > $totalPages 
		$page = min(max($page, 1), $totalPages);
		$offset = ($page - 1) * $limit;
		if( $offset < 0 ) $offset = 0;
		return array_slice( $data, $offset, $limit );
	}

	
	public function getOriginalData($type='original',$returnArray=false){
		$fileContent= file_get_contents(ROOT."datas/original.json");
		$data = json_decode($fileContent,$returnArray);
		return $type=='original' ? $data : (is_object($data) ? $data->{$type} : $data[$type]);
	}

	
	public function updateOriginalData($type=null, array $data){
		if(!$this->isAcceptedType($type)){ return false; }
		$originalData = $this->getOriginalData('original',true);
		$originalData[$type] = $data;
		$json = json_encode($originalData,JSON_PRETTY_PRINT);
		$jsonFile = ROOT."datas/original.json";
		if(!\file_exists($jsonFile)){
			touch($jsonFile);
		}
		if(\file_put_contents($jsonFile,$json)){
			return true;
		}
		return false;
	}

	public function refreshDataFromOriginal($type=null){
		if(!$this->isAcceptedType($type)){ return false; }
		$data = $this->getOriginalData('original',true);
		$json = json_encode($data[$type],JSON_PRETTY_PRINT);
		$jsonFile = ROOT."datas/$type.json";
		if(!\file_exists($jsonFile)){
			touch($jsonFile);
		}
		if(\file_put_contents($jsonFile,$json)){
			return true;
		}
		return false;
	}

	public function refreshAllDataFromOriginal(){
		foreach($this->types AS $data){
			$this->refreshDataFromOriginal($data);
		}
	}


	public function getRecordsFrom($type,$paginate=true,$page=1,$limit=20){
		if(!$this->isAcceptedType($type)){ return false; }
		$fileContent= file_get_contents(ROOT."datas/$type.json");
		if($paginate){			
			$data = json_decode($fileContent,true);
			$data = $this->paginate($data, $page, $limit);
			// Return data as json object
			$data = json_decode(json_encode($data));
		}else{
			// Return data as json object
			$data = json_decode($fileContent);
		}
		return $data;
	}

	public function addRecordTo($type,array $data){
		if(!$this->isAcceptedType($type)){ return false; }
		$arrs = $this->getRecordsFrom($type,false);
		$lastArr = end($arrs);
		$insert = ['id'=>$lastArr->id + 1];
		if($type != 'users'){
			$data = array_slice($data, 0, 1, true) + $insert + array_slice($data, 1, count($data) - 1, true);
		}else{
			$data = array_slice($data, 0, 0, true) + $insert + array_slice($data, 0, count($data), true);
		}
	
		$arrs[] = $data;
		if($this->updateAllData($type,$arrs)){
			return $data;
		}
		return false;
	}

	public function editRecordIn($type,array $data, $id){
		if(!$this->isAcceptedType($type)){ return false; }
		$arrs = $this->getRecordsFrom($type,false);
		$return = [];
		$newData = [];
		foreach($arrs AS $arr){
			if($arr->id == $id){
				$return = array_merge((array)$arr,$data);
				$newData[] = $return;
			}else{
				$newData[] = $arr;
			}
		}
		if($this->updateAllData($type,$newData)){
			return $return;
		}
		return false;
	}

	public function deleteRecordFrom($type,$id){
		if(!$this->isAcceptedType($type)){ return false; }
		$arrs = $this->getRecordsFrom($type,false);
		$delKey = null;
		foreach($arrs AS $k => $arr){
			if($arr->id == $id){
				$delKey = $k;
				break;
			}
		}
		unset($arrs[$delKey]);
		return $this->updateAllData($type,array_values($arrs));
	}

	public function getRecordById($type,$id){
		if(!$this->isAcceptedType($type)){ return false; }
		$object = $this->getRecordsFrom($type,false);
		$data = [];
		foreach($object AS $obj){
			if($obj->id == $id){
				$data = $obj;
				break;
			}
		}
		return $data;
	}

	public function getRecordBy($type,$key,$value){
		if(!$this->isAcceptedType($type)){ return false; }
		$object = $this->getRecordsFrom($type,false);
		$data = [];
		foreach($object AS $obj){
			if($obj->{$key} == $value){
				$data[] = $obj;
			}
		}
		return $data;
	}
	
	public function updateAllData($type=null, $data){
		if(!$this->isAcceptedType($type)){ return false; }
		$json = '';
		if(is_array($data)){
			$json = json_encode($data,JSON_PRETTY_PRINT);
		}else if(is_string($data)){
			$json = json_encode(json_decode($data),JSON_PRETTY_PRINT);
		}else{
			return false;
		}
		$jsonFile = ROOT."datas/$type.json";
		if(!\file_exists($jsonFile)){
			touch($jsonFile);
		}
		if(\file_put_contents($jsonFile,$json)){
			return true;
		}
		return false;
	}

	/* public function modifyPost(){
		foreach($this->types AS $type){
			$postData = $this->getOriginalData($type);
			$posts =[];
			foreach($postData AS $post){
				$post->createdAt = date('Y-m-d\TH:i:s\Z', time());
				$post->updatedAt = date('Y-m-d\TH:i:s\Z', time());
				$posts[] = $post;
			}
			$this->updateOriginalData($type,$posts);
		}
	} */
	
	public function pa($data,$kill=false){
		echo "<pre>";
			print_r($data);
		echo "</pre>";
		if(True === $kill){
			die();
		}
	}

	public function getBooks($page=null, $perPage=50,$addCharacters=false){
		$endpont = 'books';
		if(!empty($page)){
			$endpont .= "?page=$page&pageSize=$perPage";
		}else{
			$endpont .= "?pageSize=$perPage";
		}
		$list = $this->getApiData($endpont);

		$data = [];
		foreach($list AS $book){
			$date = new \DateTime($book['released']);
			$date->setTimezone(new \DateTimeZone('Africa/Lagos')); // +01
			$released = $date->format('Y-m-d H:i:s'); // 2012-07-15 05:00:00 
			$key = strtotime($released);

			$data[$key]['released'] = $released;

			$urlArr = explode('/',$book['url']);
			$bookId = end($urlArr); 
			$data[$key]['id'] = $bookId;
			$data[$key]['name'] = $book['name'];
			$data[$key]['authors'] = $book['authors'];
			$data[$key]['isbn'] = $book['isbn'];
			$data[$key]['numberOfPages'] = $book['numberOfPages'];
			$data[$key]['publisher'] = $book['publisher'];
			$data[$key]['country'] = $book['country'];
			$data[$key]['mediaType'] = $book['mediaType'];
			$data[$key]['comments_count'] = $this->getBookCommentCount($bookId);
			if(True === $addCharacters){
				$data[$key]['characters'] = $book['characters'];
			}
			 
		}

		krsort($data);

		//return $list;
		return array_values($data);
	}


	public function getBook($bookId, $addCharacters=false){
		$book = $this->getApiData('books/'.$bookId);

		$date = new \DateTime($book['released']);
		$date->setTimezone(new \DateTimeZone('Africa/Lagos')); // +01
		$released = $date->format('Y-m-d H:i:s'); // 2012-07-15 05:00:00

		$data['released'] = $released;

		$urlArr = explode('/',$book['url']);
		$bookId = end($urlArr); 
		$data['id'] = $bookId;
		$data['name'] = $book['name'];
		$data['authors'] = $book['authors'];
		$data['isbn'] = $book['isbn'];
		$data['numberOfPages'] = $book['numberOfPages'];
		$data['publisher'] = $book['publisher'];
		$data['country'] = $book['country'];
		$data['mediaType'] = $book['mediaType'];
		if(True === $addCharacters){
			$data['characters'] = $book['characters'];
		}
		$data['comments_count'] = $this->getBookCommentCount($bookId);
		//$data['comments'] = $this->getComments($bookId);
		return $data;
	}


	


	public function getCharacters($page=null, $perPage=50){
		$endpont = 'characters';
		if(!empty($page)){
			$endpont .= "?page=$page&pageSize=$perPage";
		}else{
			$endpont .= "?pageSize=$perPage";
		}
		$list = $this->getApiData($endpont);
		
		$info = $data = [];
		$info['character_count'] = count($list);
		foreach($list AS $character){
			$urlArr = explode('/',$character['url']);
			$characterId = end($urlArr); 
			$char['id'] = $characterId;
			$char['name'] = $character['name'];
			$char['age'] = $character['born'];
			$char['gender'] = $character['gender'];
			$data[] = $char;
		}
		$info['characters'] = $data;
		//return $list;
		return $info;
	}




	public function arrayFilterByValue($array, $index, $value)
    { 
		$new_array = [];
        if(is_array($array) && count($array)>0)  
        { 
			$temp = [];
            foreach(array_keys($array) as $key){ 
                $temp[$key] = $array[$key][$index]; 
                 
                if ($temp[$key] == $value){ 
                    $new_array[$key] = $array[$key]; 
                } 
            } 
          } 
      return $new_array; 
	} 
	

	

	public function getBookCommentCount($bookId){
		$sql = "SELECT COUNT(*) AS `count` FROM `comments` WHERE `book_id` =  $bookId ORDER BY `date` DESC ";
		$data = $this->db->fetchOneRow($sql);
		return $data['count'];
	}



}