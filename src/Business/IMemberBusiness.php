<?php

namespace App\Business;


use App\Dto\Member\MemberCreateDto;
use App\Dto\Member\MemberDto;

interface IMemberBusiness
{

    /**
     * @param MemberCreateDto $memberCreateDto
     * @return MemberDto
     */
    public function createMember(MemberCreateDto $memberCreateDto): MemberDto;

    /**
     * @param array $searchCriteria
     * @return array
     */
    public function listMember(array $searchCriteria): array;
}
