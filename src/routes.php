<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

$app->group('', 
	function (Slim\App $group) {
	 $group->get('/contacts', 'ContactController:getAllContacts');
	 $group->post("/contacts", 'ContactController:createContact');
	 $group->delete("/contacts/{id}", 'ContactController:deleteContact');
	 $group->put("/contacts/{id}", 'ContactController:updateContact');
	 $group->get("/contacts/{id}", 'ContactController:getContactsById');
}
);

$app->group('', 
	function (Slim\App $group) {
	 $group->post('/activities/{contactID}', 'ActivityController:create');
	 $group->put("/activities/{id}", 'ActivityController:updateActivity');
	 $group->delete("/activities/{id}", 'ActivityController:deleteActivity');
	 $group->get("/activities/{contactID}", 'ActivityController:getAllActivity');
}
);

$app->group('', 
	function (Slim\App $group) {
	 $group->post('/users', 'FmUserController:createUser');
	 $group->post("/users/login", 'FmUserController:login');
}
);





   

   
   
    
   
   
   
