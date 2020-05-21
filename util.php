<?php
require_once 'helpers.php';
require_once 'db.php';

function connect_db ($params) {
    $con = mysqli_connect($params['host'], $params['user'], $params['password'], $params['db_name']);

    mysqli_set_charset($con, 'utf8');

    if (!$con) {
        die('Ошибка подключения: ' . mysqli_connect_error());
    }

    return $con;
}

/*функция, проверяющая наличие значения в массиве по ключу*/
function isValueInArray($array, $key, $value)
{
    foreach ($array as $val) {
        if (isset($val[$key]) && $val[$key] == $value) {
            return true;
        }
    }
    return false;
}

/*функция, возвращающая значение массива по ключу при его наличии*/
function getValue ($array, $key)
{
    if (isset($array[$key])) {
        return $array[$key];
    }
}

/*функция, возвращающая количество задач в проекте*/
function countProjectTasks(array $task_list, $project_id)
{
    $count = 0;
    foreach ($task_list as $task) {
        if (isset($task['project_id']) && $task['project_id'] === $project_id) {
            $count++;
        }
    }
    return $count;
}

/*функция, возвращающая массив проектов для конкретного пользователя*/
function getProjects($con, int $user_id)
{
    $sql = 'SELECT * FROM projects WHERE user_id = ?';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $projects = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $projects;
}

/*функция, возвращающая массив всех задач для конкретного пользователя*/
function getTasksAll($con, int $user_id)
{
    $sql = 'SELECT * FROM tasks WHERE user_id = ?';
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $user_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $tasksAll = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return $tasksAll;
}

/*объявление переменных*/
$con = connect_db($params);
$user_id = 1;
$projects = getProjects($con, $user_id);
$tasksAll = array_reverse(getTasksAll($con, $user_id));