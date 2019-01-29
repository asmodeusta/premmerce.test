<?php

class User extends Model
{

    public static function add($name, $email, $country_id) {
        $errors = false;

        if(!self::validateName($name)) {
            $errors[] = "Ім'я повинно містити від 3 до 50 символів українського чи англійського алфавіту";
        }

        if(self::existsName($name)) {
            $errors[] = "Таке ім'я вже зайняте іншим користувачем";
        }

        if(!self::validateEmail($email)) {
            $errors[] = "Неправильние формат e-mail";
        }

        if(self::existsEmail($email)) {
            $errors[] = "Такий e-mail вже зайнятий іншим користувачем";
        }

        if(!self::validateCountry($country_id)) {
            $errors[] = "Виберіть країну зі списку";
        }

        if($errors === false) {
            $result = self::_add($name, $email, $country_id);
            if($result) {
                return $result;
            } else {
                $errors[] = "Не вдалось додати користуача";
            }
        }

        return $errors;
    }

    public static function delete($id) {
        return self::_delete($id);
    }

    public static function edit($id, $name, $email, $country_id) {
        $errors = false;

        if(!self::exists($id)) {
            $errors[] = "Такого користувача не існує";
        }

        if(!self::validateName($name)) {
            $errors[] = "Ім'я повинно містити від 3 до 50 символів українського чи англійського алфавіту";
        }

        if(self::existsName($name, $id)) {
            $errors[] = "Таке ім'я вже зайняте іншим користувачем";
        }

        if(!self::validateEmail($email)) {
            $errors[] = "Неправильние формат e-mail";
        }

        if(self::existsEmail($email, $id)) {
            $errors[] = "Такий e-mail вже зайнятий іншим користувачем";
        }

        if(!self::validateCountry($country_id)) {
            $errors[] = "Виберіть країну зі списку";
        }

        if($errors === false) {
            $result = self::_edit($id, $name, $email, $country_id);
            if($result) {
                return $result;
            } else {
                $errors[] = "Не вдалось записати зміни";
            }
        }

        return $errors;
    }

    public static function  viewAll($page) {
        $limit = 10;
        $offset = $limit*($page-1);
        $result = self::_select($limit, $offset);

        return $result;
    }

    public static function validateEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function validateName($name)
    {
        return mb_strlen($name) >= 3
            && mb_strlen($name) <= 50
            && preg_match('~^([a-zA-Zа-яА-ЯіІїЇ ]+)$~u', $name);
    }

    public static function validateCountry($country_id) {
        return true;
    }

    private static function exists($id) {
        $result = false;

        $query = 'select id from users where id=:id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":id", $id, PDO::PARAM_INT);

            if($st->execute()) {
                if($res = $st->fetch(PDO::FETCH_ASSOC)) {
                    $result = ($res['id']>0);
                }
            }
        }

        return $result;
    }

    public static function count() {
        $result = 0;

        $query = 'select count(id) as id from users';
        if ($st = self::$db->prepare($query)) {
            if($st->execute()) {
                if($res = $st->fetch(PDO::FETCH_ASSOC)) {
                    $result = $res['id'];
                }
            }
        }
        return $result;
    }

    private static function existsName($name, $id=0) {
        $result = false;

        $query = 'select id from users where name=:name and not id=:id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":name", $name, PDO::PARAM_STR);
            $st->bindValue(":id", $id, PDO::PARAM_INT);

            if($st->execute()) {
                if($res = $st->fetch(PDO::FETCH_ASSOC)) {
                    $result = ($res['id']>0);
                }
            }
        }

        return $result;
    }

    private static function existsEmail($email, $id=0) {
        $result = false;

        $query = 'select id from users where email=:email and not id=:id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":email", $email, PDO::PARAM_STR);
            $st->bindValue(":id", $id, PDO::PARAM_INT);

            if($st->execute()) {
                if($res = $st->fetch(PDO::FETCH_ASSOC)) {
                    $result = ($res['id']>0);
                }
            }
        }

        return $result;
    }

    public static function view($id) {
        $result = false;

        $query = 'select users.id,
                    users.name,
                    users.email, 
                    users.country_id, 
                    countries.country 
                  from users 
                  left join countries 
                    on users.country_id = countries.id
                  where users.id = :id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":id", $id, PDO::PARAM_INT);

            if($st->execute()) {
                $result = $st->fetch(PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }

    private static function _select($limit, $offset) {
        $result = false;

        $query = 'select users.id,
                    users.name,
                    users.email, 
                    users.country_id, 
                    countries.country 
                  from users 
                  left join countries 
                    on users.country_id = countries.id
                  order by users.id
                  LIMIT :limit OFFSET :offset';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":limit", $limit, PDO::PARAM_INT);
            $st->bindValue(":offset", $offset, PDO::PARAM_INT);

            if($st->execute()) {
                $result = $st->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }

    private static function _add($name, $email, $country_id) {
        $result = false;

        $query = 'insert into users(name, email, country_id) 
                values(:name, :email, :country_id)';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":name", $name, PDO::PARAM_STR);
            $st->bindValue(":email", $email, PDO::PARAM_STR);
            $st->bindValue(":country_id", $country_id, PDO::PARAM_INT);

            if($st->execute()) {
                $result = self::$db->lastinsertid();
            }
        }
        return $result;
    }

    private static function _delete($id) {
        $result = false;

        $query = 'delete from users 
                  where id = :id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $result = $st->execute();
        }
        return $result;
    }

    private static function _edit($id, $name, $email, $country_id) {
        $result = false;

        $query = 'update users 
                  set name = :name,
                  email = :email,
                  country_id = :country_id
                  where id = :id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":id", $id, PDO::PARAM_INT);
            $st->bindValue(":name", $name, PDO::PARAM_STR);
            $st->bindValue(":email", $email, PDO::PARAM_STR);
            $st->bindValue(":country_id", $country_id, PDO::PARAM_INT);

            $result = $st->execute();
        }
        return $result;
    }

}