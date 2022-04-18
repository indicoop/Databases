<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LoanTypes;

class LoanTypesController extends Controller
{
   public function index()
   {
         $loan_types = LoanTypes::all();

         return view('loan_types');
   }
}
