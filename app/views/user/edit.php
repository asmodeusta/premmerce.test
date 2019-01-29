<?php
$page = Page::go();
$page->header();
?>
    <form action="" class="form" method="post">
        <div class="title"><h1>Редагувати користувача</h1></div>
        <?php if(is_array($page->errors)) {?>
        <ul class="errors">
            <?php foreach ($page->errors as $error) {?>
            <li><?=$error?></li>
            <?php }?>
        </ul>
        <?php } ?>
        <input type="text" name="name" placeholder="Ім'я" value="<?=$page->name?>">
        <input type="email" name="email" placeholder="E-mail" value="<?=$page->email?>">
        <select name="country_id" id="country_id">
            <?php foreach ($page->countries as $country) {?>
            <option value="<?=$country['id']?>" <?php if($country['id']==$page->country_id){echo "selected";}?>><?=$country['country']?></option>
            <?php }?>
        </select>
        <input type="submit" value="Зберегти">
    </form>
<?php
$page->footer();
