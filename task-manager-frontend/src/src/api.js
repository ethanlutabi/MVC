import React, { useState } from 'react';
import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import Home from './components/Home';
import AllTasks from './components/AllTasks';

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/tasks" element={<AllTasks />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
