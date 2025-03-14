<?php

class User extends Model {

   protected static $table_name = 'USER';

   // load all users from Db
   public static function getList() {
      $stm = parent::exec('USER_LIST');
      return $stm->fetchAll();
   }

   public static function getUser($id) {
      $stm = parent::exec('USER_GET_WITH_ID', array("id" => $id));
      $result =  $stm->fetchAll();
      if (isset($result) && count($result) > 0) {
         return $result[0];
     } else {
         return null;
     }
   }

   public function updateUser() {
      $stm = parent::exec('USER_UPDATE', array("id" => $this->id, "name" => $this->name, "email" => $this->email, "pwd" => $this->pwd));
      if ($stm === 0) {
         return null;
      } else {
         return $this;
      }
   }
}