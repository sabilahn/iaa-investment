<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'IAA Investment | Dashboard';

        return view('header', $data)
            . view('menu')
            . view('dashboard')
            . view('footer');
    }
}

