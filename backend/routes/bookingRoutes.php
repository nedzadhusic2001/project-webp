<?php

Flight::group('/bookings', function() {
    
    Flight::route('GET /', function() {
        Flight::json(Flight::bookingsService()->get_all());
    });

    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::bookingsService()->get_by_id($id));
    });

    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::bookingsService()->add($data));
    });

    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::bookingsService()->update($data, $id));
    });

    Flight::route('DELETE /@id', function($id) {
        Flight::json(Flight::bookingsService()->delete($id));
    });

});
