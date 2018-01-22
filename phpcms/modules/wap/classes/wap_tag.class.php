<?php
class wap_tag {
	//数据库连接
	private $db;
	
	public function __construct() {
		$this->db = pc_base::load_model('content_model');
		$this->position = pc_base::load_model('position_data_model');
	}
	/**
	 * 初始化模型
	 * @param $catid 栏目id
	 */
	public function set_modelid($catid) {
		$siteids = getcache('category_content','commons');
		$siteid = $siteids[$catid];
		$this->category = getcache('category_content_'.$siteid,'commons');		
		$this->modelid = $this->category[$catid]['modelid'];
		$this->db->set_model($this->modelid);
		$this->tablename = $this->db->table_name;
	}
	
	/**
	 * 初始化模型
	 * @param $catid
	 */
	public function my_set_modelid($catid) {
	    static $CATS;
	    $siteids = getcache('category_content','commons');
	    if(!$siteids[$catid]) return false;
	    $siteid = $siteids[$catid];
	    if ($CATS[$siteid]) {
	        $this->category = $CATS[$siteid];
	    } else {
	        $CATS[$siteid] = $this->category = getcache('category_content_'.$siteid,'commons');
	    }
	    if($this->category[$catid]['type']!=0) return false;
	    $this->modelid = $this->category[$catid]['modelid'];
	    $this->db->set_model($this->modelid);
	    $this->tablename = $this->db->table_name;
	    if(empty($this->category)) {
	        return false;
	    } else {
	        return true;
	    }
	}
	
	/**
	 * 分页统计
	 * @param $data
	 */	
	public function count($data) {
		if($data['action'] == 'lists') {
			if(isset($data['where'])) {
				$sql = $data['where'];
			} else {
				$TYPES = getcache('wap_type','wap');
				$TYPE = $TYPES[$data['typeid']];
				$catid = intval($TYPE['cat']);
				$this->set_modelid($catid);
				if(!$this->category[$catid]) return false;
				if($this->category[$catid]['child']) {
					$catids_str = $this->category[$catid]['arrchildid'];
					$pos = strpos($catids_str,',')+1;
					$catids_str = substr($catids_str, $pos);
					$sql = "status=99 AND catid IN ($catids_str)";
				} else {
					$sql = "status=99 AND catid='$catid'";
				}
			}
			return $this->db->count($sql);
		}
	}
	/**
	 * 分类标签
	 * @param $data
	 */
	public function type($data) {
		$siteid = $data['siteid'] = intval(trim($data['siteid']))!== 0 ? intval(trim($data['siteid'])) : 1;
		$TYPES = getcache('wap_type','wap');		
		$i = 1;
		if(is_array($TYPES) && !empty($TYPES)) {
			foreach ($TYPES as $typeid=>$type) {
				if($i>$data['limit']) break;
				if($siteid && $type['siteid']!=$siteid) continue;			
				if($type['parentid']==$data['typeid']) {
					$array[$typeid] = $type;
					$i++;
				}
			}
		}
		return $array;
	}
	

	/**
	 * 列表页标签
	 * @param $data
	 */
	public function lists($data) {
		$data['siteid'] = intval(trim($data['siteid']))!== 0 ? intval(trim($data['siteid'])) : 1;
		$TYPES = getcache('wap_type','wap');
		$TYPE = $TYPES[$data['typeid']];
		if(isset($data['where'])) {
			$sql = $data['where'];
		} else {
			$child = subtype($data['typeid']);
			$catid = intval($TYPE['cat']);
			$this->set_modelid($catid);
			//var_dump($this->category);
			if(!$this->category[$catid]) return false;
			
			if(is_array($child) && count($child) >0 ) {
				foreach ($child as $_k) $catids_str .= ','.$_k['cat'];
				$pos = strpos($catids_str,',')+1;
				$catids_str = substr($catids_str, $pos);
				$sql = "status=99 AND catid IN ($catids_str)".$thumb;
			} else {
				$sql = "status=99 AND catid='$catid'".$thumb;
			}
		}
		$order = $data['order'];
		$return = $this->db->select($sql, '*', $data['limit'], $order, '', 'id');
						
		//调用副表的数据
		if (isset($data['moreinfo']) && intval($data['moreinfo']) == 1) {
			$ids = array();
			foreach ($return as $v) {
				if (isset($v['id']) && !empty($v['id'])) {
					$ids[] = $v['id'];
				} else {
					continue;
				}
			}
			if (!empty($ids)) {
				$this->db->table_name = $this->db->table_name.'_data';
				$ids = implode('\',\'', $ids);
				$r = $this->db->select("`id` IN ('$ids')", '*', '', '', '', 'id');
				if (!empty($r)) {
					foreach ($r as $k=>$v) {
						if (isset($return[$k])) $return[$k] = array_merge($v, $return[$k]);
					}
				}
			}
		}
		return $return;
	}
	
