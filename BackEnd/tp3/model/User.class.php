<?php

class User extends Model {

   protected static $table_name = 'USER';

   // load all users from Db
   public static function getList() {
      $stm = parent::exec('USER_LIST');
      return $stm->fetchAll();
   }
}