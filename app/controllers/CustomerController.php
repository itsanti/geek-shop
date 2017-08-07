<?php

namespace MyApp\controllers;


use MyApp\base\App;

class CustomerController extends Controller
{
    public function actionCreate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($_POST['password1'] === $_POST['password2']) {

            }
        }
    }

    public function actionDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_id = $_POST['id'];
            App::call()->customerRep->deleteCustomer($customer_id);
        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

    public function actionUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $customer_id = $_POST['id'];
            $customer_login = $_POST['login'];
            $customer_is_admin = $_POST['is_admin'];
            $customer_password = $_POST['password'];

            App::call()->customerRep->updateCustomer($customer_id, $customer_login, $customer_is_admin, $customer_password);
            $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
            $this->redirect($referer);
        } else {
            $this->redirect('home');
        }
    }
}