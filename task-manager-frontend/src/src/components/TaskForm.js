import React, { useState } from 'react';
import api from '../api';

export default function TaskForm({ onSaved, initial }) {
  const blank = { title: '', description: '', priority: 'medium', due_date: '' };
  const [form, setForm] = useState(initial || blank);
  const [submitting, setSubmitting] = useState(false);

  const handleChange = e => {
    const { name, value } = e.target;
    setForm(f => ({ ...f, [name]: value }));
  };

  const handleSubmit = async e => {
    e.preventDefault();
    setSubmitting(true);
    try {
      if (form.id) {
        await api.put(`/tasks/${form.id}`, form);
      } else {
        await api.post('/tasks', form);
      }
      setForm(blank);
      onSaved();
    } catch (err) {
      console.error(err);
      alert('Error saving task');
    } finally {
      setSubmitting(false);
    }
  };

  return (
    <form onSubmit={handleSubmit} className="mb-6 border p-4 rounded">
      <h3 className="text-lg font-semibold mb-2">
        {form.id ? 'Edit Task' : 'Create New Task'}
      </h3>
      <div className="mb-2">
        <input
          name="title"
          placeholder="Title"
          required
          value={form.title}
          onChange={handleChange}
          disabled={submitting}
          className="w-full border rounded px-2 py-1"
        />
      </div>
      <div className="mb-2">
        <textarea
          name="description"
          placeholder="Description"
          value={form.description}
          onChange={handleChange}
          disabled={submitting}
          className="w-full border rounded px-2 py-1"
        />
      </div>
      <div className="mb-2 flex gap-2">
        <select
          name="priority"
          value={form.priority}
          onChange={handleChange}
          disabled={submitting}
          className="border rounded px-2 py-1 flex-1"
        >
          <option value="low">low</option>
          <option value="medium">medium</option>
          <option value="high">high</option>
        </select>
        <input
          type="date"
          name="due_date"
          value={form.due_date}
          onChange={handleChange}
          disabled={submitting}
          className="border rounded px-2 py-1"
        />
      </div>
      <button
        type="submit"
        disabled={submitting}
        className="bg-green-600 text-white px-4 py-1 rounded hover:bg-green-700"
      >
        {submitting ? 'Saving…' : form.id ? 'Update Task' : 'Create Task'}
      </button>
    </form>
  );
}
