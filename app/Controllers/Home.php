<?php

namespace App\Controllers;

use App\Models\ProductModel; 

class Home extends BaseController
{
    protected $productModel;
    function __construct(){
        helper(['number', 'form']);
        $this->productModel = new ProductModel();
    }
    public function index(): string
    {
        return view('v_home', [
            'products' => $this->productModel->findAll()
        ]);
    }

    public function faq()
    {
        return view('v_faq');
    }

    public function contact()
    {
        return view('v_contact');
    }
}
