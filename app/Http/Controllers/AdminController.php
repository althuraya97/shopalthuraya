<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
{
    return view('admin.dashboard'); // تأكد من إنشاء هذا الملف في resources/views/admin/dashboard.blade.php
}
}
