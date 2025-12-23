<?php

namespace Database\Seeders;

use App\Models\CastingApplication;
use App\Models\CastingRequirement;
use App\Models\TalentProfile;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PaymentTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();
        
        // Ensure we have some requirements and talents
        $requirements = CastingRequirement::all();
        $talents = TalentProfile::all();

        if ($requirements->isEmpty() || $talents->isEmpty()) {
            $this->command->info('Requirements or Talents table is empty. Please run SampleDataSeeder first.');
            return;
        }

        $adminId = User::where('type', User::TYPE_ADMIN)->value('id') ?? 1;
        $superAdminId = User::where('is_super_admin', true)->value('id') ?? $adminId;

        $scenarios = [
            'pending',
            'requested',
            'approved',
            'released',
            'received',
            'rejected'
        ];

        for ($i = 0; $i < 30; $i++) {
            $requirement = $requirements->random();
            $talent = $talents->random();
            $scenario = Arr::random($scenarios);
            
            $paymentRequestedAt = null;
            $paymentApprovedAt = null;
            $paymentReleasedAt = null;
            $paymentReceivedAt = null;
            $rating = null;
            $reviews = null;

            if (in_array($scenario, ['requested', 'approved', 'released', 'received', 'rejected'])) {
                $paymentRequestedAt = $faker->dateTimeBetween('-30 days', '-20 days');
            }

            if (in_array($scenario, ['approved', 'released', 'received'])) {
                $paymentApprovedAt = $faker->dateTimeBetween('-19 days', '-10 days');
            }

            if (in_array($scenario, ['released', 'received'])) {
                $paymentReleasedAt = $faker->dateTimeBetween('-9 days', '-5 days');
            }

            if ($scenario === 'received') {
                $paymentReceivedAt = $faker->dateTimeBetween('-4 days', 'now');
                // Paid ones often have reviews
                if ($faker->boolean(80)) {
                    $rating = $faker->numberBetween(3, 5);
                    $reviews = $faker->sentence(10);
                }
            }
            
            // Randomly add reviews to others too
            if (!$rating && $faker->boolean(20)) {
                $rating = $faker->numberBetween(1, 5);
                $reviews = $faker->sentence(10);
            }

            CastingApplication::create([
                'casting_requirement_id'             => $requirement->id,
                'talent_profile_id'                  => $talent->id,
                'rate'                               => $requirement->rate_per_model ?? $faker->numberBetween(200, 1000),
                'rate_offered'                       => $faker->boolean(30) ? $faker->numberBetween(200, 1000) : null,
                'status'                             => 'selected', // Usually only selected ones have payment flows
                'payment_status'                     => $scenario,
                'payment_requested_at'              => $paymentRequestedAt,
                'payment_requested_by_admin_id'      => $paymentRequestedAt ? $adminId : null,
                'payment_approved_at'               => $paymentApprovedAt,
                'payment_approved_by_super_admin_id' => $paymentApprovedAt ? $superAdminId : null,
                'payment_released_at'               => $paymentReleasedAt,
                'payment_received_at'               => $paymentReceivedAt,
                'rating'                             => $rating,
                'reviews'                            => $reviews,
                'admin_notes'                        => $faker->boolean(30) ? $faker->sentence() : null,
                'payment_rejection_reason'           => $scenario === 'rejected' ? $faker->sentence() : null,
            ]);
        }

        $this->command->info('30 test payment applications created successfully.');
    }
}
