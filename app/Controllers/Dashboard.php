<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\libraries\Tema;

class Dashboard extends Controller
{
  public function index()
  {
    $tema = new Tema();
    if ($tema->auth() === 0) {
      return redirect()->to('login');
    }
    $data = [];
    $tema->setJudul('Users Dashboard');
    $tema->loadView('tema/content', $data);
  }
}
