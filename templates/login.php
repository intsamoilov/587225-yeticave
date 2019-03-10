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
    <form class="form container <?php if(count($errors)) echo'form--invalid';?>"
          action="login.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Вход</h2>
        <div class="form__item <?php if($errors['email']) echo'form__item--invalid';?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <?php $value = isset($login_data['email']) ? $login_data['email'] : '';?>
            <input id="email" type="text" name="email" placeholder="Введите e-mail"
                   value="<?=htmlspecialchars($value);?>">
            <span class="form__error">Введите e-mail</span>
        </div>
        <div class="form__item form__item--last <?php if($errors['password']) echo'form__item--invalid';?>">
            <label for="password">Пароль*</label>
            <?php $value = isset($login_data['password']) ? $login_data['password'] : '';?>
            <input id="password" type="password" name="password" placeholder="Введите пароль"
                   value="<?=htmlspecialchars($value);?>">
            <span class="form__error">Введите пароль</span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <ul>
            <?php if(count($errors)): ?>
                <?php foreach($errors as $err => $val):?>
                    <li><?=$dict[$err];?> : <?=$val?></li>
                <?php endforeach;?>
            <?php endif;?>
        </ul>
        <button type="submit" class="button">Войти</button>
    </form>
</main>