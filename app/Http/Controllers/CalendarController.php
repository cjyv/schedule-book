<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalendarController extends Controller
{
     function edit(){
        header("Content-Type: text/html; charset=KS_C_5601-1987");
        header("Cache-Control:no-cache");
        header("Pragma:no-cache");

$this->load->model('Calendar_m');
        $data['title']=$_POST['title'];
        $data['start']=$_POST['start'];
        $data['end']=$_POST['end'];

        $data = $this -> Calendar_m->edit($data);
    }
    function add() {
        header("Content-Type: text/html; charset=KS_C_5601-1987");
        header("Cache-Control:no-cache");
        header("Pragma:no-cache");
    
        $this->load->model('Calendar_m');
    
        $arr = array(
            'title'=>$_POST['title'],
            'start'=>$_POST['start'],
            'end'=>$_POST['end']
        );
    
        $data = $this->Calendar_m->add($arr);
    }

    function delete() {
        header("Content-Type: text/html; charset=KS_C_5601-1987");
        header("Cache-Control:no-cache");
        header("Pragma:no-cache");
    
        $this->load->model('Calendar_m');
    
        $title = $_POST['title'];
    
        $data = $this->Calendar_m->delete($title);
    }
    function load() {
        header("Content-Type: text/html; charset=KS_C_5601-1987");
        header("Cache-Control:no-cache");
        header("Pragma:no-cache");
        header("Content-Type:application/json");
    
        $this->load->model('Calendar_m');
    
        $data = $this->Calendar_m->getAll();
    
        $result = json_encode($data, JSON_UNESCAPED_UNICODE);
    
        echo $result;
    }
}
