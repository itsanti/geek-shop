<?php

namespace MyApp\controllers;


use MyApp\base\App;

class OrderController extends Controller
{
    public function actionIndex() {
        $menu = App::call()->categoryRep->getTree();

        if (static::$user->getIsAuth()) {
            $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId(static::$user->getId())[0]['id'];
            if (null === $order_id) $order_id = 0;

            $ordered_products = App::call()->orderRep->fetchProductsByOrderId($order_id);
            $products_number = App::call()->orderRep->getNumberOfProducts(static::$user->getId());
        } else {
            $products_number = App::call()->sessionsRep->getNumberOfProductsGuest(static::$user->getId());
            $ordered_products = App::call()->sessionsRep->getProductsBySessionIdGuest(static::$user->getId());
        }
        $message = null;
        if (!$ordered_products) {
            $message = App::call()->getConfig("empty_basket_message");
        }

        App::call()->renderer->render('page/order',
            [
                'layout' => true,
                'breadcrumbs' => true,
                'ordered_products' => $ordered_products,
                'is_auth' => static::$user->getIsAuth(),
                'message' => $message,
                'products_number' => $products_number,
                'user_name' => static::$user->getLogin(),
                'is_admin' => static::$user->getIsAdmin(),
                'menu' => $menu
            ]
        );
    }

    public function actionAdd() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            if (static::$user->getIsAuth()) {
                App::call()->orderRep->saveOrder($product_id, static::$user->getId());
                $products_number = App::call()->orderRep->getNumberOfProducts(static::$user->getId());
            } else {
                App::call()->sessionsRep->addProductToSessionGuest(static::$user->getId(), $product_id);
                $products_number = App::call()->sessionsRep->getNumberOfProductsGuest(static::$user->getId());
            }
            $response =
                    [
                        'products_number' => $products_number
                    ];
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            /*$referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
            $this->redirect($referer);*/
        } else {
            echo "Что-то пошло не так";
//            $this->redirect('home');
        }
    }

    public function actionProceed() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (static::$user->getIsAuth()) {
                App::call()->orderRep->proceedOrder(static::$user->getId());
            }
        }
        $this->redirect('order');
    }

    public function actionDelete()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'];
            if (static::$user->getIsAuth()) {
                App::call()->orderRep->deleteOrder($product_id, static::$user->getId());
            } else {
                App::call()->sessionsRep->deleteProductFromSessionGuest(static::$user->getId(), $product_id);
            }
        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

    public function actionClear()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (static::$user->getIsAuth()) {
                App::call()->orderRep->clearOrder(static::$user->getId());
            } else {
                App::call()->sessionsRep->clearOrder(static::$user->getId());
            }

        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

}
