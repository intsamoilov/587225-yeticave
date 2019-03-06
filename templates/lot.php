<nav class="nav">
    <ul class="nav__list container">
        <?php foreach($categories as $category):?>
        <li class="nav__item">
            <a href="all-lots.html"><?=htmlspecialchars($category['name']);?></a>
        </li>
        <?php endforeach;?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?=htmlspecialchars($lot['title']);?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src=<?=$lot['url'];?> width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?=htmlspecialchars($lot['category']);?></span></p>
            <p class="lot-item__description"><?=htmlspecialchars($lot['description']);?></p>
        </div>
        <div class="lot-item__right">
            <?php if($is_show): ?>
            <div class="lot-item__state">
                <div class="lot-item__timer timer">
                    <?=getRemainingTime($lot['date_end']);?>
                </div>
                <div class="lot-item__cost-state">
                    <div class="lot-item__rate">
                        <span class="lot-item__amount">Текущая цена</span>
                        <span class="lot-item__cost"><?=htmlspecialchars($lot['price']);?></span>
                    </div>
                    <div class="lot-item__min-cost">
                        Мин. ставка <span><?=htmlspecialchars($lot['price'] + $lot['bet_step']);?></span>
                    </div>
                </div>
                <form class="lot-item__form <?php if(count($errors)) echo('form--invalid');?>"
                      action="lot.php?id=<?=$lot['id'];?>"
                      method="post" enctype="multipart/form-data">
                    <p class="lot-item__form-item form__item
                        <?php if($errors['cost']) echo('form__item--invalid');?>">
                        <?php $value = isset($bet['cost']) ? $bet['cost'] : '';?>
                        <label for="cost">Ваша ставка</label>
                        <input id="cost" type="text" name="cost" placeholder="12 000" value="<?=$value;?>">
                        <?php if(count($errors)): ?>
                            <?php foreach($errors as $err => $val):?>
                                <span class="form__error"><?=$dict[$err];?> : <?=$val?></span>
                            <?php endforeach;?>
                        <?php endif;?>
                    </p>
                    <button type="submit" class="button">Сделать ставку</button>
                </form>
            </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?=count($bets_list);?></span>)</h3>
                <table class="history__list">
                    <?php if($bets_list):?>
                        <?php foreach ($bets_list as $bet):?>
                            <tr class="history__item">
                                <td class="history__name"><?=htmlspecialchars($bet['name'])?></td>
                                <td class="history__price"><?=htmlspecialchars($bet['bid'])?></td>
                                <td class="history__time"><?=formatDate($bet['date'])?></td>
                            </tr>
                        <?php endforeach;?>
                    <?php endif;?>
                </table>
            </div>
        </div>
    </div>
</section>