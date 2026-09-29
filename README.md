# 📋 Task Manager

A simple CRUD web app for adding, editing, deleting, and tracking tasks. Built with **plain PHP (PDO)** and **MySQL**, with a modern gradient UI.

<img width="1366" height="768" alt="image" src="https://github.com/user-attachments/assets/acce5d28-4c76-4aa9-a9c1-1420c7e07708" />


---

## ✨ Features

- ➕ **Add** a task (name, description, status, due date)
- ✏️ **Edit** an existing task
- 🗑️ **Delete** a task (with confirmation prompt)
- 🔄 **Toggle status** between `Pending` and `Completed`
- 📅 Tasks sorted by due date
- ✅ Success/error alert messages
- 🔒 Prepared statements (PDO) to prevent SQL injection, and `htmlspecialchars()` to prevent XSS

---

## 🧰 Tech Stack

| Layer     | Technology                         |
|-----------|------------------------------------|
| Backend   | PHP 7.4+ (PDO)                     |
| Database  | MySQL / MariaDB                    |
| Frontend  | HTML, CSS (custom), Poppins font   |
| Server    | XAMPP / Laragon / WAMP (Apache)    |

> **Note:** This version does **not** use Laravel. See the [Laravel section](#-laravel-version) below for the Laravel equivalent and how to port it.

---

## 📁 Project Structure

```
task-manager/
├── config.php          # Database connection (PDO)
├── index.php           # View all tasks
├── add_task.php        # Add a new task
├── edit_task.php       # Edit a task
├── delete_task.php     # Delete a task
├── update_status.php   # Toggle Pending / Completed
├── style.css           # Styling
├── screenshot.png      # Preview image
└── README.md
```

---

## 🚀 Installation (Plain PHP)

### 1. Requirements
- XAMPP / Laragon / WAMP (PHP + MySQL + Apache)

### 2. Copy the project
Put the folder inside your web root:

- XAMPP: `C:\xampp\htdocs\task-manager`
- Laragon: `C:\laragon\www\task-manager`

### 3. Create the database
Open phpMyAdmin (or MySQL CLI) and run:

```sql
CREATE DATABASE boragay CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE boragay;

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('Pending', 'Completed') NOT NULL DEFAULT 'Pending',
    due_date DATE NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional sample data
INSERT INTO tasks (task_name, description, status, due_date) VALUES
('Sample Task 1', 'This is a sample task description.', 'Pending', '2026-10-01'),
('Sample Task 2', 'Another sample task.', 'Completed', '2026-09-20');
```

### 4. Configure the connection
Edit `config.php` if your MySQL settings are different:

```php
$host = "localhost";
$db_name = "boragay";
$username = "root";   // change if needed
$password = "";       // change if you have a password
```

### 5. Run it
Start Apache and MySQL, then open:

```
http://localhost/task-manager/
```

---

## 🔴 Laravel Version

The app above is plain PHP. If you want to build it with **Laravel**, this is how each file maps to Laravel's structure:

| Plain PHP file       | Laravel equivalent                                          |
|----------------------|-------------------------------------------------------------|
| `config.php`         | `.env` (DB settings) + `config/database.php`                |
| SQL `CREATE TABLE`   | Migration (`database/migrations/..._create_tasks_table.php`)|
| `index.php`          | `TaskController@index` + `resources/views/tasks/index.blade.php` |
| `add_task.php`       | `TaskController@create` / `@store`                          |
| `edit_task.php`      | `TaskController@edit` / `@update`                           |
| `delete_task.php`    | `TaskController@destroy`                                    |
| `update_status.php`  | `TaskController@toggleStatus`                               |
| `style.css`          | `public/css/style.css`                                      |

### Laravel setup

**Requirements:** PHP 8.2+, Composer, MySQL

```bash
# 1. Create the project
composer create-project laravel/laravel task-manager
cd task-manager

# 2. Generate model + migration + controller
php artisan make:model Task -mc --resource
```

**`.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=boragay
DB_USERNAME=root
DB_PASSWORD=
```

**Migration** (`database/migrations/xxxx_create_tasks_table.php`)
```php
Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('task_name');
    $table->text('description')->nullable();
    $table->enum('status', ['Pending', 'Completed'])->default('Pending');
    $table->date('due_date')->nullable();
    $table->timestamps();
});
```

**Model** (`app/Models/Task.php`)
```php
protected $fillable = ['task_name', 'description', 'status', 'due_date'];
```

**Routes** (`routes/web.php`)
```php
use App\Http\Controllers\TaskController;

Route::resource('tasks', TaskController::class)->except(['show']);
Route::patch('tasks/{task}/status', [TaskController::class, 'toggleStatus'])
    ->name('tasks.status');
Route::redirect('/', '/tasks');
```

**Controller** (`app/Http/Controllers/TaskController.php`)
```php
public function index()
{
    $tasks = Task::orderBy('due_date')->orderByDesc('id')->get();
    return view('tasks.index', compact('tasks'));
}

public function store(Request $request)
{
    $data = $request->validate([
        'task_name'   => 'required|string|max:255',
        'description' => 'nullable|string',
        'status'      => 'required|in:Pending,Completed',
        'due_date'    => 'nullable|date',
    ]);

    Task::create($data);
    return redirect()->route('tasks.index')->with('msg', 'Task added successfully!');
}

public function toggleStatus(Task $task)
{
    $task->update([
        'status' => $task->status === 'Pending' ? 'Completed' : 'Pending',
    ]);
    return redirect()->route('tasks.index')->with('msg', "Status updated to {$task->status}!");
}

public function destroy(Task $task)
{
    $task->delete();
    return redirect()->route('tasks.index')->with('msg', 'Task deleted successfully!');
}
```

Then:
1. Convert each PHP page into a Blade view in `resources/views/tasks/` (use `{{ }}` instead of `htmlspecialchars()`, and add `@csrf` inside every form).
2. Move `style.css` to `public/css/style.css`.
3. Run:

```bash
php artisan migrate
php artisan serve
```

Open `http://127.0.0.1:8000`.

---

## 🔐 Notes / Tips

- Never commit real database passwords. In Laravel, keep them in `.env`.
- For production, turn off PHP error display and don't `die()` with raw DB error messages.
- Consider adding CSRF protection to the plain PHP forms (Laravel does this automatically with `@csrf`).

---

## 📝 License

Free to use for learning and school projects.
