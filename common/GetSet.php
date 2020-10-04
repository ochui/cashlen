<?php


namespace app\common;


trait GetSet
{
    public function get($what){
        return $this->{$what};
    }

    public function set($what,$value){
        $this->{$what} = $value;
    }

    /**
     * @param array $params
     * @param bool $assoc
     * @return array
     */
    public function fetch($params, $assoc = true){
        $data = [];


        foreach($params as $param){

            if($this->get($param) !== false) {
                if($assoc)
                    $data[$param] = $this->get($param);
                else
                    $data[] = $this->get($param);
            }
        }

        return $data;
    }
}
