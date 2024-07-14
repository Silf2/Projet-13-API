<?php

declare(strict_types=1);

namespace App\Entity;

interface GroupsInterface 
{
    public const READ_GROUP = 'common:read';
    public const CREATE_GROUP = 'common:create';
    public const UPDATE_GROUP = 'common:update';
    public const WRITE_GROUP = 'common:write';
}