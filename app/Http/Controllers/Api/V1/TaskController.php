<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;

use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // return TaskResource::collection(Task::all());
        return Task::all()->toResourceCollection();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return $task->toResource()->additional([
            'message' => 'Tạo nhiệm vụ thành công.'
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        // return new TaskResource($task);
        // return TaskResource::make($task);
        return $task->toResource();
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return $task->toResource()->additional([
            'message' => 'Cập nhập nhiệm vụ thành công.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
        // return response()->json([
        //     'message' => 'Xóa nhiệm vụ thành công.'
        // ], 200);
    }

}