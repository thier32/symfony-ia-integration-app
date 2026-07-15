<?php
namespace App\Controller;

use App\Dto\Member\MemberCreateDto;
use App\Response\MemberResponseDto;

interface IMemberController
{
    public function createMember(MemberCreateDto $memberCreateDto): MemberResponseDto;

    public function listMember(array $searchCriteria = []): MemberResponseDto;

}
