<?php

namespace App\Enums;


enum CourseStatus:string{
    case Active = 'active';
    case Completed = 'completed';
    case Archived = 'archived';
}


?>