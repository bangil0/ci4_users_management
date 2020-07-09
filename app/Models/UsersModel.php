<?php
namespace App\models;

use CodeIgniter\Model;

class UsersModel extends Model
{

  function __construct()
  {
    $this->db = db_connect();
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
