<?php ob_start(); ?><h1>405 Method Not Allowed</h1>
<p>Sai phương thức HTTP.</p><?php $content = ob_get_clean();
                            require __DIR__ . '/../layout.php'; ?>