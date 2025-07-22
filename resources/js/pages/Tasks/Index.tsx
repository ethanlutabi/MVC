import React from 'react';
import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import AppLayout from '@/layouts/app-layout';
import { BreadCrumbItem } from '@types';
import { usePage, useForm, router } from '@inertiajs/react';

const breadcrumbs: BreadCrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tasks',
        href: '/tasks',
    }
];

export default function Index({ tasks }) {
  // Extract the array from Laravel Resource Collection
  const taskArray = tasks?.data || [];
  
  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <div className="p-6">
        <h1 className="text-2xl font-bold mb-4">Tasks ({taskArray.length})</h1>
        
        {/* Actual content */}
        {taskArray.length === 0 ? (
          <div className="text-center py-12">
            <PlaceholderPattern />
            <p className="mt-4 text-gray-500">No tasks found.</p>
          </div>
        ) : (
          <ul className="space-y-2">
            {taskArray.map((task, index) => (
              <li key={task?.id || index} className="p-3 border rounded">
                <h3 className="font-semibold">{task?.title || 'No title'}</h3>
                {task?.description && (
                  <p className="text-gray-600 mt-1">{task.description}</p>
                )}
                <div className="text-sm text-gray-500 mt-2">
                  Status: {task?.is_completed ? 'Completed' : 'Pending'}
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>
    </AppLayout>
  );
}