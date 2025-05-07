<?php
// GET all users
Flight::route('GET /users', function() {
    Flight::json(Flight::userService()->getAllUsers());
});

// GET single user
Flight::route('GET /users/@id', function($id) {
    Flight::json(Flight::userService()->getUserById($id));
});

// POST create user
Flight::route('POST /users', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->createUser($data));
});

// PUT update user
Flight::route('PUT /users/@id', function($id) {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::userService()->updateUser($id, $data));
});

// DELETE user
Flight::route('DELETE /users/@id', function($id) {
    Flight::userService()->deleteUser($id);
    Flight::json(["message" => "User deleted"]);
});