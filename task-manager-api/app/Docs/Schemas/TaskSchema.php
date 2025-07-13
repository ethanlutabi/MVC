<?php

namespace App\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     required={"title"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Buy groceries"),
 *     @OA\Property(property="description", type="string", nullable=true, example="Milk and eggs"),
 *     @OA\Property(property="completed", type="boolean", example=false),
 *     @OA\Property(property="priority", type="string", enum={"low","medium","high"}, example="medium"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-07-15"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\Schema(
 *     schema="TaskCreateRequest",
 *     type="object",
 *     required={"title"},
 *     @OA\Property(property="title", type="string", example="New Task"),
 *     @OA\Property(property="description", type="string", example="Task details"),
 *     @OA\Property(property="priority", type="string", enum={"low","medium","high"}, example="low"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-07-20")
 * )
 *
 * @OA\Schema(
 *     schema="TaskUpdateRequest",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Updated Task"),
 *     @OA\Property(property="description", type="string", example="Updated details"),
 *     @OA\Property(property="completed", type="boolean", example=true),
 *     @OA\Property(property="priority", type="string", enum={"low","medium","high"}, example="high"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-07-30")
 * )
 */
class TaskSchema {}
