<?php

namespace Tests\Feature;

use App\Models\CostCategory;
use App\Models\User;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CostCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $company = Company::factory()->create();
        $user = User::factory()->create(['company_id' => $company->id]);
        $this->actingAs($user);
        return [$user, $company];
    }

    public function test_save_new_cost_category()
    {
        [$user, $company] = $this->authenticate();

        $response = $this->post(route('app.cost-category.save'), [
            'name' => 'Internet',
            'description' => 'Biaya langganan internet',
        ]);

        $response->assertRedirect(route('app.cost-category.index'));
        $this->assertDatabaseHas('cost_categories', [
            'name' => 'Internet',
            'company_id' => $user->company_id,
        ]);
    }

    public function test_save_existing_cost_category_validation_error()
    {
        [$user, $company] = $this->authenticate();

        CostCategory::create([
            'name' => 'Internet',
            'company_id' => $user->company_id,
        ]);

        $response = $this->post(route('app.cost-category.save'), [
            'name' => 'Internet', // Duplicate name
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_update_cost_category()
    {
        [$user, $company] = $this->authenticate();

        $category = CostCategory::create([
            'name' => 'Listrik',
            'company_id' => $user->company_id,
        ]);

        $response = $this->post(route('app.cost-category.save'), [
            'id' => $category->id,
            'name' => 'Listrik Updated',
            'description' => 'Biaya listrik bulanan',
        ]);

        $response->assertRedirect(route('app.cost-category.index'));
        $this->assertDatabaseHas('cost_categories', [
            'id' => $category->id,
            'name' => 'Listrik Updated',
        ]);
    }

    public function test_fetch_cost_category_data()
    {
        [$user, $company] = $this->authenticate();

        CostCategory::factory()->count(5)->create([
            'company_id' => $user->company_id,
        ]);

        $response = $this->getJson(route('app.cost-category.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
        ]);
    }

    // public function test_fetch_cost_category_detail()
    // {
    //     [$user, $company] = $this->authenticate();

    //     $category = CostCategory::factory()->create([
    //         'company_id' => $user->company_id,
    //         'name' => 'Test',
    //     ]);

    //     $response = $this->get(route('app.cost-category.detail', $category->id));

    //     $response->assertStatus(200);
    //     $response->assertSee($category->name);
    // }

    public function test_delete_cost_category()
    {
        [$user, $company] = $this->authenticate();

        $category = CostCategory::factory()->create([
            'company_id' => $user->company_id,
        ]);

        $response = $this->post(route('app.cost-category.delete', $category->id));

        $response->assertStatus(200);

        $this->assertNotNull(
            CostCategory::withTrashed()->find($category->id)->deleted_at
        );
    }
}
