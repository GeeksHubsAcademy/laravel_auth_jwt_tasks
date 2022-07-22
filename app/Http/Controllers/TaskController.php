<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function createTask(Request $request)
    {
        try {
            Log::info("Creating a task");

            $validator = Validator::make($request->all(), [
                'title' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    400
                );
            };

            $title = $request->input('title');
            $userId = auth()->user()->id;

            $task = new Task();
            $task->title = $title;
            $task->user_id = $userId;

            $task->save();


            return response()->json(
                [
                    'success' => true,
                    'message' => "Task created"
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error creating task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error creating tasks"
                ],
                500
            );
        }
    }

    public function getAllTasks()
    {
        try {
            Log::info("Getting all Tasks");
            $userId = auth()->user()->id;

            // $tasks = Task::query()
            //     ->where('user_id',$userId)
            //     ->get()
            //     ->toArray();

            $tasks = User::query()->find($userId)->tasks;

            return response()->json(
                [
                    'success' => true,
                    'message' => "Get all Tasks",
                    'data' => $tasks
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error getting task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting tasks"
                ],
                500
            );
        }
    }

    public function getTaskById($id)
    {
        try {
            $userId = auth()->user()->id;

            $task = Task::query()
                ->where('id', '=', $id)
                ->where('user_id', '=', $userId)
                ->get()
                ->toArray();

            if (!$task) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "Task doesnt exists"
                    ],
                    404
                );
            };

            return response()->json(
                [
                    'success' => true,
                    'message' => "Get by Task",
                    'data' => $task
                ],
                200
            );

        } catch (\Exception $exception) {
            Log::error("Error getting task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting tasks"
                ],
                500
            );
        }
    }

    public function updateTask(Request $request, $id)
    {
        try {
            Log::info("Updating task");
            $validator = Validator::make($request->all(), [
                'title' => 'string',
                'status' => ['boolean'],
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => $validator->errors()
                    ],
                    400
                );
            };

            $userId = auth()->user()->id;


            $task = Task::query()->where('user_id', $userId)->find($id);

            // dd($task);

            if (!$task) {
                return response()->json(
                    [
                        'success' => true,
                        'message' => "Task doesnt exists"
                    ],
                    404
                );
            }

            $title = $request->input('title');
            $status = $request->input('status');

            if (isset($title)) {
                $task->title = $title;
            }

            if (isset($status)) {
                $task->status = $status;
            }

            $task->save();

            return response()->json(
                [
                    'success' => true,
                    'message' => "Task " . $id . " updated"
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error updating task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error updating task"
                ],
                500
            );
        }
    }

    public function getUserByIdTask($id)
    {
        try {
            $task = Task::query()->find($id);

            $user = $task->user;

            return response()->json(
                [
                    'success' => true,
                    'message' => "User retrieved",
                    'data' =>   $user
                ],
                200
            );
        } catch (\Exception $exception) {
            Log::error("Error updating task: " . $exception->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => "Error getting task by id"
                ],
                500
            );
        }
    }
}
