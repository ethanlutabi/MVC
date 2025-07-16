import React, { useState } from 'react';
import TaskForm from './TaskForm';
import EditForm from './EditForm';
import RecentTasks from './RecentTasks';

export default function Home() {
  const [editTask, setEditTask] = useState(null);

  const onSaved = () => {
    setEditTask(null); // Exit edit mode
  };

  return (
    <div className="max-w-2xl mx-auto p-6">
      {editTask ? (
        <EditForm task={editTask} onSaved={onSaved} />
      ) : (
        <TaskForm onSaved={onSaved} />
      )}

      <RecentTasks onEdit={setEditTask} />
    </div>
  );
}
