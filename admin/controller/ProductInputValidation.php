<?php
/** Kiểm tra dữ liệu phía server cho sản phẩm và biến thể. */
class ProductInputValidation
{
    public static function product(array $data, array $categories): array
    {
        $errors = [];
        foreach (['product_name'=>255, 'brand'=>120] as $field=>$max) {
            $value = trim((string)($data[$field]??''));
            if ($value === '' || mb_strlen($value)>$max) $errors[] = "$field: bắt buộc, tối đa $max ký tự";
        }
        foreach (['screen'=>50,'chip'=>100,'promotion_label'=>80] as $field=>$max) {
            if (mb_strlen((string)($data[$field]??''))>$max) $errors[]="$field: tối đa $max ký tự";
        }
        $cat = filter_var($data['category_id']??null,FILTER_VALIDATE_INT);
        if (!$cat || !in_array((int)$cat, array_map('intval',array_column($categories,'id')),true)) $errors[]='Danh mục không tồn tại';
        if (!in_array(($data['status']??''),['dang_ban','ngung_ban'],true)) $errors[]='Trạng thái không hợp lệ';
        foreach (['list_price','old_price'] as $field) {
            $value=$data[$field]??'';
            if ($value!=='' && $value!==null && !self::money($value)) $errors[]="$field: giá phải từ 0 đến 9.999.999.999.999,99";
        }
        return $errors;
    }

    public static function variant(array $data, bool $prefixed = false): array
    {
        $prefix = $prefixed ? 'init_' : '';
        $errors=[];
        foreach (['ram'=>0,'storage'=>1,'stock'=>0] as $field=>$minimum) {
            $key=$prefix.($field==='stock'?'stock':$field);
            if (!$prefixed && $field==='stock') $key='stock_quantity';
            $value=filter_var($data[$key]??null,FILTER_VALIDATE_INT);
            if ($value===false || $value===null || $value<$minimum || $value>1000000) $errors[]="$key: số nguyên từ $minimum đến 1000000";
        }
        $sku=trim((string)($data[$prefix.'sku']??''));
        if ($sku==='' || strlen($sku)>100 || !preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]*$/D',$sku)) $errors[]='SKU: 1–100 ký tự, chỉ chữ không dấu, số, dấu chấm, gạch nối và gạch dưới';
        $color=trim((string)($data[$prefix.'color']??''));
        if ($color==='' || mb_strlen($color)>80) $errors[]='Màu sắc: bắt buộc, tối đa 80 ký tự';
        if (!self::money($data[$prefix.'sale_price']??null)) $errors[]='Giá bán không hợp lệ';
        if (!$prefixed && !in_array((string)($data['is_on_sale']??''),['0','1'],true)) $errors[]='Giá trị khuyến mãi không hợp lệ';
        return $errors;
    }

    private static function money($x): bool
    {
        return is_scalar($x) && preg_match('/^\d{1,13}(?:\.\d{1,2})?$/D',(string)$x)===1 && (float)$x <= 9999999999999.99;
    }

    public static function abort(array $errors): void
    {
        if (!$errors) return;
        http_response_code(422);
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><html lang="vi"><meta charset="utf-8"><title>Thông tin chưa hợp lệ</title>';
        echo '<main style="max-width:700px;margin:50px auto;font:16px system-ui"><h2>Vui lòng sửa thông tin</h2><ul>';
        foreach($errors as $error) echo '<li>'.htmlspecialchars($error,ENT_QUOTES,'UTF-8').'</li>';
        echo '</ul><button onclick="history.back()">Quay lại form</button></main></html>';
        exit;
    }
}
