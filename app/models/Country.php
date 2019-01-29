<?php

class Country extends Model
{

    public static function add($country) {
        $errors = false;

        if(!self::validateName($country)) {
            $errors[] = "Назва країни повинна містити від 3 до 50 символів українського чи англійського алфавіту";
        }

        if(self::existsName($country)) {
            $errors[] = "Така країна вже є у списку";
        }

        if($errors === false) {
            $result = self::_add($country);
            if($result) {
                return $result;
            } else {
                $errors[] = "Не вдалось додати країну";
            }
        }

        return $errors;
    }

    public static function  viewAll() {
        return self::_select();
    }

    public static function validateName($name)
    {
        return mb_strlen($name) >= 3
            && mb_strlen($name) <= 50
            && preg_match('~^([a-zA-Zа-яА-ЯіІїЇ ]+)$~u', $name);
    }

    private static function existsName($country, $id=0) {
        $result = false;

        $query = 'select id from countries where country=:country and not id=:id';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":country", $country, PDO::PARAM_STR);
            $st->bindValue(":id", $id, PDO::PARAM_INT);

            if($st->execute()) {
                if($res = $st->fetch(PDO::FETCH_ASSOC)) {
                    $result = ($res['id']>0);
                }
            }
        }

        return $result;
    }

    private static function _add($country) {
        $result = false;

        $query = 'insert into countries(country) 
                values(:country)';
        if ($st = self::$db->prepare($query)) {
            $st->bindValue(":country", $country, PDO::PARAM_STR);

            if($st->execute()) {
                $result = self::$db->lastinsertid();
            }
        }
        return $result;
    }

    private static function _select() {
        $result = false;

        $query = 'select id, country 
                  from countries
                  order by id';
        if ($st = self::$db->prepare($query)) {
            if($st->execute()) {
                $result = $st->fetchAll(PDO::FETCH_ASSOC);
            }
        }

        return $result;
    }

}