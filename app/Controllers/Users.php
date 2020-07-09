<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use App\libraries\Tema;

class Users extends Controller
{
  public function index()
  {
  	$tema = new Tema();
  	$data = [];
    $tema->setJudul('Users Dashboard');
    $tema->loadView('users/index', $data);
  }

  public function ajax_list()
  {
  	$request = \Config\Services::request();
	$user = new UsersModel($request);
	if($request->getMethod(true)=='POST'){
		$lists = $user->get_datatables();
    	$data = [];
    	$no = $request->getPost("start");
    	foreach ($lists as $list) {
			$no++;
			$row = [];
			$row[] = $no;
			$row[] = $list->username;
			$level = '';
			if ($list->level == 1) {
				$level = 'admin';
			} else {
				$level = 'lainnya';
			}
			$row[] = $level;
			$data[] = $row;
		}
	$output = ["draw" => $request->getPost('draw'),
	                    "recordsTotal" => $user->count_all(),
	                    "recordsFiltered" => $user->count_filtered(),
	                    "data" => $data];
	    echo json_encode($output);
	}
  }
}
