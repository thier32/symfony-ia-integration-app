<?php
namespace App\Dto\Member;


use App\Dto\BaseDto;

class MemberCreateDto extends BaseDto
{
    public function __construct(
        public ?string $name = null,
        public ?string $email = null
    )
    {
    }
}
