<?php
$page = Page::go();
$page->header();
?>
<div class="table">
    <table>
        <h1>Список користувачів</h1>
        <thead>
        <th>ID</th>
        <th>Ім'я</th>
        <th>E-mail</th>
        <th>Країна</th>
        <th></th>
        </thead>
        <tbody>
        <?php foreach ($page->users as $user) {?>
        <tr>
            <td><?=$user['id']?></td>
            <td><?=$user['name']?></td>
            <td><?=$user['email']?></td>
            <td><?=$user['country']?></td>
            <td>
                <a href="/user/edit/<?=$user['id']?>">Редагуати</a>
                <a href="/user/delete/<?=$user['id']?>">Видалити</a>
            </td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3"><a href="/user/add">Додати користувача</a></td>
            <td><a href="/country">Країни</a></td>
        </tr>
        </tbody>
    </table>
</div>
<?php
if($page->paging instanceof Pagination) {
    $page->paging->render();
} else {
    echo $page->paging;
}
$page->footer();
