import React, { useEffect, useState } from 'react';
import api from '../api';

export default function AllTasks() {
  const [tasks, setTasks] = useState([]);
  const [page, setPage] = useState(1);
  const [lastPage, setLastPage] = useState(1);

  const fetchPage = async pg => {
    try {
      const res = await api.get(`/tasks?page=${pg}&per_page=10`);
      // Laravel paginator returns data under `data` and meta under `meta`
      setTasks(res.data.data);
      setLastPage(res.data.last_page);
      setPage(res.data.current_page);
    } catch (err) {
      console.error(err);
    }
  };

  useEffect(() => { fetchPage(page); }, [page]);

  const handleDelete = async id => {
    if (!window.confirm('Delete this task?')) return;
    await api.delete(`/tasks/${id}`);
    fetchPage(page);
  };

  const handleEdit = task => {
    alert('Implement Edit Route or Modal');
  };

  return (
    <div className="p-6">
      <h2 className="text-xl font-semibold mb-4">All Tasks (Page {page} of {lastPage})</h2>
      <table className="w-full table-auto border-collapse">
        <thead>
          <tr className="bg-gray-100">
            {['Title','Completed','Priority','Due Date','Actions'].map(h => (
              <th key={h} className="border px-2 py-1">{h}</th>
            ))}
          </tr>
        </thead>
        <tbody>
          {tasks.map(t => (
            <tr key={t.id}>
              <td className="border px-2 py-1">{t.title}</td>
              <td className="border px-2 py-1">{t.completed ? 'Yes':'No'}</td>
              <td className="border px-2 py-1">{t.priority}</td>
              <td className="border px-2 py-1">{t.due_date || '-'}</td>
              <td className="border px-2 py-1 space-x-2">
                <button
                  onClick={() => handleEdit(t)}
                  className="bg-yellow-400 px-2 py-1 rounded"
                >Edit</button>
                <button
                  onClick={() => handleDelete(t.id)}
                  className="bg-red-500 px-2 py-1 text-white rounded"
                >Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      <div className="mt-4 flex gap-2">
        <button
          onClick={() => setPage(p => Math.max(1, p - 1))}
          className="bg-gray-300 px-3 py-1 rounded"
          disabled={page === 1}
        >Prev</button>
        <button
          onClick={() => setPage(p => Math.min(lastPage, p + 1))}
          className="bg-gray-300 px-3 py-1 rounded"
          disabled={page === lastPage}
        >Next</button>
      </div>
    </div>
  );
}
