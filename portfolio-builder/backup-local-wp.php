<?php
/**
 * اسکریپت پشتیبان‌گیری از دیتابیس وردپرس
 * نسخه تست: بدون فشرده‌سازی برای عیب‌یابی Task Scheduler
 */

// --- تنظیمات مهم برای لاراگون ---
$mysqldumpPath = 'C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysqldump.exe'; 
$wpConfigPath = __DIR__ . '/wp-config.php';
$backupDir = __DIR__ . '/wp-backups';

// --- منطق اصلی اسکریپت ---
date_default_timezone_set('UTC');
set_time_limit(0);

echo "--- شروع پشتیبان‌گیری (حالت تست بدون فشرده‌سازی) ---" . PHP_EOL;

if (!file_exists($mysqldumpPath)) {
    die("خطا: فایل mysqldump.exe یافت نشد.");
}
$wpConfigContent = file_get_contents($wpConfigPath);

function get_wp_config_value($constant_name, $config_content) {
    $pattern = "/define\(\s*[\'\"]" . preg_quote($constant_name, '/') . "[\'\"],\s*[\'\"](.*?)[\'\"]\s*\)/";
    if (preg_match($pattern, $config_content, $matches)) {
        return $matches[1];
    }
    return null;
}

$db_name = get_wp_config_value('DB_NAME', $wpConfigContent);
$db_user = get_wp_config_value('DB_USER', $wpConfigContent);
$db_pass = get_wp_config_value('DB_PASSWORD', $wpConfigContent);
$db_host = get_wp_config_value('DB_HOST', $wpConfigContent);

if ($db_name === null || $db_user === null || $db_pass === null || $db_host === null) {
    die("خطا: استخراج اطلاعات دیتابیس ممکن نبود.");
}
echo "اطلاعات دیتابیس با موفقیت بارگذاری شد." . PHP_EOL;

if (!is_dir($backupDir)) {
    if (!mkdir($backupDir, 0755, true)) {
        die("خطا: ساخت پوشه پشتیبان ممکن نبود.");
    }
}

// *** تغییر ۱: نام فایل دیگر .gz ندارد ***
$backupFileName = "backup_{$db_name}_" . date('Y-m-d_H-i-s') . ".sql";
$backupFilePath = $backupDir . '/' . $backupFileName;

// *** تغییر ۲: دستور دیگر شامل gzip و | نمی‌شود ***
$command = sprintf(
    '%s --user=%s --password=%s --host=%s %s > %s',
    escapeshellarg($mysqldumpPath),
    escapeshellarg($db_user),
    escapeshellarg($db_pass),
    escapeshellarg($db_host),
    escapeshellarg($db_name),
    escapeshellarg($backupFilePath) // مستقیم به فایل خروجی می‌ریزد
);

echo "در حال اجرای دستور پشتیبان‌گیری..." . PHP_EOL;
passthru($command, $return_var);

if ($return_var === 0 && file_exists($backupFilePath) && filesize($backupFilePath) > 0) {
    echo "موفقیت‌آمیز بود!" . PHP_EOL;
} else {
    echo "خطا! پشتیبان‌گیری با شکست مواجه شد." . PHP_EOL;
}
?>