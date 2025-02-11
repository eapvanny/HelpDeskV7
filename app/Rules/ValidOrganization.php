<?php

namespace App\Rules;

use App\Http\Helpers\AppHelper;
use App\Organization;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidOrganization implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $org_id_arr = [];
        if (getAuthUser()) {
            $role_ref_id = getRefID();
            if ($role_ref_id != AppHelper::USER_SUPER_ADMIN) {
                if ($role_ref_id == AppHelper::USER_SUPER_ADMIN_ORG) {
                    $organizations = Organization::whereIn('id', getWholeOrgByHeadORG());
                } else {
                    $organizations = Organization::join('user_org', 'user_org.org_id', '=', 'organizations.id')
                        ->where('user_org.user_id', getAuthUser()->id);
                }
                $organizations = $organizations->pluck('name', 'id');
            } else {
                $organizations = Organization::pluck('name', 'id');
            }
            $org_id_arr = $organizations->keys()->toArray();

        }

        if (!in_array($value, $org_id_arr)) {
            $fail("The :attribute is doesn't exist or not authorized to the user.");
        }
    }
}
