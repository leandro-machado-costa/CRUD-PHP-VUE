<?php
 
    header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
    header('Access-Control-Allow-Headers: token, Content-Type');
    header('Access-Control-Max-Age: 1728000');
    header('Content-Length: 0');
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

      $request=$_SERVER['REQUEST_METHOD'];

   switch ( $request) {
    case 'GET':
      getmethod();
    break;
    case 'PUT':
          $data=json_decode(file_get_contents('php://input'),true);
         putmethod($data);
    break;
    case 'POST':
      $data=json_decode(file_get_contents('php://input'),true);
         postmethod($data);
    break;
    case 'DELETE':
         deletemethod($_GET['id']);
    break;
    
    default:
      echo '{"name": "data not found"}';
      break;
   }
//data read part are here
function getmethod(){
  include "db.php";
  $sql = "SELECT * FROM produtos";
  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
       $rows=array();
       while ($r = mysqli_fetch_assoc($result)) {

          $rows["result"][] = $r;
       }

       echo json_encode($rows);
  }  else{
       echo json_encode([]);
    }
}
//data insert part are here
function postmethod($data){
   include "db.php";
   $product_name=$data["product_name"];
   $cod_bar=$data["cod_bar"];
   $price_buy=$data["price_buy"];

   $sql= "INSERT INTO produtos(product_name,cod_bar,price_buy) VALUES ('$product_name' , '$cod_bar', '$price_buy')";

   if (mysqli_query($conn , $sql)) {
      echo '{"result": "data inserted"}';
   } else{

      echo '{"result": "data not inserted"}';
   }



}

//data edit part are here
function putmethod($data){
   include "db.php";
   $id=$data["id"];
   $product_name=$data["product_name"];
   $cod_bar=$data["cod_bar"];
   $price_buy=$data["price_buy"];

   $sql= "UPDATE produtos SET product_name='$product_name', cod_bar='$cod_bar', price_buy='$price_buy' where id='$id'  ";

   if (mysqli_query($conn , $sql)) {
      echo '{"result": "Update Succesfully"}';
   } else{

      echo '{"result": "not updated"}';
   }



}
//delete method are here,,,,,,,,,,,,,,
function deletemethod($id){
   include "db.php";


   $sql= "DELETE FROM produtos where id=$id";

   if (mysqli_query($conn , $sql)) {
      echo '{"result": "delete Succesfully"'.$sql.'}';
   } else{

      echo '{"result": "not deleted "}';
   }
}
?>
