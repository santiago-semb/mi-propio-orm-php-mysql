<?php 
namespace Model;

class Attraction {

    private static string $table_name = "attractions";

    private array $constraints =             
    # check (keys = CK_name, CK_condition)
    # unique (keys = UQ_name, UQ_field)
    # foreign keys (keys = references_table, fk_name, field_fk_name, field_references)
    [
        'CK_name' => '',
        # remember if condition is string, put "" into string fields
        'CK_condition' => '',
        'UQ_name' => '',
        'UQ_field' => '',
        'references_table' => 'countries',
        'fk_name' => 'FK_country',
        'field_fk_name' => 'countryId',
        'field_references' => 'id',
    ];

    public function __construct(
        public int|string $id,
        public string $name,
        public int $countryId
    )
    {}

    public static function get_table_name()
    {
        return self::$table_name;
    }

    public function __get($property)
    {
        return $this->$property;
    }

    public function __set($property, $value)
    {
        $this->$property = $value;
    }

    
    private static array $properties = [];

    public static function getProperties() :array
    {
        self::setPropertiesConfig();
        return self::$properties;
    }
    public static function setPropertiesConfig()
    {
        /* Property(
                    nombre,
                    not_null,
                    type,
                    unsigned,
                    size,
                    default,
                    auto_increment,
                    primary_key,
                    indexes
                )
        */
        # default (keys = default_value)
        self::$properties[0] = new Property(
            'id',true,'BIGINT',true,20,[],true,true,
            [
                # all indexes are UNIQUE
                'INDEX_name' => 'CL_IN_id',
                'INDEX_field' => 'id'
            ]
        );
        self::$properties[1] = new Property(
            'name',true,'VARCHAR',false,255,[],false,false,[]
        );
        self::$properties[2] = new Property(
            'countryId',true,'BIGINT',false,20,[],false,false,
            [
                # all indexes are UNIQUE
                'INDEX_name' => 'CL_IN_countryId',
                'INDEX_field' => 'countryId'
            ]
        );
        
    }

}


?>