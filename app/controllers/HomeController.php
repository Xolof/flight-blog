<?php

declare(strict_types=1);

namespace app\controllers;

class HomeController extends BaseController
{
    /**
     * Index
     *
     * @return void
     */
    public function index(): void
    {
        $this->render('home.latte', [
            'page_title' => 'Home',
        ]);
    }
}
