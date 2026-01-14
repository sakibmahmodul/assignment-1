<?php
include_once "student-manager.php";
$data = json_decode(file_get_contents('data\students.json'), true);
$sm = new StudentManager($data);
$createdData = [
    "name" => '',
    "email" => '',
    "phone" => '',
    "status" => ''
];
$nameErr = $emailErr = $phoneErr = $statusErr = '';
$name = $email = $phone = $status = '';
function testInput($data)
{
    $data = trim($data);
    $data = stripcslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required!";
    } else {
        $name = testInput($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
        $createdData['name'] = $name;
    }
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = testInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
        $createdData['email'] = $email;
    }
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = testInput($_POST["phone"]);
        if (!preg_match("/^[0-9\+\-\s\(\)]{10,}$/", $phone)) {
            $phoneErr = "Invalid phone number";
        } else {

            $createdData['phone'] = $phone;
        }
    }
    if (empty($_POST["status"])) {
        $statusErr = "Please select student status";
    } else {
        $status = $_POST["status"];
        $createdData['status'] = $status;
    }
}
if ($createdData['name'] != '' && $createdData['email'] != '' && $createdData['phone'] != '') {

    $sm->create($createdData);
    header("location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" class="h-full ">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Student | Student.io</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: "Inter", sans-serif;
        }

        .error {
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>

<body class="h-full ">
    <div class="min-h-full flex flex-col">
        <nav class="bg-indigo-600 pb-32">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <span class="text-white font-bold text-xl tracking-tight">STUDENT.IO</span>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button
                                class="rounded-full bg-indigo-700 p-1 text-indigo-200 hover:text-white focus:outline-none">
                                <span class="sr-only">View notifications</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <header class="py-10">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold tracking-tight text-white">
                        Student Create
                    </h1>
                </div>
            </header>
        </nav>

        <main class="-mt-32">
            <div class="mx-auto max-w-3xl px-4 pb-12 sm:px-6 lg:px-8">
                <form method="post" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                    class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
                    <div class="px-4 py-6 sm:p-8">
                        <div class="mb-8">
                            <h2 class="text-base font-semibold leading-7 text-gray-900">
                                Student Information
                            </h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                Update the student's personal details and enrollment status.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Name</label>
                                <div class="mt-2">
                                    <input type="text" name="name" id="name" autocomplete="name" placeholder="John Doe"
                                        value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm sm:leading-6 outline-none focus:ring-1 focus:ring-indigo-600" />
                                    <span class="error">* <?php echo $nameErr; ?></span>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email
                                    address</label>
                                <div class="mt-2">
                                    <input id="email" name="email" type="email" autocomplete="email"
                                        value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                                        placeholder="john@example.com"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm sm:leading-6 outline-none focus:ring-1 focus:ring-indigo-600" />
                                    <span class="error">* <?php echo $emailErr; ?></span>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="phone" class="block text-sm font-medium leading-6 text-gray-900">Phone
                                    Number</label>
                                <div class="mt-2">
                                    <input type="tel" name="phone" id="phone" autocomplete="tel"
                                        value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>"
                                        placeholder=" +1 (555) 123-4567"
                                        class="block w-full rounded-md border-0 p-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 sm:text-sm sm:leading-6 outline-none focus:ring-1 focus:ring-indigo-600" />
                                    <span class="error">* <?php echo $phoneErr; ?></span>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Enrollment
                                    Status</label>
                                <div class="mt-2">
                                    <select id="status" name="status"
                                        class="block w-full rounded-md border-0 p-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 sm:max-w-xs sm:text-sm sm:leading-6 outline-none focus:ring-1 focus:ring-indigo-600">
                                        <option value="Active" <?php echo ($status == 'Active') ? 'selected' : ''; ?>>
                                            Active</option>
                                        <option value="On Leave" <?php echo ($status == 'On Leave') ? 'selected' : ''; ?>>
                                            On Leave</option>
                                        <option value="Graduated" <?php echo ($status == 'Graduated') ? 'selected' : ''; ?>>Graduated</option>
                                        <option value="Inactive" <?php echo ($status == 'Inactive') ? 'selected' : ''; ?>>
                                            Inactive</option>
                                    </select>
                                    <span class="error">* <?php echo $statusErr; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8 bg-gray-50 rounded-b-xl">
                        <a href="index.php"
                            class="text-sm font-semibold leading-6 text-gray-900 hover:text-gray-700">Cancel</a>
                        <button type="submit"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
        <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="text-center text-sm text-gray-500">
                    &copy; 2025 Student Management System.
                </p>
            </div>
        </footer>
    </div>
</body>

</html>