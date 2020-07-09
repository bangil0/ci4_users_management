<?php namespace App\Libraries;
/**
 *
 */
class Tema
{
  private $_judul;

  function __construct()
  {
    $this->session = \Config\Services::session();
  }

  public function setJudul($judul)
  {
    $this->_judul = $judul;
  }

  public function getJudul()
  {
    return $this->_judul;
  }

  public function loadView($content, $data = [])
  {
    $data['__judul'] = $this->getJudul();
    $data['__user'] = $this->session->get('username');
    echo view($content, $data);
  }

  public function auth()
  {
    $x = 1;
    if (empty($this->session->get('sessions'))) {
      $x = 0;
      $this->session->setFlashdata('messageLogin', '<div class="alert alert-danger">Anda Belum Login</div>');
    }
    return $x;
  }
}
