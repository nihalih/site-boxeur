<?php

interface BoxeurStorage {


    public function read($id);
    public function readAll();
    public function create(Boxeur $b);
    public function update($id, Boxeur $b);
    public function delete($id);
}


?>