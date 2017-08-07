<?php

namespace MyApp\controllers;

use MyApp\base\App;

class ProductController extends Controller
{
    public function actionIndex() {
        $products = App::call()->productRep->getRandomLimit(4);

        $menu = App::call()->categoryRep->getTree();

        $product_id = $this->params[0];
        $product = App::call()->productRep->getById($product_id);

        if ($product) {
            if (static::$user->getIsAuth()) {
                $products_number = App::call()->orderRep->getNumberOfProducts(static::$user->getId());
                $order_id = App::call()->orderRep->fetchNotCompletedOrderByCId(static::$user->getId())[0]['id'];
                if (null === $order_id) $order_id = 0;
                $ordered_products = App::call()->orderRep->fetchProductsByOrderId($order_id);
            } else {
                $products_number = App::call()->sessionsRep->getNumberOfProductsGuest(static::$user->getId());
                $ordered_products = App::call()->sessionsRep->getProductsBySessionIdGuest(static::$user->getId());
            }


            App::call()->renderer->render('page/product',
                [
                    'layout' => true,
                    'breadcrumbs' => true,
                    'products' => $products,
                    'product' => $product,
                    'is_auth' => static::$user->getIsAuth(),
                    'products_number' => $products_number,
                    'ordered_products' => $ordered_products,
                    'user_name' => static::$user->getLogin(),
                    'is_admin' => static::$user->getIsAdmin(),
                    'menu' => $menu
                ]
            );
        } else {
            $this->action404();
        }
    }

    public function actionUpdate() {
        if ($post = $this->checkPost()) {
            App::call()->productRep->updateProduct($post['id'], $post['title'], $post['alt'], $post['desc'], $post['price'], $post['img_large'], $post['img_small']);
        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

    public function actionDelete()
    {
        if ($post = $this->checkPost()) {
            App::call()->productRep->deleteProduct($post['id']);
        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

    public function actionCreate()
    {
        if ($post = $this->checkPost()) {
            App::call()->productRep->createProduct($post['title'], $post['alt'], $post['desc'], $post['price'], $post['img_large'], $post['img_small']);
        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

    private function checkPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post['id'] = $_POST['product_id'];
            $post['title'] = $_POST['product_title'];
            $post['alt'] = $_POST['product_alt'];
            $post['desc'] = $_POST['product_desc'];
            $post['price'] = $_POST['product_price'];
            $post['img_large'] = $_POST['product_img_large'];
            $post['img_small'] = $_POST['product_img_small'];
            if ($post['title'] && $post['desc'] && $post['alt'] && $post['price']) {
                if (!$post['img_large']) $post['img_large'] = App::call()->getConfig('img_large_placeholder');
                if (!$post['img_small']) $post['img_small'] = App::call()->getConfig('img_small_placeholder');
            }
            return $post;
        }
        return false;
    }
}