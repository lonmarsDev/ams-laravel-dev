dir
<?php
//config

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "";

//end db config




error_reporting (E_ALL|E_STRICT);
require_once ("./PHP-MySQLi-Database-Class/MysqliDb.php");
//require_once ('MysqliDb.php');
//require_once ("./PHP-MySQLi-Database-Class/dbObject.php");
// Create database context
$db = new MysqliDb ('localhost', 'root', '', 'ams-dev');






$page_count = 0;
$has_more_items = false;
$continuation = "";

$print =1;




$json_string = 'https://www.eventbriteapi.com/v3/events/38258442030/orders/?token=5ZS5EOARNYW4ZIAPHEDN';
echo $json_string;
$jsondata = file_get_contents($json_string);
$obj = json_decode($jsondata);
$continuation = $obj->{'pagination'}->{'continuation'};
$has_more_items = $obj->{'pagination'}->{'has_more_items'};
$page_count =  $obj->{'pagination'}->{'page_count'}; 



foreach($obj->{'orders'} as $value){
  echo "<br>";
  echo "<br>";
  echo $print;
  $print ++;
  var_dump( $value->{'name'}  );
  var_dump( $value->{'event_id'}  );
  
  $amount = 0;
  if( isset($value->{'cost'}->{'gross'}->{'display'} ) ){
     $amount=  $value->{'cost'}->{'gross'}->{'display'} ;
  }

  $db->where ("eb_order_id", $value->{'id'}  );
  $order = $db->getOne("orders");
  if (!$order){ //order filter
    $data = Array ( 
              "eb_order_id" => $value->{'id'} ,
              "account_id" => 1 ,
              "order_status_id" => 1 ,
              "first_name" => $value->{'first_name'} ,
              "last_name" => $value->{'last_name'} ,
              "email" => $value->{'email'} ,
              "order_date" => $value->{'created'} ,
              "amount" =>  $amount ,
              "notes" => "hook from EB" ,
              "event_id" => 6 ,
              "created_at" => $value->{'created'} ,
              "updated_at" => $value->{'created'} 
    );
    $id = $db->insert ('orders', $data);
    if ($id){
      echo 'order was imported. Id=' . $id;
    }else{
      echo "error importing order";
      echo 'insert failed: ' . $db->getLastError();
    }
  }else{
    echo "order already exist";
  }

}

var_dump($has_more_items);

echo "<br>";
  echo "<br>count:" . $page_count;
  echo "<br>";
  echo "<br>continuation:" . $continuation;

for ($i=0; $i <= $page_count ; $i++) { 
  if($has_more_items){
    $json_string = 'https://www.eventbriteapi.com/v3/events/38258442030/orders/?token=5ZS5EOARNYW4ZIAPHEDN&continuation='.$continuation;
    echo $json_string;
    $jsondata = file_get_contents($json_string);
    $obj = json_decode($jsondata);
    
    if ( isset($obj->{'pagination'}->{'continuation'} ) ) {
      $continuation = $obj->{'pagination'}->{'continuation'} ;
    }

    if ( isset($obj->{'pagination'}->{'has_more_items'} ) ) {
      $has_more_items = $obj->{'pagination'}->{'has_more_items'};
    }else{
      $has_more_items =false;
    }
    
    echo "<br>";
    echo "<br>";
    if ( isset($obj->{'pagination'}->{'page_number'} ) ) {
      var_dump( $obj->{'pagination'}->{'page_number'} );
    }
  




    foreach($obj->{'orders'} as $value){
    echo "<br>";
    echo "<br>";
    echo $print;
    $print ++;
    var_dump( $value->{'name'}  );
    var_dump( $value->{'event_id'}  );
    
    $amount = 0;
    if( isset($value->{'cost'}->{'gross'}->{'display'} ) ){
       $amount=  $value->{'cost'}->{'gross'}->{'display'} ;
    }

    $db->where ("eb_order_id", $value->{'id'}  );
    $order = $db->getOne("orders");
    if (!$order){ //order filter
      $data = Array ( 
                "eb_order_id" => $value->{'id'} ,
                "account_id" => 1 ,
                "order_status_id" => 1 ,
                "first_name" => $value->{'first_name'} ,
                "last_name" => $value->{'last_name'} ,
                "email" => $value->{'email'} ,
                "order_date" => $value->{'created'} ,
                "amount" =>  $amount ,
                "notes" => "hook from EB" ,
                "event_id" => 6 ,
                "created_at" => $value->{'created'} ,
                "updated_at" => $value->{'created'} 
      );
      $id = $db->insert ('orders', $data);
      if ($id){
        echo 'order was imported. Id=' . $id;
      }else{
        echo "error importing order";
        echo 'insert failed: ' . $db->getLastError();
      }
    }else{
      echo "order already exist";
    }  


  }


  }

}




