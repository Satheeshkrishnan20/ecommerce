<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->head() ?>
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
    <?php $this->beginBody() ?>
    
    <?= $content ?>

    <?php $this->endBody() ?>
    <style>
        .has-error .help-block,
        .has-error .invalid-feedback {
            color: red;
            font-size: 0.9em;
        }
</style>
</body>
</html>
<?php $this->endPage() ?>
