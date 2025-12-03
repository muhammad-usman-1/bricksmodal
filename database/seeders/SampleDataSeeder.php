<?php

namespace Database\Seeders;

use App\Models\CastingApplication;
use App\Models\CastingRequirement;
use App\Models\Label;
use App\Models\Role;
use App\Models\TalentProfile;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();
        $panelDateTimeFormat = config('panel.date_format') . ' ' . config('panel.time_format');

        $labelPool = collect();
        for ($i = 0; $i < 8; $i++) {
            $name = ucfirst($faker->unique()->words(2, true));
            $labelPool->push(Label::firstOrCreate(['name' => $name]));
        }
        $faker->unique(true);
        $labelIds = $labelPool->pluck('id')->all();

        // create talents
        $talentUsers = collect();
        for ($i = 0; $i < 10; $i++) {
            $user = User::firstOrCreate(
                ['email' => "talent{$i}@example.com"],
                [
                    'name'     => $faker->name(),
                    'password' => Hash::make('password'),
                    'type'     => User::TYPE_TALENT,
                ]
            );
            $talentUsers->push($user);

            $hasCard = $faker->boolean(70);
            $cardNumber = $hasCard ? preg_replace('/\D/', '', $faker->creditCardNumber()) : null;

            $talentProfile = TalentProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'legal_name'         => $user->name,
                    'display_name'       => $user->name,
                    'daily_rate'         => $faker->numberBetween(100, 500),
                    'hourly_rate'        => $faker->numberBetween(20, 80),
                    'height'             => $faker->numberBetween(150, 195),
                    'weight'             => $faker->numberBetween(45, 90),
                    'verification_status'=> $faker->randomElement(['approved', 'pending', 'rejected']),
                    'onboarding_step'    => $faker->randomElement(['profile', 'id-documents', 'headshot-center', 'pending-approval', 'pending']),
                    'onboarding_completed_at' => $faker->boolean ? $faker->dateTimeBetween('-1 month')->format('Y-m-d H:i:s') : null,
                    'gender'             => $faker->randomElement(['male', 'female']),
                    'date_of_birth'      => $faker->date(),
                    'card_number'        => $cardNumber,
                    'card_holder_name'   => $cardNumber ? $user->name : null,
                ]
            );

            if (!empty($labelIds)) {
                $maxLabels = min(4, count($labelIds));
                $labelCount = $maxLabels ? $faker->numberBetween(0, $maxLabels) : 0;
                $talentProfile->labels()->sync($labelCount ? $faker->randomElements($labelIds, $labelCount) : []);
            } else {
                $talentProfile->labels()->sync([]);
            }
        }

        // admin management sample users
        $adminRoles = Role::whereIn('title', ['admin', 'creative'])->pluck('id', 'title');
        foreach (['Operations Admin', 'Casting Creative', 'Finance Admin'] as $index => $name) {
            $roleKey = $index % 2 === 0 ? 'admin' : 'creative';
            $admin = User::firstOrCreate(
                ['email' => str($name)->slug('-') . '@example.com'],
                [
                    'name'     => $name,
                    'password' => Hash::make('password'),
                    'type'     => User::TYPE_ADMIN,
                ]
            );
            if (isset($adminRoles[$roleKey])) {
                $admin->roles()->syncWithoutDetaching([$adminRoles[$roleKey]]);
            }
        }

        // casting requirements
        $requirements = collect();
        for ($i = 0; $i < 8; $i++) {
            $requirements->push(
                CastingRequirement::updateOrCreate(
                    ['project_name' => "Campaign " . ($i + 1)],
                    [
                        'location'        => $faker->city(),
                        'notes'           => $faker->sentences(3, true),
                        'rate_per_model'  => $faker->numberBetween(150, 600),
                        'status'          => $faker->randomElement(['advertised', 'processing', 'completed']),
                        'shoot_date_time' => $faker->dateTimeBetween('+1 week', '+2 months')->format($panelDateTimeFormat),
                        'duration'        => $faker->numberBetween(1, 8) . ' hours',
                        'gender'          => $faker->randomElement(['male', 'female', 'any']),
                        'count'           => $faker->numberBetween(1, 10),
                    ]
                )
            );
        }

        $adminIds = User::where('type', User::TYPE_ADMIN)->pluck('id');
        $superAdminId = User::where('is_super_admin', true)->value('id');

        // casting applications & payments
        foreach ($requirements as $requirement) {
            foreach ($talentUsers->random(min(3, $talentUsers->count())) as $talent) {
                $status = Arr::random(array_keys(CastingApplication::STATUS_SELECT));
                $paymentStatus = Arr::random(array_keys(CastingApplication::PAYMENT_STATUS_SELECT));
                $paymentRequestedAt = in_array($paymentStatus, ['requested', 'approved', 'released', 'received'])
                    ? $faker->dateTimeBetween('-10 days', 'now')
                    : null;

                CastingApplication::updateOrCreate(
                    [
                        'casting_requirement_id' => $requirement->id,
                        'talent_profile_id'      => $talent->talentProfile->id ?? null,
                    ],
                    [
                        'rate'                          => $requirement->rate_per_model,
                        'rate_offered'                  => $faker->boolean ? $requirement->rate_per_model + $faker->numberBetween(10, 80) : null,
                        'status'                        => $status,
                        'payment_status'                => $paymentStatus,
                        'payment_requested_by_admin_id' => $paymentRequestedAt && $adminIds->isNotEmpty() ? $adminIds->random() : null,
                        'payment_requested_at'          => $paymentRequestedAt,
                        'payment_approved_by_super_admin_id' => in_array($paymentStatus, ['approved', 'released', 'received']) ? $superAdminId : null,
                        'payment_approved_at'           => in_array($paymentStatus, ['approved', 'released', 'received']) ? $faker->dateTimeBetween('-7 days', 'now') : null,
                        'payment_released_at'           => in_array($paymentStatus, ['released', 'received']) ? $faker->dateTimeBetween('-5 days', 'now') : null,
                        'payment_received_at'           => $paymentStatus === 'received' ? $faker->dateTimeBetween('-3 days', 'now') : null,
                    ]
                );
            }
        }
    }
}

