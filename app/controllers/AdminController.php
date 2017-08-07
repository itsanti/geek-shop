<?php

namespace MyApp\controllers;

use MyApp\base\App;

class AdminController extends Controller
{
    public function actionIndex() {

        if (static::$user->getIsAdmin()) {
            App::call()->renderer->render('page/admin',
                [
                    'layout' => false,
                    'breadcrumbs' => false,
                ]
            );
        } else {
            $this->redirect('home');
        }
    }

    public function actionProducts() {
        if (static::$user->getIsAdmin()) {
            $products = App::call()->productRep->getAll();
            App::call()->renderer->render('admin/products',
                [
                    'layout' => false,
                    'breadcrumbs' => false,
                    'products' => $products,
                ]
            );
        } else {
            $this->redirect('home');
        }
    }

    public function actionOrders() {
        if (static::$user->getIsAdmin()) {
            $orders = App::call()->orderRep->getAll();
            App::call()->renderer->render('admin/orders',
                [
                    'layout' => false,
                    'breadcrumbs' => false,
                    'orders' => $orders,
                ]
            );
        } else {
            $this->redirect('home');
        }
    }

    public function actionCustomers() {
        if (static::$user->getIsAdmin()) {
            $customers = App::call()->customerRep->getAll();
            App::call()->renderer->render('admin/customers',
                [
                    'layout' => false,
                    'breadcrumbs' => false,
                    'customers' => $customers,
                ]
            );
        } else {
            $this->redirect('home');
        }
    }

    public function actionCategories()
    {
        if (static::$user->getIsAdmin()) {

            $categories = App::call()->categoryRep->getTree();
            $result = [];
            foreach ($categories as $category) {

            }
            App::call()->renderer->render('admin/categories',
                [
                    'layout' => false,
                    'breadcrumbs' => false,
                    'categories' => $categories,
                ]
            );
        } else {
            $this->redirect('home');
        }
    }
}
