<?php

namespace App\Services;

use App\Models\TeamMember;

class TeamMemberService extends BaseService
{
    public function list(string $type)
    {
        return TeamMember::where('type', $type)
            ->with('media')
            ->orderBy('order')
            ->paginate(15);
    }

    public function create(array $data): TeamMember
    {
        $file = $data['photo'] ?? $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['photo'], $data['image'], $data['remove_media']);

        $member = TeamMember::create($data);
        $this->attachMedia($member, $file, $externalLink, $removeMedia, 'team_photos');
        return $member;
    }

    public function update(TeamMember $member, array $data): TeamMember
    {
        $file = $data['photo'] ?? $data['image'] ?? null;
        $externalLink = $data['external_link'] ?? null;
        $removeMedia = !empty($data['remove_media']);
        unset($data['photo'], $data['image'], $data['remove_media']);

        $member->update($data);
        $this->attachMedia($member, $file, $externalLink, $removeMedia, 'team_photos');
        return $member;
    }


    public function delete(TeamMember $member): bool
    {
        return $member->delete();
    }
}