import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import TaskForm from './TaskForm';
import RecentTasks from './RecentTasks';
import api from '../api';

export default function Home() {
  const navigate = useNavigate();
  const [editTask, setEditTask] = useState(null);

  const onSaved = () => {
    setEditTask(null);
    // no need to refresh here: RecentTasks will fetch on mount
  };

  return (
    <div className="max-w-2xl mx-auto p-6">
      <TaskForm onSaved={onSaved} initial={editTask} />
      <RecentTasks onEdit={setEditTask} />
      <div className="text-center mt-6">
        <button
          onClick={() => navigate('/tasks')}
          className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          View All Tasks
        </button>
      </div>
    </div>
  );
}
