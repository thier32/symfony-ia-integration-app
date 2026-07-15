<?php

namespace App\Service;

use App\Dto\Member\MemberCreateDto;
use App\Dto\Member\MemberDto;

interface IMemberService extends IBaseService
{
    /**
     * @param MemberCreateDto $memberCreateDto
     * @return MemberDto
     */
    public function createMember(MemberCreateDto $memberCreateDto):MemberDto;
}
