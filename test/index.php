<?php

require_once dirname(__DIR__) . '/src/SimpleTemplate.php';
$tpl = new SimpleTemplate(__DIR__ . '/index.html');
$tpl->assign('data', [
    [
        'name' => '<script>alert("hello world");</script>',
        'age'  => 12,
    ],
    [
        'name' => '<p>What?</p>',
        'age'  => 23,
    ],
]);
$tpl->render();