/*import React from 'react'
import { useForm, router } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { BreadCrumbItem } from '@/types'

interface Task {
  id: number
  title: string
  description: string
  completed: boolean
}

interface EditProps {
  task: {
    data: Task
  }
}

const breadcrumbs: BreadCrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tasks', href: '/tasks' },
  { title: 'Edit Task', href: '#' },
]

export default function Edit({ task }: EditProps) {
  const { data, setData, put, processing, errors } = useForm<Task>({
    id: task.data.id,
    title: task.data.title,
    description: task.data.description,
    completed: task.data.completed,
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();

    put(`/tasks/${task.data.id}`);
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <div className="p-6 max-w-2xl mx-auto">
        <h1 className="text-2xl font-bold mb-6">Edit Task</h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block font-medium mb-1">Title</label>
            <input
              type="text"
              value={data.title}
              onChange={(e) => setData('title', e.target.value)}
              className="w-full border px-3 py-2 rounded"
            />
            {errors.title && <p className="text-red-500 text-sm mt-1">{errors.title}</p>}
          </div>

          <div>
            <label className="block font-medium mb-1">Description</label>
            <textarea
              value={data.description || ''}
              onChange={(e) => setData('description', e.target.value)}
              className="w-full border px-3 py-2 rounded"
            />
            {errors.description && <p className="text-red-500 text-sm mt-1">{errors.description}</p>}
          </div>

          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              checked={data.completed}
              onChange={(e) => setData('completed', e.target.checked)}
              id="completed"
            />
            <label htmlFor="completed">Mark as completed</label>
          </div>

          <div className="mt-6">
            <button
              type="submit"
              disabled={processing}
              className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
            >
              {processing ? 'Saving...' : 'Update Task'}
            </button>
          </div>
        </form>
      </div>
    </AppLayout>
  )
}*/


import React from 'react'
import { useForm } from '@inertiajs/react'
import AppLayout from '@/layouts/app-layout'
import { BreadCrumbItem } from '@/types'

interface Task {
  id: number
  title: string
  description: string
  completed: boolean
}

interface EditProps {
  task: {
    data: Task
  }
}

const breadcrumbs: BreadCrumbItem[] = [
  { title: 'Dashboard', href: '/dashboard' },
  { title: 'Tasks', href: '/tasks' },
  { title: 'Edit Task', href: '#' },
]

export default function Edit({ task }: EditProps) {
  const { data, setData, put, processing, errors, recentlySuccessful } = useForm<Task>({
    id: task.data.id,
    title: task.data.title,
    description: task.data.description,
    completed: task.data.completed,
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    
    put(`/tasks/${task.data.id}`, {
      onSuccess: () => {
        console.log('Task updated successfully');
      },
      onError: (errors) => {
        console.error('Update failed:', errors);
      },
      onFinish: () => {
        console.log('Request finished');
      }
    });
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <div className="p-6 max-w-2xl mx-auto">
        <h1 className="text-2xl font-bold mb-6">Edit Task</h1>
        
        {recentlySuccessful && (
          <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            Task updated successfully!
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block font-medium mb-1">Title</label>
            <input
              type="text"
              value={data.title}
              onChange={(e) => setData('title', e.target.value)}
              className="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
            {errors.title && <p className="text-red-500 text-sm mt-1">{errors.title}</p>}
          </div>

          <div>
            <label className="block font-medium mb-1">Description</label>
            <textarea
              value={data.description || ''}
              onChange={(e) => setData('description', e.target.value)}
              className="w-full border px-3 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
              rows={4}
            />
            {errors.description && <p className="text-red-500 text-sm mt-1">{errors.description}</p>}
          </div>

          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              checked={data.completed}
              onChange={(e) => setData('completed', e.target.checked)}
              id="completed"
              className="rounded"
            />
            <label htmlFor="completed" className="cursor-pointer">Mark as completed</label>
          </div>

          <div className="mt-6 flex gap-4">
            <button
              type="submit"
              disabled={processing}
              className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 disabled:opacity-50 transition"
            >
              {processing ? 'Saving...' : 'Update Task'}
            </button>
            
            <button
              type="button"
              onClick={() => window.history.back()}
              className="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </AppLayout>
  )
}