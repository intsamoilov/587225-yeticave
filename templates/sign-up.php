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
    <form class="form container <?php if(count($errors)) echo('form--invalid');?>"
          action="sign.php" method="post" enctype="multipart/form-data">
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item <?php if($errors['email']) echo('form__item--invalid');?>">
            <label for="email">E-mail*</label>
            <?php $value = isset($user['email']) ? $user['email'] : '';?>
            <input id="email" type="text" name="email" placeholder="Введите e-mail"
                   value="<?=htmlspecialchars($value);?>">
            <span class="form__error">Введите e-mail</span>
        </div>
        <div class="form__item <?php if($errors['password']) echo('form__item--invalid');?>">
            <label for="password">Пароль*</label>
            <?php $value = isset($user['password']) ? $user['password'] : '';?>
            <input id="password" type="password" name="password" placeholder="Введите пароль"
                   value="<?=htmlspecialchars($value);?>">
            <span class="form__error">Введите пароль</span>
        </div>
        <div class="form__item <?php if($errors['name']) echo('form__item--invalid');?>">
            <label for="name">Имя*</label>
            <?php $value = isset($user['name']) ? $user['name'] : '';?>
            <input id="name" type="text" name="name" placeholder="Введите имя"
                   value="<?=htmlspecialchars($value);?>">
            <span class="form__error">Введите имя</span>
        </div>
        <div class="form__item <?php if($errors['message']) echo('form__item--invalid');?>">
            <label for="message">Контактные данные*</label>
            <?php $value = isset($user['message']) ? $user['message'] : '';?>
            <textarea id="message" name="message"
                      placeholder="Напишите как с вами связаться"><?=htmlspecialchars($value);?></textarea>
            <span class="form__error">Напишите как с вами связаться</span>
        </div>
        <div class="form__item form__item--file form__item--last">
            <label>Аватар</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="img/avatar.jpg" width="113" height="113" alt="Ваш аватар">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" name="avatar" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
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
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>