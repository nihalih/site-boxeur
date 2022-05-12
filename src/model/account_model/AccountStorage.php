<?php

    interface AccountStorage{

        public function checkAuth($login,$password);
        public function read($id);
        public function readAll();
        public function create(Account $a);
        public function update($id,Account $account);
        public function delete($id);



    }


?>