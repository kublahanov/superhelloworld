<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Используем микрофреймворк Silex
$app = new Silex\Application();

$app['debug'] = true;

// И шаблонизатор Twig, который легко интегрируется в Silex
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => arraY(__DIR__ . '/../views')
));

// Функция get_ip() определена в библиотеке super_lib.
// Благодаря сгенерированному autoloader.php нужный файл библиотеки подключится автоматически
$ip = get_ip();

// При заходе в корень нашего сайта, сработает контроллер описанный в анонимной функции ниже
$app->get('/', function() use ($app, $ip) {

    // TimerClass определён в библиотеке super_lib.
    // Благодаря сгенерированному autoloader.php нужный файл библиотеки подключится автоматически
    $timer = new TimerClass();

    $templateVars = array(
        'msg' => 'Super Hello World',
        'time' => $timer->getCurrentTime(),
        'ip' => $ip
    );

    // Рендерим шаблон и выводим его в браузер пользователя
    return $app['twig']->render('layout.twig', $templateVars);

});

$app->finish(function() use ($ip) {

    // Класс MyCompanyNamespace\SuperLogger определён в Composer-пакете mycompany/superlogger
    // Благодаря сгенерированному autoloader.php нужный файл с описанием класса подключится автоматически
    $logger = new MyCompanyNamespace\SuperLogger();
    $logger->writeLog($_SERVER['DOCUMENT_ROOT'] . '/logs/log.txt', 'Someone visited the page: ' . $ip);

});

$app->run();
