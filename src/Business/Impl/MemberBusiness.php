<?php

namespace App\Business\Impl;

use App\Business\IMemberBusiness;
use App\Dto\Member\MemberCreateDto;
use App\Dto\Member\MemberDto;
use App\Service\IMemberService;


class MemberBusiness implements IMemberBusiness
{

    public function __construct(private IMemberService $memberService){}


    public function createMember(MemberCreateDto $memberCreateDto): MemberDto
    {
        $member = $this->memberService->getEntity(['email' => $memberCreateDto->email]);
        if (!is_null($member)) {
            throw new \Exception(sprintf("Member with email %s already exists",$memberCreateDto->email));
        }
        return $this->memberService->createMember($memberCreateDto);
    }

    public function listMember(array $searchCriteria): array
    {
       return $this->memberService->getEntities(MemberDto::class, $searchCriteria);
    }
}
