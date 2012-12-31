<?php
namespace Migration\Components\Migration\Entities;

use Doctrine\DBAL\Connection,
    Doctrine\DBAL\Schema\AbstractSchemaManager as Schema,
    Doctrine\DBAL\Schema\Schema as SchemaBuilder;
use Migration\Components\Migration\EntityInterface;
    

class init_schema implements EntityInterface
{
    
    public function getTagTable(SchemaBuilder $builder)
    {
        $table = $builder->createTable("quicktag_tags");
        
        $table->addColumn('tag_id'          ,'integer' ,array("unsigned" => true));
        $table->addColumn('tag_user_context','integer' ,array("unsigned" => true,'notnull' => false));
        $table->addColumn('tag_date_created','datetime',array('notnull' => true));
        $table->addColumn('tag_weight'      ,'float'   ,array("unsigned" => true,'notnull' => false));
        $table->addColumn('tag_title'       ,'string'  ,array('length'=> 45,'notnull' => true));
        $table->setPrimaryKey(array("tag_id"));
        $table->addIndex(array('tag_user_context'));
        
    }

    public function up(Connection $db, Schema $sc)
    {
        $builder = new SchemaBuilder();
        
        $this->getTagTable($builder);
        
        $queries = $builder->toSql($db->getDatabasePlatform());
        
        foreach($queries as $query){
            $db->exec($query);
        }        
        

    }

    public function down(Connection $db, Schema $sc)
    {
        $builder = new SchemaBuilder();
        
         $this->getTagTable($builder);
        
        $queries = $builder->toDropSql($db->getDatabasePlatform());
        
        foreach($queries as $query){
            $db->exec($query);
        }

    }


}
/* End of File */