/*import attendee script*/






$page_count = 0;
$has_more_items = false;
$continuation = "";

$print =1;




$json_string = 'https://www.eventbriteapi.com/v3/events/38258442030/attendees/?token=5ZS5EOARNYW4ZIAPHEDN';
echo $json_string;
$jsondata = file_get_contents($json_string);
$obj = json_decode($jsondata);
$continuation = $obj->{'pagination'}->{'continuation'};
$has_more_items = $obj->{'pagination'}->{'has_more_items'};
$page_count =  $obj->{'pagination'}->{'page_count'}; 

echo "<br>";
echo "<br> attend page _number : ";
//var_dump( $obj->{'pagination'}->{'page_number'} );
if ( isset($obj->{'pagination'}->{'page_number'} ) ) {
  var_dump( $obj->{'pagination'}->{'page_number'} );
}



foreach($obj->{'attendees'} as $value){
  echo "<br>";
  echo "<br>";
  echo $print;
  $print ++;

  
  $db->where ("eb_order_id", $value->{'order_id'}  );
  $order = $db->getOne("orders");
  echo $order['id'];

  $barcode = "";
  var_dump($value->{'barcodes'}[0]->{'barcode'});
  if( isset($value->{'barcodes'}[0]->{'barcode'}) ){
    $barcode = $value->{'barcodes'}[0]->{'barcode'};
  }
  echo "<br>";
  echo "the barcode:" . $barcode ;
  echo "<br>";

  $db->where ("eb_attendee_id", $value->{'id'}  );
  $attendee = $db->getOne("attendees");
  
  if(!$attendee){ //filter 
    $data = Array ( 
              "first_name" => $value->{'profile'}->{'first_name'} ,
              "last_name" => $value->{'profile'}->{'last_name'} ,
              "email" => $value->{'profile'}->{'email'} ,
              "event_id" => 6 ,
              "order_id" =>   $order['id'] ,
              "ticket_id" => 0 ,
              "private_reference_number" =>  $barcode,
              "eb_barcode" =>  $barcode,
              "eb_ticket_class_name" => $value->{'ticket_class_name'},
              "eb_attendee_id" => $value->{'id'},

              "account_id" => 1 ,
              "reference_index" => 1,
              "ticket_id" => 18 ,


              "created_at" => $value->{'created'} ,
              "updated_at" => $value->{'created'} 
    );
    $id = $db->insert ('attendees', $data);
    if ($id){
      echo 'attendee was imported. Id=' . $id;
    }else{
      echo "error importing attendee";
      echo 'insert failed: ' . $db->getLastError();
    }
  }else{
    echo "attendee already exist.";
  }  

}


var_dump($has_more_items);

echo "<br>";
echo "<br>count:" . $page_count;
echo "<br>";
echo "<br>continuation:" . $continuation;


