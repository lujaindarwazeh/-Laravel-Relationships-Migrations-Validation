<?php

namespace App\Enums;

//Note:use capital 
//Note:use getvalue function and use it in migration
enum CourseStatus:string{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case ARCHIVED = 'archived';


    public function getValue(): string
    {
        return $this->value;
    }
}


?>