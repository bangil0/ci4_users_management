<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;

class Login extends Controller
{

  function __construct()
  {
    $this->session = session();
  }

  public function index ()
  {
    // $this->session = session();
    $data = [
      'messageLogin' => $this->session->getFlashdata('messageLogin')
    ];
    helper('form');
    echo view('login', $data);
  }

  public function loginAction()
  {
    $request = \Config\Services::request();
    $username = $request->getPost('username');
    $password = $request->getPost('password');

    $data = [
      'username' => $username
    ];

    $_username = hash('sha512', $username, false);
    $_password = hash('sha512', $password, false);
    $__password = $_username.$_password;
    $db = new UsersModel($request);
    $query = $db->users($data)->getResult();
    if (count($query)) {
      foreach ($query as $k_query => $v_query) {
        if ( $__password === $v_query->password ) {
          $id = hash('sha512', uniqid(), false);
          $data_session = [
            'id_user' => $v_query->id_users,
            'sessions' => $id
          ];
          $query_inset_session = $db->insertSession($data_session);
          $data_session['username'] = $v_query->username;
          $this->session->set($data_session);
          return redirect()->to('dashboard');
        } else {
          $this->session->setFlashdata('messageLogin', '<div class="alert alert-danger">Password Salah</div>');
          return redirect()->to('login');
        }
      }
    } else {
      $this->session->setFlashdata('messageLogin', '<div class="alert alert-danger">Username Salah</div>');
      return redirect()->to('login');
    }
  }

  public function logout()
  {
    $request = \Config\Services::request();
    $idsession = $this->session->get('sessions');
    $userModel = new UsersModel($request);
    $query = $userModel->removeSessions($idsession);
    if ($query >= 1) {
      $this->session->remove('sessions');
      $this->session->setFlashdata('messageLogin', '<div class="alert alert-success">Logout Berhasil</div>');
      return redirect()->to('login');
    }
  }
}
