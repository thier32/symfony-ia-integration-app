<?php

namespace App\Service\Impl;

use App\Dto\Member\MemberCreateDto;
use App\Dto\Member\MemberDto;
use App\Entity\Member;
use App\Repository\MemberRepository;
use App\Service\IMemberService;

class MemberService extends BaseService implements IMemberService
{
    public function __construct(private MemberRepository $memberRepository
    ){

        parent::__construct(
            $this->memberRepository
        );
    }

    public function createMember(MemberCreateDto $memberCreateDto): MemberDto
    {
        return $this->addEntityDto($memberCreateDto,Member::class,MemberDto::class);
    }
}