	/**
	 * 推荐位标签
	 * @param $data
	 */
	public function position($data) {
			$sql = '';
			$array = array();
			$posid = intval($data['posid']);
			$order = $data['order'];
			$thumb = (empty($data['thumb']) || intval($data['thumb']) == 0) ? 0 : 1;
			$siteid = $GLOBALS['siteid'] ? $GLOBALS['siteid'] : 1;
			$catid = (empty($data['catid']) || $data['catid'] == 0) ? '' : intval($data['catid']);
			//if(!$this->category[$catid]) return false;
			if($catid && $this->category[$catid]['child']) {
				$catids_str = $this->category[$catid]['arrchildid'];
				$pos = strpos($catids_str,',')+1;
				$catids_str = substr($catids_str, $pos);
				$sql = "`catid` IN ($catids_str) AND ";
			}  elseif($catid && !$this->category[$catid]['child']) {
				if($catid==12){
					$sql = "`catid` IN (13,24,25,26) AND";
					}
				elseif($catid==17){
					$sql = "`catid` IN (18,19,20) AND";
						}
				else{		
					$sql = "`catid` = '$catid' AND ";
				}
			}
		if($thumb) $sql .= "`thumb` = '1' AND ";
		$sql .= "`posid` = '$posid' AND `siteid` = '".$siteid."'";
		$pos_arr = $this->position->select($sql, '*', $data['limit'],$order);
		if(!empty($pos_arr)) {
			foreach ($pos_arr as $info) {
				$key = $info['catid'].'-'.$info['id'];
				$array[$key] = string2array($info['data']);
				$array[$key]['url'] = show_url($info['catid'],$info['id']);
			}
		}
		return $array;
	}
	
	/**
	 * 相关文章标签
	 * @param $data
	 */
	public function relation($data) {
	    $typeid = intval($data['typeid']);
	    $TYPES = getcache('wap_type','wap');
	    $TYPE = $TYPES[$data['typeid']];
	    $catid = intval($TYPE['cat']);	    
	    
	    $modelid = intval($data['modelid']);
	    if(!$this->my_set_modelid($catid) && $modelid) {
	        $this->db->set_model($modelid);
	        $this->tablename = $this->db->table_name;
	    } elseif(!$this->my_set_modelid($catid)) {
	        return false;
	    }
	    $order = $data['order'];
	    $sql = "`status`=99";
	    $limit = $data['id'] ? $data['limit']+1 : $data['limit'];
	    if($data['relation']) {
	        $relations = explode('|',trim($data['relation'],'|'));
	        $relations = array_diff($relations, array(null));
	        $relations = implode(',',$relations);
	        $sql = " `id` IN ($relations)";
	        $key_array = $this->db->select($sql, '*', $limit, $order,'','id');
	    } elseif($data['keywords']) {
	        $keywords = str_replace(array('%',"'"), '',$data['keywords']);
	        $keywords_arr = explode(' ',$keywords);
	        $key_array = array();
	        $number = 0;
	        $i =1;
	        $sql .= " AND catid='$catid'";
	        foreach ($keywords_arr as $_k) {
	            $sql2 = $sql." AND `keywords` LIKE '%$_k%'".(isset($data['id']) && intval($data['id']) ? " AND `id` != '".abs(intval($data['id']))."'" : '');
	            $r = $this->db->select($sql2, '*', $limit, '','','id');
	            $number += count($r);
	            foreach ($r as $id=>$v) {
	                if($i<= $data['limit'] && !in_array($id, $key_array)) $key_array[$id] = $v;
	                $i++;
	            }
	            if($data['limit']<$number) break;
	        }
	    }
	    if($data['id']) unset($key_array[$data['id']]);
	    $info = $key_array;
	    return $info;
	}
}