<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach($categories as $category):?>
                <li class="nav__item <?php echo($category['id'] == $category_id) ? 'nav__item--current' : ''?>">
                    <a href="all-lots.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name']);?></a>
                </li>
            <?php endforeach;?>
        </ul>
    </nav>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($rates as $rate):?>
            <tr class="rates__item <?=($rate['winner']) ? 'rates__item--win':''?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?=$rate['image']?>" width="54" height="40"
                             alt="<?=htmlspecialchars($rate['name'])?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.php?id=<?=$rate['id']?>">
                                <?=htmlspecialchars($rate['name'])?></a></h3>
                        <?php if($rate['winner']):?>
                            <p><?=htmlspecialchars($rate['contact'])?></p>
                        <?php endif;?>
                    </div>
                </td>
                <td class="rates__category">
                    <?=htmlspecialchars($rate['category'])?>
                </td>
                <td class="rates__timer">
                    <div class="timer
                        <?=($rate['winner']) ? ' timer--win':''?>
                        <?=($rate['finishing']) ? ' timer--finishing':''?>
                        <?=($rate['end']) ? ' timer--end' : ''?>">

                        <?=$rate['winner'] ? 'Вы выиграли' : ''?>
                        <?=$rate['end'] ? 'Торги окончены' : ''?>
                        <?=$rate['end'] || $rate['winner'] ? '' : getRemainingTime($rate['date_end'])?>
                    </div>
                </td>
                <td class="rates__price">
                    <?=htmlspecialchars($rate['bid'])?>
                </td>
                <td class="rates__time">
                    <?=formatDate($rate['date'])?>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
    </section>
</main>