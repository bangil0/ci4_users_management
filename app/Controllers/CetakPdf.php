<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\libraries\Pdf;

class CetakPdf extends Controller
{
  public function index()
  {
    $pdf = new Pdf();
  }
}
