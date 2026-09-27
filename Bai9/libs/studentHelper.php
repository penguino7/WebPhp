<?php

/**
 * Thư viện hỗ trợ thao tác dữ liệu sinh viên trong file text (student.txt)
 * Cấu trúc 1 sinh viên = 5 dòng liên tiếp:
 * Dòng 0: Full name (Họ tên)
 * Dòng 1: Birthday (Ngày sinh YYYY-MM-DD)
 * Dòng 2: Address (Địa chỉ)
 * Dòng 3: Image (Tên file ảnh trong uploads/)
 * Dòng 4: Class (Lớp)
 */

function getStudentFilePath(): string
{
    return __DIR__ . '/../student.txt';
}

function getAllStudents(): array
{
    $filePath = getStudentFilePath();
    if (!file_exists($filePath)) {
        return [];
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $students = [];
    $totalLines = count($lines);

    for ($i = 0; $i < $totalLines; $i += 5) {
        $students[] = [
            'name'     => $lines[$i] ?? '',
            'birthday' => $lines[$i + 1] ?? '',
            'address'  => $lines[$i + 2] ?? '',
            'image'    => $lines[$i + 3] ?? 'default.png',
            'class'    => $lines[$i + 4] ?? ''
        ];
    }

    return $students;
}

function getStudentByIndex(int $index): ?array
{
    $students = getAllStudents();
    return $students[$index] ?? null;
}

function saveAllStudents(array $students): bool
{
    $filePath = getStudentFilePath();
    $content = '';

    foreach ($students as $sv) {
        $content .= trim((string)$sv['name']) . PHP_EOL
            . trim((string)$sv['birthday']) . PHP_EOL
            . trim((string)$sv['address']) . PHP_EOL
            . trim((string)$sv['image']) . PHP_EOL
            . trim((string)$sv['class']) . PHP_EOL;
    }

    return file_put_contents($filePath, $content, LOCK_EX) !== false;
}

function addStudent(array $studentData): bool
{
    $filePath = getStudentFilePath();
    $content = trim((string)($studentData['name'] ?? '')) . PHP_EOL
        . trim((string)($studentData['birthday'] ?? '')) . PHP_EOL
        . trim((string)($studentData['address'] ?? '')) . PHP_EOL
        . trim((string)($studentData['image'] ?? 'default.png')) . PHP_EOL
        . trim((string)($studentData['class'] ?? '')) . PHP_EOL;

    return file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX) !== false;
}

function updateStudent(int $index, array $newStudentData): bool
{
    $students = getAllStudents();
    if (!isset($students[$index])) {
        return false;
    }

    $students[$index] = $newStudentData;
    return saveAllStudents($students);
}

function deleteStudent(int $index): bool
{
    $students = getAllStudents();
    if (!isset($students[$index])) {
        return false;
    }

    // Xóa ảnh khỏi uploads/ nếu cần
    $imageFile = $students[$index]['image'] ?? '';
    if ($imageFile && $imageFile !== 'default.png') {
        $imagePath = __DIR__ . '/../uploads/' . $imageFile;
        if (file_exists($imagePath) && is_file($imagePath)) {
            @unlink($imagePath);
        }
    }

    // Xóa khỏi danh sách và đánh lại chỉ số
    unset($students[$index]);
    $students = array_values($students);

    return saveAllStudents($students);
}
