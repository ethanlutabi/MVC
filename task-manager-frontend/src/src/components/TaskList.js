// src/components/TaskList.js
import React, { useEffect, useState } from 'react';
import axios from 'axios';

export default function TaskList() {
  const [tasks, setTasks] = useState([]);
  const [page, setPage] = useState(1); // optional if paginated backend
  const [loading, setLoading] = useState(true);

  const fetchTasks = async () => {
    try {
      setLoading(true);
      const res = await axios.get(`http://localhost:8000/api/tasks?page=${page}`);
      setTasks(res.data); // Update if you're returning `data.data`
    } catch (err) {
      console.error('Error loading tasks', err);
    } finally {
      setLoading(false);
    }
  };

  const handleDelete = async (id) => {
    if (!window.confirm("Are you sure?")) return;

    try {
      await axios.delete(`http://localhost:8000/api/tasks/${id}`);
      fetchTasks(); // reload
    } catch (err) {
      console.error('Failed to delete task', err);
    }
  };

  useEffect(() => {
    fetchTasks();
  }, [page]);

  return (
    <div className="p-6">
      <h2 className="text-xl font-semibold mb-4">All Tasks</h2>

      {loading ? (
        <p>Loading tasks...</p>
      ) : (
        <div>
          <table className="w-full text-left border">
            <thead>
              <tr className="bg-gray-100">
                <th className="p-2 border">Title</th>
                <th className="p-2 border">Completed</th>
                <th className="p-2 border">Priority</th>
                <th className="p-2 border">Due Date</th>
                <th className="p-2 border">Actions</th>
              </tr>
            </thead>
            <tbody>
              {tasks.map((task) => (
                <tr key={task.id}>
                  <td className="p-2 border">{task.title}</td>
                  <td className="p-2 border">{task.completed ? 'Yes' : 'No'}</td>
                  <td className="p-2 border">{task.priority}</td>
                  <td className="p-2 border">{task.due_date || '-'}</td>
                  <td className="p-2 border">
                    <button
                      onClick={() => alert('Implement Edit!')}
                      className="bg-yellow-400 px-2 py-1 rounded mr-2"
                    >
                      Edit
                    </button>
                    <button
                      onClick={() => handleDelete(task.id)}
                      className="bg-red-500 text-white px-2 py-1 rounded"
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>

          {/* Simple Pagination UI if needed */}
          <div className="mt-4 flex gap-2">
            <button
              onClick={() => setPage(page - 1)}
              disabled={page === 1}
              className="bg-gray-300 px-3 py-1 rounded"
            >
              Prev
            </button>
            <span>Page {page}</span>
            <button
              onClick={() => setPage(page + 1)}
              className="bg-gray-300 px-3 py-1 rounded"
            >
              Next
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
