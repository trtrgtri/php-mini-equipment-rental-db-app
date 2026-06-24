<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title><?= e($title ?? 'Equipment DB App') ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>

<body>
    <nav class="navbar">
        <div class="brand">Equipment/Rental DB App</div>
        <a href="/">Dashboard</a>
        <a href="/equipments">Equipments</a>
        <a href="/equipments/create">Create Equipment</a>
        <a href="/rentals">Rentals</a>
        <a href="/rentals/create">Create Rental</a>
        <a href="/health">Health</a>
    </nav>
    <main class="container">
        <?php if ($success = flash_get('success')): ?>
            <div class="alert success" style="background: #f0fdf4; border-left: 4px solid #22c55e; color: #166534; padding: 12px 16px; margin-bottom: 24px;">
                <?= e($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error = flash_get('error')): ?>
            <div class="alert error" style="background: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 12px 16px; margin-bottom: 24px;">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>
</body>

</html>