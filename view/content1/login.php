<div class="login">
    <h3>
        Log in to use My Content Database.
    </h3>

    <?php if ($loginMessage) : ?>
        <p class="info">
            <?= $loginMessage ?>
        </p>
    <?php endif ?>

    <form class="label-left" method="post">
            <label for="user">User</label>
            <input id="user" type="text" name="user">

            <label for="password">Password</label>
            <input id="password" type="password" name="password">

            <input type="submit" name="doit" value="Login">
    </form>

</div>
