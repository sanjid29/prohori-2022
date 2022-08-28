<?php

namespace App\Projects\DefaultProject\Modules\Users;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            // 'uuid' => $this->uuid,
            // 'project_id' => $this->project_id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'name_ext' => $this->name_ext,
            'slug' => $this->slug,
            'email' => $this->email,
            // 'password' => $this->password,
            // 'remember_token' => $this->remember_token,
            'api_token' => $this->api_token,
            'api_token_generated_at' => $this->api_token_generated_at,
            'is_tenant_editable' => $this->is_tenant_editable,
            'permissions' => $this->permissions,
            'is_active' => $this->is_active,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'name_initial' => $this->name_initial,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'device_token' => $this->device_token,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'county' => $this->county,
            'country_id' => $this->country_id,
            'country_name' => $this->country_name,
            'zip_code' => $this->zip_code,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'first_login_at' => $this->first_login_at,
            'last_login_at' => $this->last_login_at,
            'auth_token' => $this->auth_token,
            'email_verified_at' => $this->email_verified_at,
            'email_verification_code' => $this->email_verification_code,
            'currency' => $this->currency,
            'social_account_id' => $this->social_account_id,
            'social_account_type' => $this->social_account_type,
            'dob' => $this->dob,
            'group_ids' => $this->group_ids,
            // 'is_test' => $this->is_test,

        ];
    }
}