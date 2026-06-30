<?php

namespace App\Enums;

enum StatutPost: string
{
    case Draft = 'draft';
    case Archived = 'archived';
    case Posted = 'posted';
}
