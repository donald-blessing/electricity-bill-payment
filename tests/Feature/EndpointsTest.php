<?php

use App\Enums\BillProvider;
use App\Enums\BillStatus;
use App\Events\BillCreated;
use App\Events\LowBalanceDetected;
use App\Events\PaymentCompleted;
use App\Models\Bill;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Valorin\Random\Random;

uses(RefreshDatabase::class, WithFaker::class);

test('can register new user', function () {
    $payload = [
        'name'                  => $this->faker->name,
        'email'                 => $this->faker->unique()->safeEmail,
        'password'              => 'password',
        'password_confirmation' => 'password',
        'phone'                 => "080".Random::string(8, false, false, true, false),
    ];

    $response = $this->postJson('api/register', $payload);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'user',
            'token',
        ]);
});

test('can login user', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $payload = [
        'email'    => $user->email,
        'password' => 'password123',
    ];

    $response = $this->postJson('api/login', $payload);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'user',
            'token',
        ]);
});

test('cannot login user with wrong credentials', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $payload = [
        'email'    => $user->email,
        'password' => 'password',
    ];

    $response = $this->postJson('api/login', $payload);

    $response->assertStatus(422)
        ->assertJson(['message' => 'Invalid login credentials']);
});
test('can logout', function () {
    $user = User::factory()->create([
        'password' => 'password123',
    ]);

    $this->actingAs($user);

    $response = $this->postJson('api/logout');

    $response->assertSuccessful()
        ->assertJson(['message' => 'Logged out successfully']);
});

test('can add funds to a wallet', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $wallet = $user->wallet;

    $response = $this->postJson("/api/wallets/$wallet->id/add-funds", [
        'amount' => 500.00,
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'wallet',
            'message',
        ]);
});

test('cannot add negative funds to a wallet', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $wallet = $user->wallet;

    $response = $this->postJson("/api/wallets/$wallet->id/add-funds", [
        'amount' => -50.00,
    ]);

    $response->assertStatus(422);
});

test('can verify a new electricity bill', function () {
    Event::fake();
    $user = User::factory()->create();
    $this->actingAs($user);

    //fund wallet
    $wallet          = $user->wallet;
    $wallet->balance = 1000.00;
    $wallet->save();

    $response = $this->postJson('api/electricity/verify', [
        'amount'   => 100.00,
        'provider' => $this->faker->randomElement(BillProvider::toArray()),
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message', 'bill',
        ]);
    Event::assertDispatched(BillCreated::class);
});
test('cannot verify a new electricity bill with low balance', function () {
    Event::fake();
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('api/electricity/verify', [
        'amount'   => 100.00,
        'provider' => $this->faker->randomElement(BillProvider::toArray()),
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
        ]);
    Event::assertDispatched(LowBalanceDetected::class);
});

test('cannot verify a bill with invalid amount', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('api/electricity/verify', [
        'amount'   => -50.00,
        'provider' => $this->faker->randomElement(BillProvider::toArray()),
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['amount']);
});

test('cannot process a payment for a bill with insufficient funds', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Wallet::factory()->create([
        'user_id' => $user->id,
        'balance' => 50.00,
    ]);

    $bill = Bill::factory()->create([
        'amount'  => 100.00,
        'status'  => BillStatus::PENDING,
        'user_id' => $user->id,
    ]);

    $response = $this->postJson("/api/vend/$bill->id/pay");

    $response->assertStatus(422)
        ->assertJson(['message' => 'Insufficient wallet funds']);
});

test('can process a payment for a bill', function () {
    Event::fake();

    $user = User::factory()->create();
    $this->actingAs($user);

    Wallet::query()->where(['user_id' => $user->id])->update([
        'balance' => 200.00,
    ]);

    $bill = Bill::factory()->create([
        'user_id' => $user->id,
        'amount'  => 100.00,
        'status'  => BillStatus::PENDING,
    ]);

    $response = $this->postJson("/api/vend/$bill->id/pay");

    $response->assertStatus(200)
        ->assertJsonStructure(['token', 'message']);

    $this->assertDatabaseHas('bills', [
        'id'     => $bill->id,
        'status' => BillStatus::PAID,
    ]);

    $this->assertDatabaseHas('wallets', [
        'id'      => $user->wallet->id,
        'balance' => 100.00,
    ]);

    Event::assertDispatched(PaymentCompleted::class);
});

test('cannot process a payment for a bill that is already paid', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $bill = Bill::factory()->create([
        'amount'  => 100.00,
        'status'  => BillStatus::PAID,
        'user_id' => $user->id,
    ]);

    $response = $this->postJson("/api/vend/$bill->id/pay");

    $response->assertStatus(422)
        ->assertJson(['message' => 'Bill has already been paid']);
});


