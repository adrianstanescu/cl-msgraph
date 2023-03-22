<?php
list($path) = explode('?', $_SERVER['REQUEST_URI'], 2);
$page = str_replace('/', '-', substr($path, 1));

if ($page !== '' && !file_exists(__DIR__ . '/tests/' . $page . '.php')) {
    return false;
}
$menuItems = array_map(function ($item) {
    return substr($item, 0, -4);
}, array_filter(scandir(__DIR__ . '/tests'), function ($item) {
    return substr($item, -4) === '.php';
}));

require_once __DIR__ . '/../vendor/autoload.php';

putenv('DEBUG=yes');

if (file_exists(__DIR__ . '/../phpunit.xml')) {
    $phpunitconfig = new SimpleXMLElement(file_get_contents(__DIR__ . '/../phpunit.xml'));
    foreach($phpunitconfig->php->env as $env) {
        if (getenv($env['name']) !== false) {
            continue;
        }
        putenv("{$env['name']}={$env['value']}");
    }
}

use Adrian\CLMSGraph\Graph;

Graph::configure(getenv('AZURE_CLIENT_ID'), getenv('AZURE_CLIENT_SECRET'), getenv('AZURE_TENANT_ID'));
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>CLMSGraph demo</title>
        <link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.classless.min.css">
        <style>
            button.small {
                padding: calc(var(--form-element-spacing-vertical) / 2) calc(var(--form-element-spacing-horizontal) / 2);
            }
            button.inline {
                width: auto;
            }
            td button {
                margin-bottom: 0;
            }
            * {
                --block-spacing-vertical: 1rem !important;
            }
        </style>
    </head>
    <body>
        <header>
            <nav>
                <ul>
                    <li><a href="/">CLMSGraph demo</a></li>
                    <?php foreach($menuItems as $item): ?>
                        <li><a href="/<?= $item ?>"<?= $item === $page ? 'role="button"' : '' ?>><?= ucfirst($item) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </header>
        <main>
            <section>
                <h2><?= ucfirst($page) ?></h2>
                <?php $page === '' ? include(__DIR__ . '/index.php') : include __DIR__ . '/tests/' . $page . '.php'; ?>
            </section>
        </main>
    </body>
</html>