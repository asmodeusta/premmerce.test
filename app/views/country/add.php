<?php
$page = Page::go();
$page->header();
?>
    <form action="" class="form" method="post">
        <div class="title"><h1>Додати країну</h1></div>
        <?php if(is_array($page->errors)) {?>
        <ul class="errors">
            <?php foreach ($page->errors as $error) {?>
            <li><?=$error?></li>
            <?php }?>
        </ul>
        <?php } ?>
        <input type="text" name="country" placeholder="Назва" value="<?=$page->country?>">
        <input type="submit" value="Додати">
    </form>
<?php
$page->footer();
