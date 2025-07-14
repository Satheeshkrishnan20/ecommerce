<?php
use dosamigos\chartjs\ChartJs;


$user = Yii::$app->user->identity;
$usertype = $user?->usertype ?? null;
$showDashboard = $usertype == 3 || ($usertype == 2 && $user?->hasPermission('access_dashboard'));
?>

<?php if ($showDashboard): ?>
    <div class="d-flex justify-content-evenly align-content-between my-4">
        <div class="text-center border border-2 border-dark p-3 rounded px-5">
            <h3>Total Category</h3>
            <?= $cat ?>
        </div>
        <div class="text-center border border-2 border-dark p-3 rounded px-5">
            <h3>Total Product</h3>
            <?= $pro ?>
        </div>
    </div>

    <div class="d-flex justify-content-evenly flex-wrap">
        <div class="border p-3" style="width: 45%;">
            <?= ChartJs::widget([
                'type' => 'pie',
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'height' => 400,
                ],
                'data' => [
                    'labels' => array_column($chartData, 'category_name'),
                    'datasets' => [[
                        'label' => "Products",
                        'backgroundColor' => [
                            'rgba(177, 0, 38, 0.5)',
                            'rgba(13, 85, 133, 0.5)',
                            'rgba(255, 183, 0, 0.5)'
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        'borderWidth' => 1,
                        'data' => array_column($chartData, 'product_count')
                    ]]
                ]
            ]) ?>
        </div>

        <div class="border p-3" style="width: 45%;">
            <?= ChartJs::widget([
                'type' => 'bar',
                'options' => [
                    'responsive' => true,
                    'maintainAspectRatio' => false,
                    'height' => 400,
                    'scales' => [
                        'yAxes' => [[
                            'ticks' => [
                                'min' => 100,
                                'max' => 5000,
                                'stepSize' => 500,
                                'beginAtZero' => false
                            ]
                        ]]
                    ]
                ],
                'data' => [
                    'labels' => array_column($chartData, 'category_name'),
                    'datasets' => [[
                        'label' => "Products",
                        'backgroundColor' => [
                            'rgba(177, 0, 38, 0.5)',
                            'rgba(13, 85, 133, 0.5)',
                            'rgba(255, 183, 0, 0.5)'
                        ],
                        'borderColor' => [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        'borderWidth' => 1,
                        'data' => array_column($chartData, 'product_count'),
                    ]]
                ]
            ]) ?>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger text-center mt-5">
        <strong>Access Denied:</strong> You do not have permission to view the dashboard.
    </div>
<?php endif; ?>
