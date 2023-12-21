<?php 
class Seeder {

    public static function seed(Object $entity, $times) :void
    {
        for($i=0;$i<$times;$i++){        
            ORM::table_push($entity);
        }
    }


}

?>