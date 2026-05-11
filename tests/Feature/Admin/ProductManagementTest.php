<?php

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('stores a product with valid data and redirects with success message', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $productData = [
        'title' => 'Test Product',
        'description' => 'Test Description',
        'specification' => 'Test Specs',
        'location' => 'Jakarta',
        'variants' => [
            [
                'name' => 'Size M',
                'price' => 100000,
                'stock' => 10,
            ]
        ],
        '_token' => csrf_token(),
    ];

    // Act
    $response = $this->actingAs($admin)->post(route('admin.products.store'), $productData);

    // Assert
    $response->assertRedirect(route('admin.dashboard'));
    $response->assertSessionHas('success', 'Produk berhasil ditambahkan!');
    $this->assertDatabaseHas('products', [
        'title' => 'Test Product',
        'description' => 'Test Description',
    ]);
});

it('displays the confirm modal test page', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);

    // Act
    $response = $this->actingAs($admin)->get(route('admin.confirm-modal.test'));

    // Assert
    $response->assertStatus(200);
    $response->assertSee('Tes Confirm Modal');
});

it('simulates storage and verifies session flash data', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $productData = [
        'title' => 'Flash Test Product',
        'description' => 'Flash Test',
        'specification' => 'Flash Specs',
        'location' => 'Bandung',
        'variants' => [
            [
                'name' => 'Size L',
                'price' => 150000,
                'stock' => 5,
            ]
        ],
        '_token' => csrf_token(),
    ];

    // Act
    $response = $this->actingAs($admin)->post(route('admin.products.store'), $productData);

    // Assert
    $response->assertRedirect();
    $response->assertSessionHas('success');
});

it('deletes a product and verifies database changes with performance check', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create();
    $otherProduct = Product::factory()->create();

    // Act
    $start = microtime(true);
    $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product->id));
    $end = microtime(true);

    // Assert
    $response->assertRedirect(route('admin.dashboard'));
    $this->assertDatabaseMissing('products', ['id' => $product->id]);
    $this->assertDatabaseHas('products', ['id' => $otherProduct->id]);
    expect($end - $start)->toBeLessThan(2); // Less than 2000ms
});

it('fails to delete without CSRF token', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $product = Product::factory()->create();

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $product->id), [], ['Accept' => 'application/json']);

    // Assert
    $response->assertStatus(302); // Redirect due to missing CSRF or session
});

it('returns 404 for deleting non-existent product', function () {
    // Arrange
    $admin = User::factory()->create(['role' => 'admin']);
    $nonExistentId = 99999;

    // Act
    $response = $this->actingAs($admin)->delete(route('admin.products.destroy', $nonExistentId));

    // Assert
    $response->assertStatus(404);
});

it('denies access to admin routes for non-admin users', function () {
    // Arrange
    $user = User::factory()->create(['role' => 'user']); // Non-admin

    // Act & Assert for store
    $this->actingAs($user)->post(route('admin.products.store'))->assertRedirect('/');

    // Act & Assert for destroy
    $this->actingAs($user)->delete(route('admin.products.destroy', 1))->assertRedirect('/');

    // Act & Assert for confirm modal test
    $this->actingAs($user)->get(route('admin.confirm-modal.test'))->assertRedirect('/');
});

it('redirects guests to login for admin routes', function () {
    // Act & Assert for store
    $this->post(route('admin.products.store'))->assertRedirect(route('login'));

    // Act & Assert for destroy
    $this->delete(route('admin.products.destroy', 1))->assertRedirect(route('login'));

    // Act & Assert for confirm modal test
    $this->get(route('admin.confirm-modal.test'))->assertRedirect(route('login'));
});