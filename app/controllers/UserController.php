<?php

class UserController
{

    public function actionIndex($pageNum = 1) {

        $page = Page::go();
        $page->title = "Користувачі";

        $users_arr = User::viewAll($pageNum);
        $users_count = intval(User::count());
        $paging = new Pagination([
            'totalItems' => $users_count,
            'itemsPerPage' => 10,
            'currentPage' => intval($pageNum),
            'url' => '/user',
            'recountPages' => true,
        ]);
        $page->users = $users_arr;
        $page->paging = $paging;
        include APP . '/views/user/index.php';

        return true;
    }

    public function actionAdd() {

        $page = Page::go();
        $page->title = "Додати користувача";

        $errors = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if(isset($_POST['name'])) {
                $name = htmlspecialchars($_POST['name']);
                $page->name = $name;
            } else {
                $errors[] = "Вкажіть ім'я користувача";
            }

            if(isset($_POST['email'])) {
                $email = htmlspecialchars($_POST['email']);
                $page->email = $email;
            } else {
                $errors[] = "Вкажіть e-mail";
            }

            if(isset($_POST['country_id'])) {
                $country_id = htmlspecialchars($_POST['country_id']);
                $page->country_id = $country_id;
            } else {
                $errors[] = "Виберіть країну";
            }

            if($errors === false) {
                $result = User::add($name, $email, $country_id);
                if(is_array($result)) {
                    $page->errors = $result;
                } else {
                    header('Location: /user');
                }
            } else {
                $page->errors = $errors;
            }

        }

        $countries = Country::viewAll();
        $page->countries = $countries;
        include APP . '/views/user/add.php';

        return true;
    }

    public function actionEdit($id) {
        $page = Page::go();
        $page->title = "Редагування користувача";

        $errors = false;

        $userData = User::view($id);
        if(is_array($userData)) {
            $name = $userData['name'];
            $email = $userData['email'];
            $country_id = $userData['country_id'];
            $country = $userData['country'];

            $page->name = $name;
            $page->email = $email;
            $page->country_id = $country_id;
        } else {
            $errors[] = "Такого користувача не існує";
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if(isset($_POST['name'])) {
                $name = htmlspecialchars($_POST['name']);
                $page->name = $name;
            } else {
                $errors[] = "Вкажіть ім'я користувача";
            }

            if(isset($_POST['email'])) {
                $email = htmlspecialchars($_POST['email']);
                $page->email = $email;
            } else {
                $errors[] = "Вкажіть e-mail";
            }

            if(isset($_POST['country_id'])) {
                $country_id = htmlspecialchars($_POST['country_id']);
                $page->country_id = $country_id;
            } else {
                $errors[] = "Виберіть країну";
            }

            if($errors === false) {
                $result = User::edit($id, $name, $email, $country_id);
                if(is_array($result)) {
                    var_dump($result);
                    $page->errors = $result;
                } else {
                    header('Location: /user');
                }
            } else {
                $page->errors = $errors;
            }

        }

        $page->errors = $errors;

        $countries = Country::viewAll();
        $page->countries = $countries;
        include APP . '/views/user/edit.php';

        return true;
    }

    public function actionDelete($id) {
        User::delete($id);
        header('Location: /user');

        return true;
    }

}