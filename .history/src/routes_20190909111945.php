<?php
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;




$app->group('/user/v1', function () use ($app) {
//API For CRUD Operations Of Contacts
$app->group('/contacts', 
	function (Slim\App $group) {
	 $group->get('', 'ContactController:getAllContacts');
	 $group->post("", 'ContactController:createContact');
	 $group->delete("/{id}", 'ContactController:deleteContact');
	 $group->put("/{id}", 'ContactController:updateContact');
	 $group->get("/{id}", 'ContactController:getContactsById');
	 $group->get("range", 'ContactController:getRecordOnRange');

}
);
//Api for CRUD Operation of Activities
$app->group('/activities', 
	function (Slim\App $group) {
	 $group->post('/{contactID}', 'ActivityController:create');
	 $group->put("/{id}", 'ActivityController:updateActivity');
	 $group->delete("/{id}", 'ActivityController:deleteActivity');
	 $group->get("/{contactID}", 'ActivityController:getAllActivity');
}
);
//API For Create and login User
$app->group('/users', 
	function (Slim\App $group) {
	 $group->post('', 'FmUserController:createUser');
	 $group->post("/login", 'FmUserController:login');
}
);

 $app->get("/report", 'ContactController:generateReport');
});





   

   
   
    
   
   
   
