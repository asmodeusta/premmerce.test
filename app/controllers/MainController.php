<?php

class MainController
{

    public function actionIndex() {
        header('Location: /user');

        return true;
    }

}