<?php

//namespace app\Helpers;

use Carbon\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Http\File;


if (!function_exists('isActiveRoute')) {
    function isActiveRoute($route)
    {
        return request()->routeIs($route) ? 'active' : '';
    }
}



function highlightHtmlMultiple($text, array $keywords)
{
    // Hilangkan HTML agar aman
    $text = strip_tags($text);

    // Bersihkan kata kosong
    $keywords = array_filter($keywords, fn($k) => trim($k) !== '');

    if (empty($keywords)) return $text;

    // Escape regex
    $escaped = array_map(fn($k) => preg_quote($k, '/'), $keywords);

    // Gabungkan OR
    $pattern = implode('|', $escaped);

    // Replace langsung
    return preg_replace(
        "/($pattern)/i",
        '<mark style="background:#ffc107;color:#000;padding:2px 4px;border-radius:3px;">$1</mark>',
        $text
    );
}



/**
 * Parsing url image dari rss feed description
 *
 * @param string $content
 * @return string
 */
function first_image_or_default($html, $default)
{
    preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $html, $matches);
    return $matches['src'] ?? $default;
}

if (! function_exists('get_tag_image')) {
    function get_tag_image(string $content)
    {
        if (preg_match('/<img.+?src="(.+?)"/', $content, $match)) {
            return $match[1];
        }

        return asset('img/no-image.png');
    }
}

/**
 * Uploads an image.
 *
 * @param      <type>  $image  The image
 * @param      string $file The file
 *
 * @return     string  ( description_of_the_return_value )
 */
function upload_image($image, $file)
{
    $extension = $image->getClientOriginalExtension();
    $path = public_path('uploads/' . $file . '/');
    if (!file_exists($path)) {
        File::makeDirectory($path, 0777, true);
    }

    $name = time() . uniqid();
    $img = Image::make($image->getRealPath());
    $img->save($path . $name . '.' . $extension);
    return $name . '.' . $extension;
}

/**
 * Generate Password
 *
 * @param      integer $length Length Character
 *
 * @return     string   voucher
 */
function generate_password($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $number = '0123456789';
    $charactersLength = strlen($characters);
    $numberLength = strlen($number);
    $randomString = '';
    for ($i = 0; $i < 3; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    for ($i = 0; $i < 3; $i++) {
        $randomString .= $number[rand(0, $numberLength - 1)];
    }
    $randomString = str_shuffle($randomString);
    return $randomString;
}

/**
 * Respon Meta
 *
 * @param      <type>  $message  The message
 */
function respon_meta($code, $message)
{
    $meta = [
        'code' => $code,
        'message' => $message
    ];
    return $meta;
}

function convert_xml_to_array($filename)
{
    try {
        $xml = file_get_contents($filename);
        $convert = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($convert);
        $array = json_decode($json, true);
        return $array;
    } catch (\Exception $e) {
        \Log::info([
            "ERROR MESSAGE" => $e->getMessage(),
            "LINE" => $e->getLine(),
            "FILE" => $e->getFile()
        ]);
        return false;
        // throw new \UnexpectedValueException(trans('message.news.import-error'), 1);
    }
}

function convert_born_date_to_age($date)
{
    $from = new DateTime($date);
    $to   = new DateTime('today');
    return $from->diff($to)->y;
	
}function convert_date_to_qty($start,$end)
{
    $from = new DateTime($start);
    $to   = new DateTime($end);
    return $from->diff($to)->d + 1;
}

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}

function years_list()
{
    // Create Year List for 4 years ago
    $this_year = date('Y');
    $year_list = [];

    for ($i = 1; $i <= 3; $i++) {
        $year_list[] = (int) $this_year--;
    }

    return $year_list;
}

function months_list()
{
    return [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
}

function get_words($sentence, $count = 10)
{
    preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
    return $matches[0];
}

function diff_for_humans($date)
{
    return  Carbon::parse($date)->diffForHumans();
}

function format_datetime($date)
{
    return  Carbon::parse($date)->translatedFormat('d F Y H:i:s');
}


if (!function_exists('format_date')) {
    function format_date($date)
    {
        Carbon::setLocale('id');
        return Carbon::parse($date)->translatedFormat('d F Y');
    }
}

function semester()
{
    return [
        1 => [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
        ],
        2 => [
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ]
    ];
}


function is_product($filename = null)
{
    if ($filename) {
        return is_img('uploads/products/' . $filename);
    }

    return asset('front/img/property-1.jpg');
}

function is_slide($filename = null)
{
    if ($filename) {
        return is_img('uploads/products/slide/' . $filename);
    }

    return asset('front/img/property-1.jpg');
}

function is_logo($url = '', $file = '/logo_100x100.webp')
{
    return is_img($url, $file);
}

function is_img($path = null, $default = 'front/img/property-1.jpg')
{
    if ($path && file_exists(storage_path('app/public/' . $path))) {
        return asset('storage/' . $path);
    }

    return asset($default);
}

function is_user($url = null)
{
    if ($url) {
        $url = 'front/images/customers/' . $url;
    }

    $default = 'https://avatar.iran.liara.run/public';

    return is_img($url, $default);
}

function avatar($foto)
{
    if ($foto) {
        $foto = 'storage/user/' . $foto;
    }

    $default = 'bower_components/admin-lte/dist/img/user2-160x160.jpg';

    return is_img($foto, $default);
}

if (! function_exists('divnum')) {
    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }
}

if (! function_exists('format_number_id')) {
    function format_number_id($inp = 0)
    {
        return number_format($inp, 2, ',', '.');
    }
}

function terbilang($angka)
{
    $angka=abs($angka);
    $baca =["", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas"];

    $terbilang="";
    if ($angka < 12) {
        $terbilang= " " . $baca[$angka];
    } elseif ($angka < 20) {
        $terbilang= terbilang($angka - 10) . " Belas";
    } elseif ($angka < 100) {
        $terbilang= terbilang($angka / 10) . " Puluh" . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $terbilang= " seratus" . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $terbilang= terbilang($angka / 100) . " Ratus" . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $terbilang= " seribu" . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $terbilang= terbilang($angka / 1000) . " Ribu" . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $terbilang= terbilang($angka / 1000000) . " Juta" . terbilang($angka % 1000000);
    }

    return $terbilang;
}