<?php

/**
 * @author Pablo
 * @copyright 2013
 *
 * Convierte automaticamente campos date y datetime a formatos PHP y MySQL
 *
 */

class PFechas extends CActiveRecordBehavior
{
    public function afterFind($event)
    {
        // convierto en formato legible la fecha y hora
        foreach($this->getOwner()->tableSchema->columns as $campo){
            if (($campo->dbType != 'date') and ($campo->dbType != 'datetime') and ($campo->dbType != 'timestamp') and ($campo->dbType != 'time')) continue;

            if(isset($campo) && ($campo->dbType === 'date' || $campo->dbType === 'datetime' || $campo->dbType === 'timestamp' || $campo->dbType === 'time')){
                switch($campo->dbType){
                    case 'date':
                        $this->getOwner()->{trim($campo->name)} = strtotime ($this->getOwner()->{trim($campo->name)});
                        $this->getOwner()->{trim($campo->name)} = date ('d-m-Y', $this->getOwner()->{trim($campo->name)});
                        break;
                    case 'datetime':
                    case 'timestamp':
                        $this->getOwner()->{trim($campo->name)} = strtotime ($this->getOwner()->{trim($campo->name)});
                        $this->getOwner()->{trim($campo->name)} = date ('d-m-Y H:i:s', $this->getOwner()->{trim($campo->name)});
                        break;
                    case 'time':
                        $this->getOwner()->{trim($campo->name)} = strtotime ($this->getOwner()->{trim($campo->name)});
                        $this->getOwner()->{trim($campo->name)} = date ('H:i', $this->getOwner()->{trim($campo->name)});
                        break;
                }
            }
        }

        parent::afterFind($event);
    }

    public function beforeSave($event)
    {
        // convierto en formato de BD la fecha y hora
        foreach($this->getOwner()->tableSchema->columns as $campo){
            if (($campo->dbType != 'date') and ($campo->dbType != 'datetime') and ($campo->dbType != 'timestamp') and ($campo->dbType != 'time')) continue;

            if(isset($campo) && ($campo->dbType === 'date' || $campo->dbType === 'datetime' || $campo->dbType === 'timestamp' || $campo->dbType === 'time')){
                switch($campo->dbType){
                    case 'date':
                        $this->getOwner()->{trim($campo->name)} = strtotime ($this->getOwner()->{trim($campo->name)});
                        $this->getOwner()->{trim($campo->name)} = date ('Y-m-d', $this->getOwner()->{trim($campo->name)});
                        break;
                    case 'datetime':
                    case 'timestamp':
                        $this->getOwner()->{trim($campo->name)} = strtotime ($this->getOwner()->{trim($campo->name)});
                        $this->getOwner()->{trim($campo->name)} = date ('Y-m-d H:i:s', $this->getOwner()->{trim($campo->name)});
                        break;
                    case 'time':
                        $this->getOwner()->{trim($campo->name)} = strtotime ($this->getOwner()->{trim($campo->name)});
                        $this->getOwner()->{trim($campo->name)} = date ('H:i', $this->getOwner()->{trim($campo->name)});
                        break;
                }
            }
        }

        return parent::beforeSave($event);
    }
}

?>