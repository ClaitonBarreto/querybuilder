<?php

namespace Cbcode\DB;

use Cbcode\DB\Sql;

class QueryBuilder
{
    /**
     * @var $methods
     * @param $methodName
     * @param $args
     * @return $this
     */
    private $methods = [];
    
    /*
       É necessario criar uma restrição de quais metodos dinamicos serão aceitos
    */

    public function __call($methodName, $args)
    {
        $clausule = $args[0];
        if(count($args) > 1)
            $clausule = $args;
            
        $this->methods[$methodName] = $clausule;

        return $this;
    }


    public function listAll()
    {

        $table = isset($this->methods['table']) ? $this->methods['table'] : '<table>';

        $_fields = isset($this->methods['fields']) ? $this->methods['fields'] : '<fields>';

        if($_fields === '*')
            $fields = $_fields;
        else
            $fields = implode(',', $_fields);

        $rowQuery = $this->prepareQueryString(['SELECT', $fields, 'FROM', $table]);

        return $rowQuery;

    }

    public function list($_values = [])
    {

        $table = isset($this->methods['table']) ? $this->methods['table'] : '<table>';

        $_fields = isset($this->methods['fields']) ? $this->methods['fields'] : '<fields>';

        if($_fields === '*')
            $fields = $_fields;
        else
            $fields = implode(', ', $_fields);

        $_conditions = isset($this->methods['where']) ? $this->methods['where'] : array();

        if(count($_conditions) != count($_values)){
            throw new \Exception('Quantidade de valores passados direfente de campos solicitados');
        }
            
        $parameters = [];

        for($i = 0; $i < count($_conditions); $i++)
        {   
            $parameters[] = $_conditions[$i] . " = '" . $_values[$i] . "'";
        }

        $condition = implode(' AND ', $parameters);

        $rowQuery = $this->prepareQueryString(['SELECT', $fields, 'FROM', $table, 'WHERE', $condition]);

        return $rowQuery;

    }

    public function update($values = [])
    {
    
         $table = isset($this->methods['table']) ? $this->methods['table'] : '<table>';
         $_fields = isset($this->methods['fields']) ? $this->methods['fields'] : '<fields>';
         $fields = implode(', ', $_fields);
         $id = $this->methods['id'];

         if(!$id)
            throw new \Exception("É preciso informar um usuario para ser editado");

        $_sets = [];
        for($i = 0; $i < count($_fields); $i++)
        {
            $_sets[] = $_fields[$i] . " = '" . $values[$i] . "'";
        }

        $sets = implode(', ', $_sets);

        $rowQuery = $this->prepareQueryString(['UPDATE', $table, 'SET', $sets, 'WHERE', "id = $id"]);

        return $rowQuery;

    }

    public function insert()
    {

        $table = isset($this->methods['table']) ? $this->methods['table'] : '<table>';
        $_fields = isset($this->methods['fields']) ? $this->methods['fields'] : '<fields>';
        $__values = isset($this->methods['values']) ? $this->methods['values'] : '<values>';

        $fields = implode(', ', $_fields);

        $_values = $this->mapArrayToSingleMarks($__values);

        $values = implode(', ', $_values);

        $rowQuery = $this->prepareQueryString(['INSERT INTO', $table, '('.$fields.')', 'VALUES', '('.$values.')']);

        return $rowQuery;
        
    }

    public function delete()
    {

        $table = isset($this->methods['table']) ? $this->methods['table'] : '<table>';

        $id = $this->methods['id'];

        if(!$id)
            throw new \Exception('Deve informar um ID');

        $rowQuery = $this->prepareQueryString(['DELETE FROM', $table, 'WHERE', "id = $id"]);

        return $rowQuery;

    }

    private function prepareQueryString(array $partsQuery)
    {
        return implode(' ', $partsQuery);
    }

    private function mapArrayToSingleMarks(array $array):array
    {
        $newArray = array_map(function($a){
            return "'".$a."'";
        }, $array);

        return $newArray;
    }

}
