<?php

Flight::group('/packages', function() {

    Flight::route('GET /', function() {
        Flight::json(Flight::packagesService()->get_all());
    });

    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::packagesService()->get_by_id($id));
    });

    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::packagesService()->add($data));
    });

    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::packagesService()->update($data, $id));
    });

    Flight::route('DELETE /@id', function($id) {
        Flight::json(Flight::packagesService()->delete($id));
    });

});
