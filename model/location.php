<?php

include("result_query.php");

class location {
    public $id;
    public $name;
    public $address;
    public $description;
    public $latitude;
    public $longitude;
    public $url_image; 
 
    public function __construct(){
    }

    public function set($data){
        $this->id = (int) $data->id;
        $this->name = $data->name;
        $this->address = $data->address;
        $this->description = $data->description;
        $this->latitude = $data->latitude;
        $this->longitude = $data->longitude;
        $this->url_image = $data->url_image; 
    }

    public function add($db) {
        $result_query = new result_query();
        $result_query->data = "ok";
        $query = "INSERT INTO location (name,address,description,latitude,longitude,url_image) VALUES (?,?,?,?,?,?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssdds', $this->name,$this->address,$this->description,$this->latitude,$this->longitude,$this->url_image);
        $stmt->execute();
        if ($stmt->error != ""){
            $result_query->error =  "error at add new kategori : ".$stmt->error;
            $result_query->data = "not ok";
        }
        $stmt->close();
        return $result_query;
    }
    
    public function one($db) {
        $result_query = new result_query();
        $one = new location();
        $query = "SELECT id,name,address,description,latitude,longitude,url_image FROM location WHERE id=? LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();      
        if ($stmt->error != ""){
            $result_query-> error = "error at query one location: ".$stmt->error;
            $stmt->close();
            return $result_query;
        }
        $rows = $stmt->get_result();
        if($rows->num_rows == 0){
            $stmt->close();
            return $result_query;
        }
        $result = $rows->fetch_assoc();
        $one->id = $result['id'];
        $one->name = $result['name'];
        $one->address = $result['address'];
        $one->description = $result['description'];
        $one->latitude = $result['latitude'];
        $one->longitude = $result['longitude'];
        $one->url_image = $result['url_image'];
        $result_query->data = $one;
        $stmt->close();
        return $result_query;
    }
 
    public function all($db,$list_query) {
        $result_query = new result_query();
        $all = array();
        $query = "SELECT 
                    id,name,address,description,latitude,longitude,url_image
                FROM 
                    location
                WHERE
                    ".$list_query->search_by." LIKE ?
                ORDER BY
                    ".$list_query->order_by." ".$list_query->order_dir." 
                LIMIT ? 
                OFFSET ?";
        $stmt = $db->prepare($query);
        $search = "%".$list_query->search_value."%";
        $offset = $list_query->offset;
        $limit =  $list_query->limit;
        $stmt->bind_param('sii',$search ,$limit, $offset);
        $stmt->execute();
        if ($stmt->error != ""){
            $result_query-> error = "error at query all kategori : ".$stmt->error;
            $stmt->close();
            return $result_query;
        }
        $rows = $stmt->get_result();
        if($rows->num_rows == 0){
            $stmt->close();
            $result_query->data = $all;
            return $result_query;
        }

        while ($result = $rows->fetch_assoc()){
            $one = new location();
            $one->id = $result['id'];
            $one->name = $result['name'];
            $one->address = $result['address'];
            $one->description = $result['description'];
            $one->latitude = $result['latitude'];
            $one->longitude = $result['longitude'];
            $one->url_image = $result['url_image'];
            array_push($all,$one);
        }
        $result_query->data = $all;
        $stmt->close();
        return $result_query;
    }


    public function allCloses($db,$current_latitude,$current_longitude,$range,$list_query) {
        $result_query = new result_query();
        $all = array();
        $query = "SELECT 
                    id,name,address,description,latitude,longitude,url_image
                FROM 
                    location
                WHERE
                    ((degrees(acos(sin(radians(?)) * sin(radians(latitude)) + cos(radians(?)) * cos(radians(latitude)) * cos(radians(? - longitude)))) * 60 * 1.1515) * 1.609344) < ?
                AND
                    ".$list_query->search_by." LIKE ?
                ORDER BY
                    ((degrees(acos(sin(radians(?)) * sin(radians(latitude)) + cos(radians(?)) * cos(radians(latitude)) * cos(radians(? - longitude)))) * 60 * 1.1515) * 1.609344) ASC
                LIMIT ? 
                OFFSET ?";
        $stmt = $db->prepare($query);
        $search = "%".$list_query->search_value."%";
        $offset = $list_query->offset;
        $limit =  $list_query->limit;
        $stmt->bind_param('ddddsdddii',$current_latitude,$current_latitude,$current_longitude,$range,$search,$current_latitude,$current_latitude,$current_longitude,$limit,$offset);
        $stmt->execute();
        if ($stmt->error != ""){
            $result_query-> error = "error at query all kategori : ".$stmt->error;
            $stmt->close();
            return $result_query;
        }
        $rows = $stmt->get_result();
        if($rows->num_rows == 0){
            $stmt->close();
            $result_query->data = $all;
            return $result_query;
        }

        while ($result = $rows->fetch_assoc()){
            $one = new location();
            $one->id = $result['id'];
            $one->name = $result['name'];
            $one->address = $result['address'];
            $one->description = $result['description'];
            $one->latitude = $result['latitude'];
            $one->longitude = $result['longitude'];
            $one->url_image = $result['url_image'];
            array_push($all,$one);
        }
        $result_query->data = $all;
        $stmt->close();
        return $result_query;
    }

    public function update($db) {
        $result_query = new result_query();
        $result_query->data = "ok";
        $query = "UPDATE location SET name = ?,address = ?,description = ?,latitude = ?,longitude = ?,url_image = ? WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('sssddsi', $this->name,$this->address,$this->description,$this->latitude,$this->longitude,$this->url_image,$this->id);
        $stmt->execute();
        if ($stmt->error != ""){
            $result_query->error = "error at update one kategori : ".$stmt->error;
            $result_query->data = "not ok";
            $stmt->close();
            return $result_query;
        }
        $stmt->close();
        return $result_query;
    }
    
    public function delete($db) {
        $result_query = new result_query();
        $result_query->data = "ok";
        $query = "DELETE FROM location WHERE id=?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('i', $this->id);
        $stmt->execute();
        if ($stmt->error != ""){
            $result_query->error = "error at delete one location : ".$stmt->error;
            $result_query->data = "not ok";
            $stmt->close();
            return $result_query;
        }
        $stmt->close();
        return $result_query;
    }
}


?>