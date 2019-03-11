<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach($categories as $category):?>
            <li class="nav__item">
                <a href="all-lots.php?id=<?=$category['id']?>"><?=htmlspecialchars($category['name']);?></a>
            </li>
            <?php endforeach;?>
        </ul>
    </nav>
    <form class="form form--add-lot container <?php if(count($errors)) echo'form--invalid';?>"
          action="add.php" method="post" enctype="multipart/form-data">
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item <?php if(isset($errors['lot-name'])) echo'form__item--invalid';?>">
                <label for="lot-name">Наименование</label>
                <?php $value = isset($lot['lot-name']) ? $lot['lot-name'] : '';?>
                <input id="lot-name" type="text" name="lot-name" value="<?=htmlspecialchars($value);?>"
                       placeholder="Введите наименование лота" <!--required-->
                <span class="form__error">Введите наименование лота</span>
            </div>
            <div class="form__item <?php if(isset($errors['category'])) echo'form__item--invalid';?>">
                <label for="category">Категория</label>
                <select id="category" name="category" <!--required-->
                    <option value="0">Выберите категорию</option>
                <?php foreach($categories as $category):?>
                    <option value="<?=$category['id'];?>"
                        <?=($category['id'] === $lot['category']) ? ' selected' : '';?>>
                        <?=htmlspecialchars($category['name']);?>
                    </option>
                <?php endforeach;?>
                </select>
                <span class="form__error">Выберите категорию</span>
            </div>
        </div>
        <div class="form__item form__item--wide <?php if(isset($errors['message'])) echo'form__item--invalid';?>">
            <label for="message">Описание</label>
            <?php $value = isset($lot['message']) ? $lot['message'] : '';?>
            <textarea id="message" name="message" placeholder="Напишите описание лота"
                <!--required--><?=htmlspecialchars($value);?></textarea>
            <span class="form__error">Напишите описание лота</span>
        </div>
        <div class="form__item form__item--file <?php if(isset($errors['lot-img'])) echo'form__item--invalid';?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" name="lot-img" type="file" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small <?php if(isset($errors['lot-rate'])) echo'form__item--invalid';?>">
                <label for="lot-rate">Начальная цена</label>
                <?php $value = isset($lot['lot-rate']) ? $lot['lot-rate'] : '';?>
                <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?=htmlspecialchars($value);?>" <!--required-->
                <span class="form__error">Введите начальную цену</span>
            </div>
            <div class="form__item form__item--small <?php if(isset($errors['lot-step'])) echo'form__item--invalid';?>">
                <label for="lot-step">Шаг ставки</label>
                <?php $value = isset($lot['lot-step']) ? $lot['lot-step'] : '';?>
                <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?=htmlspecialchars($value);?>" <!--required-->
                <span class="form__error">Введите шаг ставки</span>
            </div>
            <div class="form__item <?php if(isset($errors['lot-date'])) echo'form__item--invalid';?>">
                <label for="lot-date">Дата окончания торгов</label>
                <?php $value = isset($lot['lot-date']) ? $lot['lot-date'] : '';?>
                <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="<?=htmlspecialchars($value);?>" <!--required-->
                <span class="form__error">Введите дату завершения торгов</span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php if(count($errors)): ?>
                <?php foreach($errors as $err => $val):?>
                    <li><?=$dict[$err];?> : <?=$val?></li>
                <?php endforeach;?>
            <?php endif;?>
        </ul>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>