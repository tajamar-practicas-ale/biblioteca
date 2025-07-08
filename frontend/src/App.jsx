import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import BookManager from './components/BookManager';

function App() {

  return (
    <div className="min-h-screen bg-gray-100 text-gray-800 p-6">
      <header className="mb-6">
        <h1 className="text-3xl font-bold text-center">📖 Sistema de Biblioteca</h1>
      </header>

      <main>
        <BookManager />
      </main>
    </div>
  )
}

export default App
