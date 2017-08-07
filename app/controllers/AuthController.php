<?php

namespace MyApp\controllers;

use MyApp\base\App;

class AuthController extends Controller
{
    public function actionIndex() {
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (App::call()->auth->login($_POST['login'], $_POST['password'])) {
                $this->redirect('home');
                return true;
            }
            $message = "Неверный логин или пароль";
        }

        App::call()->renderer->render('page/login',
            [
                'layout' => false,
                'breadcrumbs' => false,
                'message' => $message
            ]
        );
    }

    public function actionLogout()
    {
        App::call()->auth->logout();
        $this->redirect('home');
    }

    public function actionRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (App::call()->auth->register($_POST['login'], $_POST['password'])) {
                if (static::$user->getIsAuth()) {
                    App::call()->auth->logout();
                }
                $orders = $_SESSION[static::$user->getId()]['ordered_products'];
                App::call()->auth->login($_POST['login'], $_POST['password']);
                $this->defineUser();
                if ($orders) {
                    foreach ($orders as $order) {
                        App::call()->orderRep->saveOrder($order['id'], static::$user->getId(), $order['number']);
                    }
                }
                $this->redirect('home');
                return true;
            }
        }

        if (static::$user->getIsAuth()) {
            $this->redirect('home');
        }

        App::call()->renderer->render('page/register',
            [
                'layout' => false,
                'breadcrumbs' => false,
            ]
        );
    }
}