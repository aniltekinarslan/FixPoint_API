<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    //public function toArray($request)
    //{
    //    $role = $this->roles->first();
    //
    //    return array_merge(
    //        $this->only([
    //                        'id',
    //                        'client_id',
    //                        'name',
    //                        'username',
    //                        'surname',
    //                        'email',
    //                        'skype',
    //                        'note',
    //                        'is_active',
    //                        'is_ip',
    //                        'is_g2fa',
    //                        'is_master',
    //                        'last_seen_at',
    //                        'started_at',
    //                        'created_at',
    //                        'updated_at'
    //                    ]),
    //        [
    //            'role_id'     => optional($role)->id,
    //            'role'        => optional($role)->name,
    //            'permissions' => $this->permissions,
    //            'ips'         => UserIpResource::collection($this->ips()->withoutGlobalScopes()->get())->keyBy('id'),
    //            'client'      => new UserClientResource($this->client),
    //            $this->mergeWhen($request->user() && $request->user()->id == $this->id, function () {
    //                return [
    //                    'login_g2fa'         => $this->checkG2fa(),
    //                ];
    //            }),
    //        ]
    //    );
    //}
    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function with($request)
    {
        return [
            'status' => true
        ];
    }
}
