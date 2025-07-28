import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { BreadCrumbItem } from '@types';
import { router } from '@inertiajs/react';

const breadcrumbs: BreadCrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tasks', href: '/tasks' },
];

export default function Index({ tasks }) {
  const taskArray = tasks?.data || [];

  const handleEdit = (taskId) => {
    router.get(`tasks/${taskId}/edit`);
  };

  const handleDelete = (taskId) => {
    if (confirm('Are you sure you want to delete this task?')) {
      router.delete(`tasks/${taskId}`, {
        onError: (errors) => {
          console.log('Delete errors:', errors);
          alert('Failed to delete task');
        },
      });
    }
  };

  const handleStatusChange = (taskId, e) => {
    const newStatusValue = e.target.value;
    console.log('Changing status for task:', taskId, 'to:', newStatusValue);
    
    // Try different data formats - uncomment one at a time to test
    
    // Option 1: Send as boolean
    const statusBoolean = newStatusValue === 'completed';
    const data = { completed: statusBoolean };
    
    // Option 2: Send as string (uncomment to try)
    // const data = { status: newStatusValue };
    
    // Option 3: Send both (uncomment to try)
    // const data = { 
    //   completed: newStatusValue === 'completed',
    //   status: newStatusValue 
    // };
    
    // Option 4: Send as integer (uncomment to try)
    // const data = { completed: newStatusValue === 'completed' ? 1 : 0 };
    
    console.log('Sending data:', data);
    console.log('URL:', `tasks/${taskId}`);
    
    router.put(
      `tasks/${taskId}`,
      data,
      {
        onStart: () => console.log('Request started'),
        onProgress: (progress) => console.log('Progress:', progress),
        onSuccess: (response) => {
          console.log('Success response:', response);
          router.reload();
        },
        onError: (errors) => {
          console.log('Error response:', errors);
          console.log('Full error object:', JSON.stringify(errors, null, 2));
          alert(`Failed to update status. Error: ${JSON.stringify(errors)}`);
        },
        onFinish: () => console.log('Request finished'),
      }
    );
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <div className="p-6">
        <div className="flex items-center justify-between mb-4">
          <h1 className="text-2xl font-bold">Tasks ({taskArray.length})</h1>
          <button
            onClick={() => router.get('tasks/create')}
            className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
          >
            + Create Task
          </button>
        </div>

        {taskArray.length === 0 ? (
          <div className="text-center py-12">
            <p className="mt-4 text-gray-500">
              No tasks found. Click <a href="tasks/create">here</a> to create new tasks.
            </p>
          </div>
        ) : (
          <ul className="space-y-2">
            {taskArray.map((task) => (
              <li
                key={task.id}
                className="p-3 border rounded flex items-start gap-3"
              >
                <div className="flex flex-col gap-2">
                  <select
                    name="completed"
                    value={task.completed ? 'completed' : 'pending'}
                    onChange={(e) => handleStatusChange(task.id, e)}
                    className="px-3 py-1 text-sm rounded border focus:outline-none"
                  >
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                  </select>
                </div>
                
                <div className="flex-1">
                  <h3 className="font-semibold">{task.title || 'No title'}</h3>
                  {task.description && (
                    <p className="text-gray-600 mt-1">{task.description}</p>
                  )}
                  <div className="text-sm text-gray-500 mt-2">
                    Status: {task.completed ? 'Completed' : 'Pending'}
                    <br />
                    <span className="text-xs">
                      (Task ID: {task.id}, Completed value: {JSON.stringify(task.completed)})
                    </span>
                  </div>
                </div>
                
                <div className="flex flex-col gap-2">
                  <button
                    onClick={() => handleEdit(task.id)}
                    className="px-3 py-1 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 transition"
                    title="Edit task"
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDelete(task.id)}
                    className="px-3 py-1 bg-red-500 text-white text-sm rounded hover:bg-red-600 transition"
                    title="Delete task"
                  >
                    Delete
                  </button>
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>
    </AppLayout>
  );
}