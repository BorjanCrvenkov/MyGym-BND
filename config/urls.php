<?php

return [
    'frontend_url'                             => env("FRONTEND_URL", 'http://localhost:3000/'),
    'frontend_reset_password_url'              => env("FRONTEND_RESET_PASSWORD_URL", 'http://localhost:3000/password/reset'),
    'frontend_business_plans_url'              => env('FRONTEND_BUSINESS_PLANS_URL', 'http://localhost:3000/business?plans'),
    'frontend_business_problems_url'           => env('FRONTEND_BUSINESS_PROBLEMS_URL', 'http://localhost:3000/business/gyms/view/{gym_id}?tab=problems'),
    'frontend_member_gym_membership_types_url' => env('FRONTEND_MEMBER_GYM_MEMBERSHIP_TYPES_URL', 'http://localhost:3000/member/gyms?gymId={gym_id}{gym_id}&tab=memberships'),
    'frontend_member_main_page_url'            => env('FRONTEND_MEMBER_MAIN_PAGE_URL', 'http://localhost:3000/member'),
    'frontend_profile_page_url' => env('FRONTEND_PROFILE_PAGE_URL', 'http://localhost:3000/employee/users/view/{user_id}')
];
