<?php
class Http {
    public static function get(string $url): ?string {
        $body = self::phpGet($url);
        if ($body !== null) return $body;
        if (PHP_OS_FAMILY === 'Windows') {
            $body = self::nodeGet($url);
            if ($body !== null) return $body;
        }
        return null;
    }

    private static function phpGet(string $url): ?string {
        $ctx = stream_context_create(['http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n",
            'timeout' => 15,
            'follow_location' => 1,
            'max_redirects' => 5,
            'ignore_errors' => true,
        ]]);
        $body = @file_get_contents($url, false, $ctx);
        if ($body === false || $body === '') {
            if (function_exists('curl_version')) {
                $ch = curl_init($url);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_MAXREDIRS => 5,
                    CURLOPT_TIMEOUT => 15,
                    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);
                $body = curl_exec($ch);
                curl_close($ch);
            }
        }
        return ($body === false || $body === '') ? null : $body;
    }

    private static function nodeGet(string $url): ?string {
        $ua = json_encode("Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36", JSON_UNESCAPED_SLASHES);
        $u = json_encode($url, JSON_UNESCAPED_SLASHES);
        $mod = strpos($url, 'https') === 0 ? 'https' : 'http';
        $out = sys_get_temp_dir() . '/o_' . bin2hex(random_bytes(4)) . '.tmp';
        $tmp = sys_get_temp_dir() . '/h_' . bin2hex(random_bytes(4)) . '.mjs';
        $js = "import $mod from'$mod';\n"
            . "import fs from'fs';\n"
            . "const url=$u, out=" . json_encode($out, JSON_UNESCAPED_SLASHES) . ";\n"
            . "function get(u, cb){\n"
            . "const r=$mod.get(u,{headers:{'User-Agent':$ua},rejectUnauthorized:false,timeout:15000},r=>{\n"
            . "if(r.statusCode>=300&&r.statusCode<400&&r.headers.location){\n"
            . "r.resume();\n"
            . "get(new URL(r.headers.location,u).href,cb);\n"
            . "return;\n"
            . "}\n"
            . "let d=[];\n"
            . "r.on('data',c=>d.push(c));\n"
            . "r.on('end',()=>cb(Buffer.concat(d)));\n"
            . "});\n"
            . "r.on('timeout',()=>{r.destroy();cb('');});\n"
            . "r.on('error',()=>cb(''));\n"
            . "}\n"
            . "get(url, d=>{fs.writeFileSync(out,d);});";
        file_put_contents($tmp, $js);
        shell_exec('node ' . escapeshellarg($tmp));
        unlink($tmp);
        $body = file_exists($out) ? file_get_contents($out) : null;
        @unlink($out);
        return ($body === null || $body === '') ? null : $body;
    }
}