for ($i=0; $i <= $page_count ; $i++) { 
  if($has_more_items){
    $json_string = 'https://www.eventbriteapi.com/v3/events/38258442030/attendees/?token=5ZS5EOARNYW4ZIAPHEDN&continuation='.$continuation;
    
    echo $json_string;
    $jsondata = file_get_contents($json_string);
    $obj = json_decode($jsondata);
    
    if ( isset($obj->{'pagination'}->{'continuation'} ) ) {
      $continuation = $obj->{'pagination'}->{'continuation'} ;
    }

    if ( isset($obj->{'pagination'}->{'has_more_items'} ) ) {
      $has_more_items = $obj->{'pagination'}->{'has_more_items'};
    }else{
      $has_more_items =false;
    }
    
    echo "<br>";
    echo "<br> attend page _number : ";
    //var_dump( $obj->{'pagination'}->{'page_number'} );
    if ( isset($obj->{'pagination'}->{'page_number'} ) ) {
      var_dump( $obj->{'pagination'}->{'page_number'} );
    }
  



    foreach($obj->{'attendees'} as $value){
      echo "<br>";
      echo "<br>";
      echo $print;
      $print ++;

      
      $db->where ("eb_order_id", $value->{'order_id'}  );
      $order = $db->getOne("orders");
      echo $order['id'];

      $barcode = "";
      if( isset($value->{'barcodes'}[0]->{'barcode'}) ){
        $barcode = $value->{'barcodes'}[0]->{'barcode'};
      }
      echo "<br>";
      echo "the barcode:" . $barcode ;
      echo "<br>";

      $db->where ("eb_attendee_id", $value->{'id'}  );
      $attendee = $db->getOne("attendees");
      
      if(!$attendee){ //filter 
      
        $data = Array ( 
                  "first_name" => $value->{'profile'}->{'first_name'} ,
                  "last_name" => $value->{'profile'}->{'last_name'} ,
                  "email" => $value->{'profile'}->{'email'} ,
                  "event_id" => 6 ,
                  "order_id" =>   $order['id'] ,
                  "ticket_id" => 0 ,
                  "private_reference_number" =>  $barcode,
                  "eb_barcode" =>  $barcode,
                  "eb_ticket_class_name" => $value->{'ticket_class_name'},
                  "eb_attendee_id" => $value->{'id'},
                  
                  "account_id" => 1 ,
                  "reference_index" => 1,
                  "ticket_id" => 18 , 

                  "created_at" => $value->{'created'} ,
                  "updated_at" => $value->{'created'} 
        );
        $id = $db->insert ('attendees', $data);
        if ($id){
          echo 'attendee was imported. Id=' . $id;
        }else{
          echo "error importing attendee";
          echo 'insert failed: ' . $db->getLastError();
        }

      }else{
        echo "attendee already exist.";
      }  

    }


  }

}
















/*end importing attendee*/
/*

foreach($obj as $value){
  var_dump($value->{'id'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'author'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'link'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'tags'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'title'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'updatetime'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'title'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'content'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'link'}); //change accordinglyc
  echo '<br>';  
  var_dump($value->{'datetime'}); //change accordinglyc
 // var_dump($value->{'updatetime'}); //change accordinglyc
 ///date( 'Y-m-d', strtotime($value->{'datetime'})  ) 




  $data = Array ("id"               => $value->{'id'},
                 //"lead_source"      => $value->{'author'},  
                 "r_s_s_post_u_r_l" => $value->{'link'},
                 //"domain"           => $value->{'tags'}, 
                 //"subject_line"     => 'cat',
                 "created_at"     => date('Y-m-d H:i:s'),
                 "modified_at"      => date('Y-m-d H:i:s'),
                 "type"             => 'Not Yet Categorized',
                 "source"           => 'RSS', 
                 "r_s_s_update_date"=> date( 'Y-m-d', strtotime($value->{'updatetime'})  ), 
                 "r_s_s_post_title" => $value->{'title'},
                 "r_s_s_post_body"  => $value->{'content'},
                 "r_s_s_post_date"  => $value->{'datetime'},
                 "status"           => 'Raw',


                 "address_city"  => '',

                 "salutation_name"  => '',
                 "description"      => '',
                 "address_street"   => '',
                 "address_state"    => '',
                 "address_country"  => '',
                 "address_postal_code" => '',
                 "gmail_compose" => '',
                 "created_by_id" => '',
                 "assigned_user_id" => ''
  );
  
  //var_dump($data);die();
  $id = $db->insert ('target_lead', $data);
  if ($id){
    echo 'user was created. Id=' . $id;
  }else{ /// if record exist will update the RSS update date
    echo 'insert failed: ' . $db->getLastError();
    echo ' ---update record';
    $data = Array(
        'r_s_s_update_date' => date( 'Y-m-d', strtotime($value->{'updatetime'})  )
    );
    $db->where ("id", $value->{'id'});
    $db->where ("status",'Raw');
    $db->update ('target_lead', $data);
  }


}


*/