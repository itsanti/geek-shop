<?php

namespace MyApp\controllers;


use MyApp\base\App;

class CheckoutController extends Controller
{
    public function actionIndex() {
        $menu = App::call()->categoryRep->getTree();
        if (static::$user->getIsAuth()) {
            $products_number = App::call()->orderRep->getNumberOfProducts(static::$user->getId());
            $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId(static::$user->getId())[0]['id'];
            if (null === $order_id) $order_id = 0;
            $ordered_products = App::call()->orderRep->fetchProductsByOrderId($order_id);
        } else {
            $products_number = App::call()->sessionsRep->getNumberOfProductsGuest(static::$user->getId());
            $ordered_products = App::call()->sessionsRep->getProductsBySessionIdGuest(static::$user->getId());
        }

        App::call()->renderer->render('page/checkout',
            [
                'layout' => true,
                'breadcrumbs' => true,
                'is_auth' => static::$user->getIsAuth(),
                'products_number' => $products_number,
                'ordered_products' => $ordered_products,
                'user_name' => static::$user->getLogin(),
                'is_admin' => static::$user->getIsAdmin(),
                'menu' => $menu
            ]
        );
    }
}