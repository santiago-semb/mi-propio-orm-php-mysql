<?php 
namespace Model;

class Persona {

    private static string $table_name = "personas";

    private array $constraints =             
    # check (keys = CK_name, CK_condition)
    # unique (keys = UQ_name, UQ_field)
    # foreign keys (keys = references_table, fk_name, field_fk_name, field_references)
    [
        'CK_name' => 'CK_edad',
        # remember if condition is string, put "" into string fields
        'CK_condition' => '(edad >= 18)',
        'UQ_name' => 'UQ_dni',
        'UQ_field' => 'dni',
        'references_table' => '',
        'fk_name' => '',
        'field_fk_name' => '',
        'field_references' => '',
    ];

    public function __construct(
        public int|string $id,
        public int $dni,
        public string $edad
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
            'id',true,'INT',true,8,
            [],
            true,true,
            [
                # all indexes are UNIQUE
                'INDEX_name' => 'CL_IN_id',
                'INDEX_field' => 'id'
            ]
        );
        self::$properties[1] = new Property(
            'dni',true,'INT',true,8,
            [],
            false,false,
            [
                # all indexes are UNIQUE
                'INDEX_name' => 'CL_IN_dni',
                'INDEX_field' => 'dni'
            ]
        );
        self::$properties[2] = new Property(
            #name
            'edad',
            # not null
            true,
            # type
            'INT',
            # unsigned
            false,
            # size (null=100)
            3,
            # default (keys = default_value)
            [],
            # auto_increment
            false,
            # pk
            false,
            []
        );
        
    }

}


?>