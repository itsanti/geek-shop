<?php

namespace MyApp\controllers;


class CategoryController extends Controller
{
    public function actionCreate()
    {
        if ($post = $this->checkPost()) {
            App::call()->categoryRep->createCategory($post['title'], $post['ancestor_id']);
        }
        $referer = App::call()->request->getRefererAction($_SERVER['HTTP_REFERER']);
        $this->redirect($referer);
    }

    private function checkPost()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post['title'] = $_POST['title'];
            $post['ancestor_id'] = $_POST['ancestor_id'];
            if ($post['title'] && $post['ancestor_id']) {
                return $post;
            }
        }
        return false;
    }
}