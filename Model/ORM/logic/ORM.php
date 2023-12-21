<?php
require 'Database/Conectar.php';


class ORM
{
    public static function getConnection()
    {
        return Connect::connection();
    }

    # Create entity table
    public static function table_create(Object $entity)
    {
        $table = $entity::get_table_name();
        $db_connection = self::getConnection();
        $db_connection->prepare("CREATE TABLE $table (created boolean) ENGINE = InnoDB;")->execute();
        $properties =  $entity::getProperties();
        $constraints = $entity->__get('constraints');
        $i = 1;
        foreach ($properties as $property) {
            $name = $property->__get('name');
            $not_null = $property->__get('not_null');
            $type = $property->__get('type');
            $unsigned = $property->__get('unsigned');
            #$unique = $property->__get('unique');
            $size = $property->__get('size');
            $default = $property->__get('default');
            $auto_increment = $property->__get('auto_increment');
            $primary_key = $property->__get('primary_key');
            #$foreign_key = $property->__get('foreign_key');
            $indexes = $property->__get('indexes');

            if($size == null) $size = 100;

            $save_properties = [
                "save_property_$i" => [
                    'name' => $name,
                    'not_null' => ($not_null) ? 'NOT NULL' : '',
                    'type' => ($unsigned) ? "$type($size) UNSIGNED" : "$type($size)",
                    #'unique' => (count($unique) > 0) ? "$unique[UQ_name] UNIQUE ($unique[UQ_field])" : '',
                    'default' => (count($default) > 0) ? "DEFAULT $default[default_value]" : '',
                    'auto_increment' => ($auto_increment) ? 'AUTO_INCREMENT' : '',
                    'primary_key' => ($primary_key) ? "PRIMARY KEY($name)" : '',
                    #'foreign_key' => (count($foreign_key) > 0) ? "$foreign_key[fk_name] FOREIGN KEY ($foreign_key[field_fk_name]) REFERENCES $foreign_key[references_table] ($foreign_key[field_references])" : '',
                    'indexes' => (count($indexes) > 0) ? "CREATE UNIQUE INDEX $indexes[INDEX_name] ON $table ($indexes[INDEX_field]);" : '',
                ],
            ];

            $saveName = $save_properties["save_property_$i"]['name'];
            $saveNotNull = $save_properties["save_property_$i"]['not_null'];
            $saveType = $save_properties["save_property_$i"]['type'];
            #$saveUnique = $save_properties["save_property_$i"]['unique'];
            $saveDefault = $save_properties["save_property_$i"]['default'];
            ($i == 1) ? $saveAutoIncrement = $save_properties["save_property_$i"]['auto_increment'] . ',' : $saveAutoIncrement = $save_properties["save_property_$i"]['auto_increment'];
            $savePrimaryKey = $save_properties["save_property_$i"]['primary_key'];
            #$saveForeignKey = $save_properties["save_property_$i"]['foreign_key'];
            $saveIndexes = $save_properties["save_property_$i"]['indexes'];

            $db_connection->prepare("ALTER TABLE $table ADD (
                    $saveName $saveType $saveNotNull $saveDefault $saveAutoIncrement
                    $savePrimaryKey
                )")->execute();

            /*if ($saveForeignKey != null || $saveForeignKey != "") {
                $db_connection->prepare("ALTER TABLE $table ADD CONSTRAINT $saveForeignKey")->execute();
            }
            if ($saveUnique != null || $saveUnique != "") {
                $db_connection->prepare("ALTER TABLE $table ADD CONSTRAINT $saveUnique")->execute();
            }*/
            if ($saveIndexes != null || $saveIndexes != "") {
                $db_connection->prepare($saveIndexes)->execute();
            }

            $i++;
        }

        # foreign keys
        if($constraints['fk_name'] != null || $constraints['fk_name'] != ''){
            $db_connection->prepare(
            "ALTER TABLE $table ADD CONSTRAINT $constraints[fk_name] FOREIGN KEY 
            ($constraints[field_fk_name]) REFERENCES $constraints[references_table] ($constraints[field_references])"
            )->execute();
        }
        # unique
        if($constraints['UQ_name'] != null || $constraints['UQ_name'] != ''){  
            $db_connection->prepare("ALTER TABLE $table ADD CONSTRAINT $constraints[UQ_name] UNIQUE ($constraints[UQ_field])")->execute();
        }
        # check
        if($constraints['CK_condition'] != null || $constraints['CK_condition'] != ''){      
             $db_connection->prepare("ALTER TABLE $table ADD CHECK $constraints[CK_condition]")->execute();
        }



        $db_connection->prepare("ALTER TABLE $table DROP COLUMN created")->execute();
    }
    # Solo se pueden agregar hasta 100 campos
    public static function table_push(Object $entity)
    {
        $table = $entity::get_table_name();
        $db_connection = self::getConnection();
        $properties = $entity::getProperties();

        $saveProperties = [];
        $valuesProperties = [];
        $valuesOfProperties = [];
        for ($i = 0; $i < count($properties); $i++) {
            array_push($saveProperties, $properties[$i]->__get('name'));
            array_push($valuesProperties, [
                "value_prop_$i" => [
                    'value' => $entity->__get($properties[$i]->__get('name'))
                ],
            ]);
            array_push($valuesOfProperties, $valuesProperties[$i]["value_prop_$i"]['value']);
            # Si existe el valor de la propiedad, y si esta misma no es la ultima, se le asigna una coma (,) al final.
            # Caso contrario, no se le asigna la coma (,). Esto para que no de error en la query
            (isset($valuesOfProperties[$i])) ? $variables["v_$i"] = ($i == count($properties) - 1) ? '"' . $valuesOfProperties[$i] . '"' : '"' . $valuesOfProperties[$i] . '"' . ',' : $variables["v_$i"] = null;
        }

        # Separo por comas las propiedades (para la query)
        $nameProperties = implode(',', $saveProperties);

        # Si no existe el indice dentro del arrray $variables, entonces que no escriba nada en la query
        for ($i = 0; $i < 100; $i++) {
            # Si el valor no existe, entonces va a ser null (para que no de conflictos en la query)
            if (!isset($variables["v_$i"])) {
                $marcador["marcador_$i"] = null;
            } else {
                # Si existe el valor, entonces se le asigna un '?' para luego darle el valor en la query con bindParam()
                if (isset($variables["v_$i"])) {
                    $marcador["marcador_$i"] = "?,";
                }
            }
        }
        # Quito la ultima coma para que no de error en la query
        $marcadores = trim(implode($marcador), ',');
        # Query
        $query = $db_connection->prepare("INSERT INTO $table ($nameProperties) VALUES ($marcadores)");
        # Asigno los interrogantes '?' en la query
        for ($i = 0; $i < 100; $i++) {
            # Si el marcador existe, entonces se asigna el valor
            if (isset($marcador["marcador_$i"])) {
                $query->bindParam($i + 1, $valuesOfProperties[$i]);
            }
        }
        $query->execute();
    }

