<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach($categories as $category):?>
            <li class="nav__item">
                <a href="all-lots.html"><?=htmlspecialchars($category['name']);?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?=htmlspecialchars($search_str);?></span>»</h2>
            <?php $message ? print($message) : ''; ?>
            <ul class="lots__list">
                <?php foreach($lots as $lot):?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src=<?=$lot['url'];?> width="350" height="260"
                                 alt="<?=htmlspecialchars($lot['title']);?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?=htmlspecialchars($lot['category']);?></span>
                            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot['id'];?>">
                                    <?=htmlspecialchars($lot['title']);?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?=formatPrice($lot['price']);?></span>
                                </div>
                                <div class="lot__timer timer">
                                    <?=getRemainingTime($lot['date_end']);?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach;?>
            </ul>
        </section>
        <?php if($total_pages > 1): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev">
                <a href="<?php ($page_number == 1) ? print('#')
                    : print("search.php?page=". ($page_number - 1) . "&search=" . $search_str);?>">
                    Назад</a>
            </li>
            <?php foreach ($pages as $page): ?>
                <li class="pagination-item
                    <?php if($page == $page_number) print(' pagination-item-active');?>">
                    <a href="search.php?page=<?=$page?>&search=<?=$search_str?>"><?=$page?></a></li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next">
                <a href="<?php ($page_number == count($pages)) ? print('#')
                    : print("search.php?page=". ($page_number + 1) . "&search=" . $search_str);?>">
                    Вперед</a>
            </li>
        </ul>
        <?php endif; ?>
    </div>
</main>