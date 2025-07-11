<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->beginPage();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->head(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title>Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <style>
    .flash-messages-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        max-width: 350px;
        width: 100%;
        padding: 0 10px;
        box-sizing: border-box;
    }
    @media (min-width: 576px) {
        .flash-messages-container {
            width: auto;
            padding: 0;
        }
    }
    .flash-messages-container .alert {
        margin-bottom: 10px;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
    }
    .has-error .help-block,
    .has-error .invalid-feedback {
        color: red;
        font-size: 0.9em;
    }
    </style>
</head>
<body>
<?php $this->beginBody(); ?>

<?php
$user = Yii::$app->user->identity;
$usertype = $user?->usertype;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><label>Logo</label></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <?php if ($usertype == 3 || $user?->hasPermission('access_dashboard')): ?>
                    <li class="nav-item">
                        <?= Html::a('Dashboard', ['/admin/default/dashboard'], ['class' => 'nav-link text-white']) ?>
                    </li>
                <?php endif; ?>

                <?php if ($usertype == 3 || $user?->hasPermission('access_admin') || $user?->hasPermission('access_customer')): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">User Master</a>
                        <ul class="dropdown-menu">
                            <?php if ($usertype == 3 || $user?->hasPermission('access_admin')): ?>
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/admin/admin']) ?>">Admin</a></li>
                            <?php endif; ?>
                            <?php if ($usertype == 3 || $user?->hasPermission('access_customer')): ?>
                                <li><a class="dropdown-item" href="<?= Url::to(['/admin/user/user']) ?>">Customer</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if ($usertype == 3 || $user?->hasPermission('access_category')): ?>
                    <li class="nav-item">
                        <?= Html::a('Category', ['/admin/category/category'], ['class' => 'nav-link text-white']) ?>
                    </li>
                <?php endif; ?>

                <?php if ($usertype == 3 || $user?->hasPermission('access_product')): ?>
                    <li class="nav-item">
                        <?= Html::a('Product', ['/admin/product/product'], ['class' => 'nav-link text-white']) ?>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div>
            <ul class="navbar-nav ms-lg-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle fs-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <?= Html::a('Logout', ['/admin/default/logout'], [
                                'class' => 'dropdown-item',
                                'data-method' => 'post',
                            ]) ?>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="flash-messages-container">
    <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
        <?php
        $alertClass = match($type) {
            'success' => 'alert-success',
            'error' => 'alert-danger',
            'warning' => 'alert-warning',
            'info' => 'alert-info',
            default => 'alert-secondary'
        };
        ?>
        <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= Html::encode($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class=""><?= $content ?></div>
    </div>
</div>

<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
