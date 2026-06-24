<?php ob_start(); ?><h1>404 Not Found</h1>
<p>Trang không tồn tại.</p><?php $content = ob_get_clean();
                            require __DIR__ . '/../layout.php'; ?>