<?php
namespace App\Dto\Member;

use App\Dto\BaseDto;

class MemberDto extends BaseDto
{
    public function __construct(
        public ?int $memberId = null,
        public ?string $name = null,
        public ?string $email = null
    )
    {
    }
}
