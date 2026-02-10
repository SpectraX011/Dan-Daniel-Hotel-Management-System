<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

   public function index()
{
   if(Auth::check())
   {
      $usertype = Auth::user()->usertype;

      if($usertype =='user')
       {
           return view('dashboard'); // Sends user to the regular dashboard view
       }
      
else if($usertype == 'admin')
       {
          return view('admin.index'); // Sends admin to the admin HTML view
       }
       else
           {
               return redirect()->back();
           }
   }
}
   
}