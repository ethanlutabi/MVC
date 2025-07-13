// src/App.js or wherever you're using Router
import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Home from './components/Home';
import TaskList from './components/TaskList';

function App() {
  return (
    <Router>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/tasks" element={<TaskList />} />
        {/* Add other routes like /tasks/:id/edit if needed */}
      </Routes>
    </Router>
  );
}

export default App;
