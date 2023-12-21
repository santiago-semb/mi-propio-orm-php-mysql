<?php 

namespace Model;
class Property {
    public function __construct(
        public string $name,
        public bool $not_null,
        public string $type,
        public bool $unsigned,
        public int|null $size,
        public array $default,
        public bool $auto_increment,
        public bool $primary_key,
        public array $indexes,
        /*
        
        For the default field.
        It, will have contains this keys inside an asociative array
        'default_value' => DEFAULT VALUE

        For the foreign keys.
        It, will have contain this keys inside an asociative array

        'references_table' => TABLE REFERENCE,
        'fk_name' => FOREIGN KEY NAME, 
        'field_fk_name' => FIELD FOREIGN KEY NAME,
        'field_references' => FIELD REFERENCE,

        */
    )
    {}


    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

}

?>