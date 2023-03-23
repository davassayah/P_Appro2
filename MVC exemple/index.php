<?php
include('./Autoloader.php');

Autoloader::register();

$router = new Router();

// IMPORTANT! Si besoin de rajouter une page alors rajouter une route comme la ligne en dessous qui contient 2 params
// Param 1 = url ex: /teachers ce qui donnerai comme url complete google.com/teachers aka localhost:3000/teachers
// Param 2 = {Le nom du controller}@{le nom de la method}
$router->setRoute('/teachers', 'TeacherController@GetAll');
$router->setRoute('/teachers', 'TeacherController@DeleteById');
$router->setRoute('/teacher',  'TeacherController@GetById');
$router->setRoute('/createTeacher',  'TeacherController@Create');
$router->setRoute('/updateTeacher',  'TeacherController@UpdateById');

$router->notFound('Views/Specials/404.php');