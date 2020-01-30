<?php include('_partials/header.php'); ?>

<form action="registerHandle.php" method="post">
    <div class="form-group">
        <label for="">Pseudonyme</label>
        <input type="text" class="form-control" name="username">
    </div>
    <div class="form-group">
        <label for="">E-mail</label>
        <input type="text" class="form-control" name="email">
    </div>
    <div class="form-group">
        <label for="">Mot de passe</label>
        <input type="text" class="form-control" name="password">
    </div>
</form>

<?php include('_partials/footer.php'); ?>