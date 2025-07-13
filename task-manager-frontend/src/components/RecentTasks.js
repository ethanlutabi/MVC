import React, { useEffect, useState } from 'react';
import api from '../api';

export default function RecentTasks({ onEdit }) {
  const [tasks, setTasks] = useState([]);

  const fetchRecent = async () => {
    try {
      const res = await api.get('/tasks?limit=10&sort=desc');
      setTasks(res.data);
    } catch (err) {
      console.error(err);
    }
  };

  const handleDelete = async id => {
    if (!window.confirm('Delete this task?')) return;
    await api.delete(`/tasks/${id}`);
    fetchRecent();
  };

  useEffect(() => { fetchRecent(); }, []);

  return (
    <div className="mb-4">
      <h3 className="text-lg font-semibold mb-2">Most Recent 10 Tasks</h3>
      {tasks.length === 0 ? (
        <p>No tasks found.</p>
      ) : (
        <ul className="space-y-2">
          {tasks.map(task => (
            <li
              key={task.id}
              className="flex justify-between items-center border rounded p-2"
            >
              <div>
                <strong>{task.title}</strong>{' '}
                {task.completed ? <span>✅</span> : <span>❌</span>}
              </div>
              <div className="space-x-2">
                <button
                  onClick={() => onEdit(task)}
                  className="bg-yellow-400 px-2 py-1 rounded"
                >Edit</button>
                <button
                  onClick={() => handleDelete(task.id)}
                  className="bg-red-500 px-2 py-1 text-white rounded"
                >Delete</button>
              </div>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
