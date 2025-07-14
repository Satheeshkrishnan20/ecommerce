<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\components\assets\AppAsset;

AppAsset::register($this);
// $this->registerJsFile('@web/js/site.js', ['depends' => [\yii\web\JqueryAsset::class]]);



$this->beginPage();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->head() ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= Html::csrfMetaTags() ?>
    <title>Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <style>
    /* ... (existing flash message and error styles) ... */
  
    </style>
</head>
<body>
<?php $this->beginBody() ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <a class="navbar-brand" href="home">Logo</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav d-flex gap-4 flex-row align-items-center">
                <li class="nav-item">
                    <?= Html::a('Products', Url::to(['home/home']), ['class' => 'nav-link text-white px-2']) ?>
                </li>
                <li class="nav-item">
                    <?php
                    $session = Yii::$app->session;
                    $cartItemCount = $session->get('cart_item_count', 0); // Get count from session, default to 0
                    ?>
                    <?= Html::a(
                        'Cart ' . ($cartItemCount > 0 ? '<span class="badge bg-danger rounded-pill">' . $cartItemCount . '</span>' : ''),
                        ['/cart/cart'],
                        ['class' => 'nav-link text-white px-2 cart-link-with-badge'] // Add a unique class here
                    ) ?>
                </li>
                <li class="nav-item">
                    <?= Html::a('Wishlist', ['/wishlist/wishlist'], ['class' => 'nav-link text-white px-2']) ?>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                        // $username=false;
                     

                            $username = $session->get('username');
                       
                        ?>
                        <span class="navbar-text me-2">
                            <?= $username ? "Hello, " . Html::encode($username) : "Hi user" ?>
                        </span>
                        <i class="bi bi-person-circle fs-5"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li>
                            <div class="px-3 py-3" style="min-width: 220px;">
                                <div class="text-center mb-2">
                                    <i class="bi bi-question-circle-fill text-primary fs-4"></i>
                                    <h6 class="mt-2 mb-1">Are You Sure?</h6>
                                    <p class="text-muted small mb-2">Select how you'd like to proceed.</p>
                                </div>
                                <div class="d-grid gap-2">
                                    <?php if ($username): ?>
                                        <?= Html::beginForm(['/home/logout'], 'post') ?>
                                        <?= Html::submitButton('Logout', ['class' => 'btn btn-danger btn-sm w-100']) ?>
                                        <?= Html::endForm() ?>
                                    <?php else: ?>
                                        <a href="<?= Url::to(['login']) ?>" class="btn btn-outline-dark btn-sm w-100">Login</a>
                                        <a href="<?= Url::to(['signup']) ?>" class="btn btn-primary btn-sm w-100">Sign Up</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="flash-messages-container">
    <?php
    foreach (Yii::$app->session->getAllFlashes() as $type => $message):
        $alertClass = 'alert-secondary';
        switch ($type) {
            case 'success': $alertClass = 'alert-success'; break;
            case 'error': $alertClass = 'alert-danger'; break;
            case 'warning': $alertClass = 'alert-warning'; break;
            case 'info': $alertClass = 'alert-info'; break;
        }
    ?>
        <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
            <?= Html::encode($message) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endforeach; ?>
</div>

<div class=" mt-3">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>

<?php $this->endPage() ?>