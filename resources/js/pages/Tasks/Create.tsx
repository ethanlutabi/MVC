import React from 'react';
import { useForm, router, usePage } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import { BreadCrumbItem } from '@types';

const breadcrumbs: BreadCrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tasks',     href: '/tasks' },
  { title: 'Create',    href: '/tasks/create' },
];

export default function Create() {
  const form = useForm({
    title:       '',
    description: '',
  });

  const handleSubmit = (e) => {
    e.preventDefault();
    form.post(route('tasks.store'), {
      onSuccess: () => {
       // form.reset();
        // you could reset or leave it to the controller’s redirect
      },
    });
  };

  return (
    <AppLayout
      breadcrumbs={[
        { title: 'Dashboard', href: '/dashboard' },
        { title: 'Tasks',     href: '/tasks'     },
        { title: 'Create',    href: '/tasks/create' },
      ]}
    >
      <div className="p-6 max-w-md mx-auto">
        <h1 className="text-2xl font-bold mb-6">Create New Task</h1>

        <form onSubmit={handleSubmit} className="space-y-4">
          {/* Title Field */}
          <div>
            <label htmlFor="title" className="block font-medium mb-1">
              Title <span className="text-red-500">*</span>
            </label>
            <input
              id="title"
              type="text"
              value={form.data.title}
              onChange={e => form.setData('title', e.target.value)}
              className={`w-full border rounded px-3 py-2 focus:outline-none ${
                form.errors.title ? 'border-red-500' : 'border-gray-300'
              }`}
            />
            {form.errors.title && (
              <p className="text-red-600 text-sm mt-1">{form.errors.title}</p>
            )}
          </div>

          {/* Description Field */}
          <div>
            <label htmlFor="description" className="block font-medium mb-1">
              Description
            </label>
            <textarea
              id="description"
              rows={4}
              value={form.data.description}
              onChange={e => form.setData('description', e.target.value)}
              className={`w-full border rounded px-3 py-2 focus:outline-none ${
                form.errors.description ? 'border-red-500' : 'border-gray-300'
              }`}
            />
            {form.errors.description && (
              <p className="text-red-600 text-sm mt-1">
                {form.errors.description}
              </p>
            )}
          </div>

          {/* Buttons */}
          <div className="flex items-center space-x-2">
            <button
              type="submit"
              disabled={form.processing}
              className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition disabled:opacity-50"
            >
              {form.processing ? 'Creating…' : 'Create Task'}
            </button>
            <button
              type="button"
              onClick={() => route('tasks.index') && window.location.replace(route('tasks.index'))}
              className="px-4 py-2 border rounded hover:bg-gray-100 transition"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </AppLayout>
  );
}
