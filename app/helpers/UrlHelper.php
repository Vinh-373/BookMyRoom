<?php
function slugify($text) {
    $text = mb_strtolower($text, 'UTF-8');

    // xử lý chữ đ trước
    $text = str_replace(['đ', 'Đ'], 'd', $text);

    // bỏ dấu tiếng Việt
    $text = preg_replace('/[áàảãạăắằẳẵặâấầẩẫậ]/u', 'a', $text);
    $text = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $text);
    $text = preg_replace('/[íìỉĩị]/u', 'i', $text);
    $text = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $text);
    $text = preg_replace('/[úùủũụưứừửữự]/u', 'u', $text);
    $text = preg_replace('/[ýỳỷỹỵ]/u', 'y', $text);

    // thay khoảng trắng bằng -
    $text = preg_replace('/\s+/', '-', $text);

    // loại bỏ ký tự đặc biệt
    $text = preg_replace('/[^a-z0-9\-]/', '', $text);

    // xóa dấu - dư
    $text = preg_replace('/-+/', '-', $text);

    return trim($text, '-');
}