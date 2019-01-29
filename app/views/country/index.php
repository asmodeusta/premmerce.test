<?php
$page = Page::go();
$page->header();
?>
    <div class="table">
        <table>
            <h1>Країни</h1>
            <thead>
            <th>ID</th>
            <th>Країна</th>
            </thead>
            <tbody>
            <?php foreach ($page->countries as $country) {?>
                <tr>
                    <td><?=$country['id']?></td>
                    <td><?=$country['country']?></td>
                </tr>
            <?php } ?>
            <tr>
                <td><a href="/country/add">Додати</a></td>
                <td><a href="/user">Користувачі</a></td>
            </tr>
            </tbody>
        </table>
    </div>
<?php
$page->footer();