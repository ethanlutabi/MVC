import React, { useState } from 'react';
import api from '../api';

export default function EditForm({ task, onSaved }) {
  const [title, setTitle] = useState(task.title || '');
  const [description, setDescription] = useState(task.description || '');
  const [completed, setCompleted] = useState(task.completed || false);
  const [priority, setPriority] = useState(task.priority || 'Low');
  const [dueDate, setDueDate] = useState(task.due_date || '');

  const handleSubmit = async e => {
    e.preventDefault();
    try {
      const updatedTask = {
        title,
        description,
        completed,
        priority,
        due_date: dueDate || null,
      };
      await api.put(`/tasks/${task.id}`, updatedTask);
      onSaved();
    } catch (error) {
      console.error('Failed to update task:', error);
      alert('Failed to update task.');
    }
  };

  return (
    <form onSubmit={handleSubmit} className="p-6 border rounded shadow mb-6 space-y-4">
      <h3 className="text-xl font-semibold">Edit Task</h3>

      <div>
        <label className="block mb-1 font-medium">Title</label>
        <input
          type="text"
          value={title}
          onChange={e => setTitle(e.target.value)}
          required
          className="w-full border p-2 rounded"
        />
      </div>

      <div>
        <label className="block mb-1 font-medium">Description</label>
        <textarea
          value={description}
          onChange={e => setDescription(e.target.value)}
          rows="3"
          className="w-full border p-2 rounded"
        />
      </div>

      <div>
        <label className="block mb-1 font-medium">Priority</label>
        <select
          value={priority}
          onChange={e => setPriority(e.target.value)}
          className="w-full border p-2 rounded"
        >
          <option>Low</option>
          <option>Medium</option>
          <option>High</option>
        </select>
      </div>

      <div>
        <label className="block mb-1 font-medium">Due Date</label>
        <input
          type="date"
          value={dueDate}
          onChange={e => setDueDate(e.target.value)}
          className="w-full border p-2 rounded"
        />
      </div>

      <div className="flex items-center space-x-2">
        <input
          type="checkbox"
          checked={completed}
          onChange={e => setCompleted(e.target.checked)}
        />
        <label>Completed</label>
      </div>

      <div className="flex gap-3">
        <button
          type="submit"
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          Save Changes
        </button>
        <button
          type="button"
          onClick={onSaved}
          className="bg-gray-300 px-4 py-2 rounded"
        >
          Cancel
        </button>
      </div>
    </form>
  );
}
