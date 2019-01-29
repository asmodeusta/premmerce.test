<?php

class CountryController
{

    public function actionIndex() {

        $page = Page::go();
        $page->title = "Країни";

        $countriesArr = Country::viewAll();

        $page->countries = $countriesArr;
        include APP . '/views/country/index.php';

        return true;
    }

    public function actionAdd() {

        $page = Page::go();
        $page->title = "Додати країну";

        $errors = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if(isset($_POST['country'])) {
                $country = htmlspecialchars($_POST['country']);
                $page->country = $country;
            } else {
                $errors[] = "Вкажіть назву країни";
            }

            if($errors === false) {
                $result = Country::add($country);
                if(is_array($result)) {
                    $page->errors = $result;
                } else {
                    header('Location: /country');
                }
            } else {
                $page->errors = $errors;
            }

        }

        include APP . '/views/country/add.php';

        return true;
    }

}