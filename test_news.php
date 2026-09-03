<?php
$html = file_get_contents('http://127.0.0.1:8000/news');
echo "HTML Length: " . strlen($html) . "\n";
if (strpos($html, 'المؤسسة تعزز شراكاتها لتحقيق أثر تنموي مستدام') !== false) {
    echo "Found ID 6\n";
} else {
    echo "MISSING ID 6\n";
}
if (strpos($html, 'إطلاق مبادرة لتعزيز الاستقرار والإرشاد الأسري') !== false) {
    echo "Found ID 7\n";
} else {
    echo "MISSING ID 7\n";
}
if (strpos($html, 'المؤسسة تنفذ برنامجًا تدريبيًا لتطوير مهارات الشباب') !== false) {
    echo "Found ID 9\n";
} else {
    echo "MISSING ID 9\n";
}
