<?php

Flight::group('/contactus', function() {
    
    Flight::route('GET /', function() {
        Flight::json(Flight::contactUsService()->get_all());
    });

    Flight::route('GET /@id', function($id) {
        Flight::json(Flight::contactUsService()->get_by_id($id));
    });

    Flight::route('POST /', function() {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::contactUsService()->add($data));
    });

    Flight::route('PUT /@id', function($id) {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::contactUsService()->update($data, $id));
    });

    Flight::route('DELETE /@id', function($id) {
        Flight::json(Flight::contactUsService()->delete($id));
    });

});
