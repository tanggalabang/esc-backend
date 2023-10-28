<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileController extends Controller
{
  //
  // public function index()
  // {
  //   $filePaths = [
  //     'http://localhost/upload/profile/halo.mp3',
  //     'http://localhost/upload/profile/halo.mp4',
  //     'http://localhost/upload/profile/halo.jpg',
  //     'http://localhost/upload/profile/halo.pdf',
  //   ];

  //   return view('files.index', compact('filePaths'));
  // }
  public function index()
  {
    $mp3File = asset('upload/profile/halo.mp3');
    $mp4File = asset('upload/profile/halo.mp4');
    $jpgFile = asset('upload/profile/halo.jpg');
    $pdfFile = asset('upload/profile/halo.pdf');

    return view('files.index', compact('mp3File', 'mp4File', 'jpgFile', 'pdfFile'));
  }
}
