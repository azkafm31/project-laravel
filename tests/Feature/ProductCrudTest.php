<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    public function test_can_view_product_list_page(): void
    {
        $response = $this->get('/products');
        $response->assertStatus(200);
        $response->assertSee('Product List');
        $response->assertSee('createProductModal');
    }

    public function test_api_products_returns_json(): void
    {
        $response = $this->get('/api/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            '*' => ['id', 'name', 'price', 'stock', 'description', 'image']
        ]);
    }

    public function test_can_view_edit_product_page(): void
    {
        $product = Product::first();

        $response = $this->get("/products/{$product->id}/edit");
        $response->assertStatus(200);
        $response->assertSee("Form Edit Data Product #{$product->id}");
        $response->assertSee(e($product->name));
        $response->assertSee((string) $product->price);
        $response->assertSee((string) $product->stock);
        $response->assertSee(e($product->description));
        $response->assertSee('name="_method" value="PUT"', false);
    }

    public function test_can_update_product_without_changing_image(): void
    {
        $product = Product::first();
        $originalImage = $product->image;

        $response = $this->put("/products/{$product->id}", [
            'name' => 'Updated Product Name',
            'price' => 999999,
            'stock' => 42,
            'description' => 'Updated description text',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success', 'Data product berhasil diperbarui.');

        $product->refresh();
        $this->assertEquals('Updated Product Name', $product->name);
        $this->assertEquals(999999, $product->price);
        $this->assertEquals(42, $product->stock);
        $this->assertEquals('Updated description text', $product->description);
        $this->assertEquals($originalImage, $product->image);
    }

    public function test_can_update_product_with_new_image(): void
    {
        $product = Product::first();

        $file = UploadedFile::fake()->create('test_product.jpg', 100, 'image/jpeg');

        $response = $this->put("/products/{$product->id}", [
            'name' => 'Product With New Image',
            'price' => 150000,
            'stock' => 10,
            'description' => 'Product updated with image',
            'image' => $file,
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success', 'Data product berhasil diperbarui.');

        $product->refresh();
        $this->assertEquals('Product With New Image', $product->name);
        $this->assertTrue(file_exists(public_path('uploads/products/' . $product->image)));

        // Clean up created test file
        @unlink(public_path('uploads/products/' . $product->image));
    }

    public function test_update_product_validation_fails_when_required_fields_are_missing(): void
    {
        $product = Product::first();

        $response = $this->from("/products/{$product->id}/edit")->put("/products/{$product->id}", [
            'name' => '',
            'price' => '',
            'stock' => '',
            'description' => '',
        ]);

        $response->assertRedirect("/products/{$product->id}/edit");
        $response->assertSessionHasErrors(['name', 'price', 'stock', 'description']);
    }

    public function test_can_store_product_via_post(): void
    {
        $response = $this->post('/products', [
            'name' => 'Brand New Product',
            'price' => 85000,
            'stock' => 20,
            'description' => 'New product description',
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success', 'Data product berhasil ditambahkan.');

        $this->assertDatabaseHas('products', [
            'name' => 'Brand New Product',
            'price' => 85000,
            'stock' => 20,
        ]);
    }

    public function test_can_delete_product(): void
    {
        $product = Product::create([
            'name' => 'Product to Delete',
            'price' => 12345,
            'stock' => 5,
            'description' => 'Will be deleted',
            'image' => 'default.jpg',
        ]);

        $productId = $product->id;

        $response = $this->delete("/products/{$productId}");
        $response->assertRedirect('/products');
        $response->assertSessionHas('success', 'Data product berhasil dihapus.');

        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }
}
