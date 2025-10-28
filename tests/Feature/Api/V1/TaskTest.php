<?php

namespace Tests\Feature\Api\V1;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_list_of_tasks(): void
    {
        // Arrange: tạo 2 bản ghi Task giả trong database
        $tasks = Task::factory()->count(2)->create();

        // Act: Gửi request GET đến endpoint /api/v1/tasks
        $response = $this->getJson('/api/v1/tasks');

        // Assert: 
        // 1. Response trả về mã trạng thái 200 (OK)
        // 2. JSON có 2 phần tử trong trường 'data'
        // 3. Cấu trúc mỗi phần tử có các trường: id, name, is_completed
        $response->assertOk();
        $response->assertJsonCount(2, 'data');
        $response->assertJsonStructure([
            'data' => [
                ['id', 'name', 'is_completed']
            ]
        ]);
    }

    public function test_user_can_get_single_task(): void
    {
        // Arange: tạo 1 bản ghi Task giả
        $task = Task::factory()->create();

        // Act: Gửi request GET đến endpoint /api/v1/tasks/{id}
        $response = $this->getJson('/api/v1/tasks/' . $task->id);

        // Assert:
        // 1. Response trả về mã 200
        // 2. JSON có cấu trúc chứa các trường id, name, is_completed
        // 3. Giá trị trả về trùng khớp với dữ liệu task vừa tạo
        $response->assertOk();
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'is_completed']
        ]);
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'name' => $task->name,
                'is_completed' => $task->is_completed,
            ]
        ]);
    }
    public function test_user_can_complete_task(): void
    {
        $task = Task::factory()->create(['is_completed' => false]);

        $response = $this->patchJson("/api/v1/tasks/{$task->id}/complete", [
            'is_completed' => true,
        ]);

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $task->id,
                'is_completed' => true,
            ]
        ]);

        // Kiểm tra DB thực sự đã update
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_completed' => true,
        ]);
    }
    // `POST /tasks` → create a new task
    public function test_user_can_create_a_task(): void
    {
        $response = $this->postJson('/api/v1/tasks', [
            'name' => 'New task'
        ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => ['id', 'name', 'is_completed']
        ]);
        $this->assertDatabaseHas('tasks', [
            'name' => 'New task'
        ]);
    }

    public function test_user_cannot_create_invalid_task(): void
    {
        $response = $this->postJson('/api/v1/tasks', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    // `PUT /tasks/{id}` → update existing task
    public function test_user_can_update_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson('/api/v1/tasks/' . $task->id, [
            'name' => 'Updated Task'
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'name' => 'Updated Task'
        ]);
    }

    public function test_user_cannot_update_task_with_invalid_data(): void
    {
        $task = Task::factory()->create();

        $response = $this->putJson('/api/v1/tasks/' . $task->id, [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    // `PATCH /tasks/{id}/complete` → mark the task as completed or incomplete
    public function test_user_can_toggle_task_completion(): void
    {
        $task = Task::factory()->create([
            'is_completed' => false
        ]);

        $response = $this->patchJson('/api/v1/tasks/' . $task->id . '/complete', [
            'is_completed' => true
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'is_completed' => true
        ]);
    }

    public function test_user_cannot_toggle_completed_with_invalid_data(): void
    {
        $task = Task::factory()->create();

        $response = $this->patchJson('/api/v1/tasks/' . $task->id . '/complete', [
            'is_completed' => 'yes'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['is_completed']);
    }

    // `DELETE /tasks/{id}` → delete a task
    public function test_user_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson('/api/v1/tasks/' . $task->id);

        $response->assertNoContent();
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}