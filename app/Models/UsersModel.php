<?php
namespace App\models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\RequestInterface;

class UsersModel extends Model
{

  public $table         = 'users';
  public $column_order  = array(null, 'username', 'level');
  public $column_search = array('username', 'level');
  public $order         = array('id_users' => 'desc');

  function __construct(RequestInterface $request)
  {
    parent::__construct();
    $this->db = db_connect();
    $this->request = $request;
    $this->dt = $this->db->table($this->table);
  }

  private function _get_datatables_query()
  {
    $this->dt->select('id_users, username, level');
    $i = 0;
    foreach ($this->column_search as $item){
        if($this->request->getPost('search')['value']){ 
            if($i===0){
                $this->dt->groupStart();
                $this->dt->like($item, $this->request->getPost('search')['value']);
            }
            else{
                $this->dt->orLike($item, $this->request->getPost('search')['value']);
            }
            if(count($this->column_search) - 1 == $i)
                $this->dt->groupEnd();
        }
        $i++;
    }
     
    if($this->request->getPost('order')){
            $this->dt->orderBy($this->column_order[$this->request->getPost('order')['0']['column']], $this->request->getPost('order')['0']['dir']);
        } 
    else if(isset($this->order)){
        $order = $this->order;
        $this->dt->orderBy(key($order), $order[key($order)]);
    }
  }

  function get_datatables(){
    $this->_get_datatables_query();
    if($this->request->getPost('length') != -1)
    $this->dt->limit($this->request->getPost('length'), $this->request->getPost('start'));
    $query = $this->dt->get();
    return $query->getResult();
  }
  function count_filtered(){
    $this->_get_datatables_query();
    return $this->dt->countAllResults();
  }
  public function count_all(){
    $tbl_storage = $this->db->table($this->table);
    return $tbl_storage->countAllResults();
  }

  public function users($where)
  {
    $table = $this->db->table('users');
    $table->select('id_users, username, password');
    $table->where($where);
    return $table->get();
  }

  public function insertSession($data)
  {
    $table = $this->db->table('user_sessions');
    $table->replace($data);
    return $this->db->affectedRows();
  }

  public function userSessions($where)
  {
    $table = $this->db->table('users a');
    $table->select('a.id_users, a.username, b.sessions');
    $table->join('user_sessions b', 'a.id_users = b.id_user', 'left');
    $table->where($where);
    return $table->get();
  }

  public function removeSessions($session)
  {
    $table = $this->db->table('user_sessions');
    $table->where('sessions', $session);
    $table->delete();
    return $this->db->affectedRows();
  }

}
