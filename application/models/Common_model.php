<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model 
{	
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Kolkata');
		if (!isset($_SESSION) )
		{ 	
			session_start();
		} 
		$this->load->library('encrypt');
	}

	function login_check()
	{
		if ($this->session->userdata('user_id') == NULL)
			redirect(SITE_URL);
	}

    function insert_data($table=0,$data)
    {
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
                $this->db->insert($table,$data);
                return $this->db->insert_id();
            }
	    }
	    return 0;
	}    

	/**
	* Insert batch data
	* author: mahesh , created on: 22nd june 2016 04:15 PM, updated on: --
	* @param: $table(string) default:0
	* @param: $data(2D array)
	* return: true/false(boolean)
	**/
	function insert_batch_data($table=0,$data)
    {
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
                $this->db->insert_batch($table,$data);
                return true;
            }
	    }
	    return false;
	}    
	
	//Update data
    function update_data($table = 0,$data, $where)
    {
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
                $this->db->update($table, $data, $where);
                return $this->db->affected_rows();
            }
        }
        return 0;
    }     
    
	//Get result      
    function get_data($table = 0 , $where="", $select = "", $type=1 )
    {
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
            	$this->db->select($select);
                $query = $this->db->get_where($table, $where);
                if($type == 1)
                {
                    return $query->result_array();
                }
                else
                {
                    return $query->result();
                }
            }
        }
        return 0;  
    }     
    
	//Get single value
    function  get_value($table = 0 ,$where=array(),$column )
    {
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
                if($column!=NULL)
                {
                    $query = $this->db->get_where($table,$where);
                    $result= $query->row();
                    if($result!=NULL){
                    return $result->$column;
                    }else{
                        return NULL;
                    }
                }
            }    
        }
        return 0;
    }

    function get_query_result($qry, $type = "array")
    {
    	if($qry)
    	{
    		$res = $this->db->query($qry);
    		if($type == "array")
    			return $res->result_array();
    		else
    			return $res->result();
    	}
    }

    function get_no_of_rows($qry)
    {
    	if($qry)
    	{
    		$res = $this->db->query($qry);
    		return $res->num_rows();
    	}
    }

    function get_dropdown($table=0, $key_column, $val_column, $where=[], $concat = '')
    {
        $data = [];
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
                if($concat == '')
                    $this->db->select(array($key_column, $val_column));
                else
                    $this->db->select(array($key_column, $concat));
                $query = $this->db->get_where($table,$where);
                foreach($query->result_array() as $row)
                {
                    $data[$row[$key_column]] = $row[$val_column];
                }
                return $data;
            }
        }
        return 0;
    }  

    function getAutocompleteData($table=0, $column, $term)
    {
        $this->db->select($column);
        $this->db->from($table);
        $this->db->where('status',1);
        $this->db->like($column, $term);
        $this->db->limit(20, 0);
        $res = $this->db->get();
        $json=array();
        foreach($res->result_array() as $row){
             $json[]=array(
                        'value'=> $row[$column],
                        'label'=>$row[$column]
                            );
        }
        return $json;
    }

    // Phase2 method:  prasad 27th july 2017  start
    function get_data_row($table = 0 , $where="", $select = "", $type=1 )
    {
        if($table !== 0)
        {
            if ($this->db->table_exists($table))
            {
                $this->db->select($select);
                $query = $this->db->get_where($table, $where);
                if($type == 1)
                {
                    return $query->row_array();
                }
                else
                {
                    return $query->row();
                }
            }
        }
        return 0;  
    }
    // Phase2 method:  prasad 27th july 2017  end


}
