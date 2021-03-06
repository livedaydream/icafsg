<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tags_model extends CI_Model{
	var $CI;
	function __construct(){
  		parent::__construct();
  		$this->CI =& get_instance();
	}
	
	function loadTags($ids,$lang,$isurl=FALSE){
		$langstr = $lang==$this->CI->Cache_model->defaultLang?'':'?lang='.$lang;
		if(!$ids){return FALSE;}
		$idarr = explode(',',$ids);
		$data = $this->CI->Data_model->getData(array('id'=>$idarr,'lang'=>$lang),'listorder',0,0,'tags');
		if(!$data){return FALSE;}
		$dataarr = array();
		foreach($data as $item){
			$dataarr[] = $isurl?'<a href="'.get_full_url('tags/'.$item['url'].$langstr).'">'.$item['title'].'</a>':$item['title'];
		};
		$datastr = implode(',',$dataarr);
		return $datastr;
	}
	
	function loadTagIds($tags,$lang){
		$tags = trim($tags);
		if($tags==''){return FALSE;}
		$tags = str_replace("，",",",$tags);		
		$tagsarr = explode(',',$tags);
		$idarr = array();
		foreach($tagsarr as $tag){
			$row = $this->CI->Data_model->getSingle(array('title'=>$tag,'lang'=>$lang),'tags');
			if($row){
				$idarr[] = $row['id'];
			}else{
				$insertData = array(
					'title'=>$tag,
					'url'=>urlencode($tag),
					'lang'=>$lang	
					);
				$idarr[] = $this->CI->Data_model->addData($insertData,'tags');
			}
		}
		$idarr = array_unique($idarr);
		return implode(',',$idarr);
	}
}