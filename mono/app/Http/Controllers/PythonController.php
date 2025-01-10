<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PythonController extends Controller
{
    private $scriptPath;
    private $logFile;

    public function __construct()
    {
        // Inisialisasi properti
        $this->scriptPath = 'C:\xampp\htdocs\UAS\finger_count\main.py';
        $this->logFile = storage_path('logs/python_process.log');
    }

    public function index()
    {
        return view('python-control');
    }

    public function startPython()
    {
        // Check if script is already running
        $output = [];
        exec("ps aux | grep main.py | grep -v grep", $output);
        if (count($output) > 0) {
            return redirect('/')->with('message', 'Python script is already running!');
        }

        // Start the Python script
        $command = "python3 {$this->scriptPath} > {$this->logFile} 2>&1 &";
        exec($command);

        return redirect('/')->with('message', 'Python script started!');
    }

    public function stopPython()
    {
        // Kill the Python script process
        exec("pkill -f main.py");

        return redirect('/')->with('message', 'Python script stopped!');
    }
}
