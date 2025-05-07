<?php

Flight::group('/destinations', function() {
    
    Flight::route('GET /', function() {
        Flight::json(Flight::destinationsService()->get_all());
    });

    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::destinationsService()->get_by_id($id));
    });

    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::destinationsService()->add($data));
    });

    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::destinationsService()->update($data, $id));
    });

    Flight::route('DELETE /@id', function($id) {
        Flight::json(Flight::destinationsService()->delete($id));
    });

});