    # Delete entity table
    public static function table_destroy(array $entities)
    {
        for($i=0; $i<count($entities); $i++){
            $table = $entities[$i]::get_table_name();
            $db_connection = self::getConnection();
            $db_connection->prepare("DROP TABLE IF EXISTS $table")->execute(); 
        }
    }

    # Clear entity table (delete all data)
    public static function table_clear(Object $entity)
    {
        $table = $entity::get_table_name();
        $db_connection = Connect::connection();
        $db_connection->prepare("DELETE FROM $table")->execute();
    }

    # Clear data where id
    # Solo funciona si en la clase se tiene definido el campo 'id' (con ese nombre en específico)
    public static function table_clear_where_id(Object $entity, $id)
    {
        $table = $entity::get_table_name();
        $db_connection = Connect::connection();
        $query = $db_connection->prepare("DELETE FROM $table WHERE id=?");
        $query->bindParam(1, $id);
        $query->execute();
    }

    # Clear data between a min_id and max_id
    # Solo funciona si en la clase se tiene definido el campo 'id' (con ese nombre en específico)
    public static function table_clear_between_id()
    {
    }

    # This function will return all data of the table entity
    public static function table_get(Object $entity)
    {
        $table = $entity::get_table_name();
        $db_connection = Connect::connection();
        $res = $db_connection->prepare("SELECT * FROM $table");
        $res->execute();
        $data = $res->fetchAll();

        return $data;
    }

    public static function table_get_where_id(Object $entity, $id)
    {
        $table = $entity::get_table_name();
        $db_connection = Connect::connection();
        $res = $db_connection->prepare("SELECT * FROM $table WHERE id=?");
        $res->bindParam(1, $id);
        $res->execute();
        $data = $res->fetch();

        return $data;
    }
}
